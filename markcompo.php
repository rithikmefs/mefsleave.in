<html>
<head>
<link rel="icon" href="../leave/images/MEFS.png">
<script>
 
  
</script>

<style>
select
{
	border: 5px;
}
.sub
{
	width:10%;
	border-radius:5px;
	background-color:#665763;
	text-align:center;
	font-size:14px;
	transition:background-color 0.3s;
	height:8%;
	color:white;
    margin: 0;
    cursor: pointer;
    transition: background-color 0.2s ease, transform 0.2s ease;	
}	
.sub:hover 
{
	transform: scale(1.05);
	background-color:#493545;
}
a:link
{
text-decoration:none;
}
</style>
</head>
<body style="caret-color:transparent;" bottommargin="0" topmargin="0" leftmargin="0" rightmargin="0">	



<?php
session_start();
include "connect.php";
include "header2.html";


if(isset($_POST['empid']))
{
    $empid = $_POST['empid'];
}

$compodate='';
if(isset($_POST['compodate']))
{
    $compodate = $_POST['compodate'];
}


if(isset($_POST['compodate']))
{
    $compodate = $_POST['compodate'];
	$_SESSION['selectedcompodate'] = $compodate;
}


    $options = [];////////////////////
		
	$sql3 = "SELECT name,empid FROM emp WITH (NOLOCK) WHERE flag='Y' AND empid!='1118' AND empid!='1119' ORDER BY name";
	$res3 = sqlsrv_query($conn, $sql3);
	if ($res3) 
	{
		
        while ($row3 = sqlsrv_fetch_array($res3, SQLSRV_FETCH_ASSOC)) 
		{
			$empid1 = isset($row3['empid']) ? trim($row3['empid']) : '';
			$name1 = isset($row3['name']) ? trim($row3['name']) : '';
			$options[] = "<option value='$empid1'>$name1</option>";
        }
	}	



if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
  
    echo $sql2 = "  INSERT INTO [MISGlobal].[dbo].[compo] (empid, compodate) SELECT $empid , '$compodate' WHERE NOT EXISTS ( SELECT 1 FROM [MISGlobal].[dbo].[compo] WHERE empid = '$empid' AND compodate ='$compodate')";
    $res2=sqlsrv_query($conn, $sql2,);
	$date = DateTime::createFromFormat('Y-m-d', $compodate);
	$cd = $date->format('d-m-Y');   

	$sql4 = "SELECT * FROM [MISGlobal].[dbo].[compo] WHERE empid='$empid' order by compodate";
	
    $res4 = sqlsrv_query($conn, $sql4);
    while($res4 && $row4 = sqlsrv_fetch_array($res4, SQLSRV_FETCH_ASSOC))
    {
		$empid = isset($row4['empid']) ? trim($row4['empid']) : '';
		$compodate = isset($row4['compodate']) ? trim($row4['compodate']) : '';

	}
	
	
	$sql5 = "SELECT name, empid FROM emp WITH (NOLOCK) WHERE empid = '$empid'";
   
    $res5 = sqlsrv_query($conn, $sql5);
    if ($res5) 
	{
        while ($row5 = sqlsrv_fetch_array($res5, SQLSRV_FETCH_ASSOC)) 
		{
            $name = isset($row5['name']) ? trim($row5['name']) : '';
            $empid = isset($row5['empid']) ? trim($row5['empid']) : '';
        }
    }
	

    $rowsAffected = sqlsrv_rows_affected($res2);
        
		if ($rowsAffected > 0) 
		{
            $_SESSION['message'] = "<p style='color:black;caret-color:transparent;font-family:poppins;margin-left:380;'>You have marked <strong>$cd</strong> as compensatory date for <strong>$name</strong></p>";
        } 
		else 
		{
            $_SESSION['message'] = "<b><p style='color:red;caret-color:transparent;font-family:poppins;margin-left:340;'>Compensatory date has already been marked for $name on $cd</p></b>";
        }
	  
	
	  
	header("Location: markcompo.php");
    exit();
}
$selectedcompodate = isset($_SESSION['selectedcompodate']) ? $_SESSION['selectedcompodate'] : '';


?>
    <br><br>
    <br><br>

	<div style='background-color:#FFFFFF;border-radius:10px;width:45%;margin-left:350;box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);'>
	<br>
    <form method="post" action="">
	<div class="custom-select">
	<br><br>
        
		<label style="caret-color:transparent;margin-left:0;" for="empid"><b style="margin-left:113;">Select Employee - </b></label>	
		&nbsp;
		<select style="border-color:black;width:245px;font-family:poppins;font-size:14px;" name="empid" id="empid" required>
		<option value="" disabled selected>Select an Employee</option>
		<?php 
		foreach($options as $option) 
		{
			echo $option;
		} 
		?>
		</select>

		
       &nbsp;&nbsp;
       &nbsp;&nbsp;
	   
	   <br><br>
	   <br><br>
	   
        <label style="caret-color:transparent;margin-left:-8;" for="compodate"><b style="margin-left:108;">Compensate Date - </b></label>
        &nbsp;
		<input style="border-color:black;height:6%;width:245px;font-family:poppins;font-size:14px;padding:10px;" type="date" placeholder="Pick a Date" id="compodate" name="compodate" value="<?php echo $selectedcompodate; ?>"  required>
		
		

        &nbsp;&nbsp;
        &nbsp;&nbsp;
		
		<br><br><br>
		
		
	</div>
	<br>
	</div>
        
        <input style="caret-color:transparent;margin-left:610;" type="submit" value="MARK COMPO" class="sub" >
    </form>
	
	<?php 
	if (isset($_SESSION['message'])) 
	{
        echo "<p>" . $_SESSION['message'] . "</p>";
        unset($_SESSION['message']);
	}
	?>

</center>
<br><br>
<script>

</script>


</body>

</html>

<!--////////////////////////////////////////////-->
