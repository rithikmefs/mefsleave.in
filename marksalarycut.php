<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MEFS - Leave Portal</title>
  <link rel="icon" href="../leave/images/MEFS.png">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

<style>

  /*----------------Mark Salary Cut-------------*/
    body 
	{
	  position:sticky;
	  top:0;
      font-family: 'Poppins', Arial, sans-serif;
    }
	p
	{
	  font-family: system-ui;
	}
	
	.custom-select option 
	{
		font-family:"Poppins", Arial, sans-serif;
    }

.custom-select select 
{
    width: 300px;
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 4px;
    appearance: none;

    background: url('data:image/svg+xml;charset=US-ASCII,<svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M18.425 10.271C19.499 8.967 18.57 7 16.88 7H7.12c-1.69 0-2.618 1.967-1.544 3.271l4.881 5.927a2 2 0 0 0 3.088 0l4.88-5.927Z" clip-rule="evenodd"/></svg>') no-repeat right 10px center;
    background-size: 15px;
}


	
	/*-----------header----------------*/
	

    header 
	{
      background: linear-gradient(135deg, black, black 70%, black);
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 20px;
	  position:sticky;
	  top:0;	 
    }

    .back-button 
	{
      background-color: transparent;
      border: none;
      display: flex;
      align-items: center;
      cursor: pointer;
    }

    .back-button img 
	{
	  caret-color:transparent;
      width: 32px;
      height: auto; 
    }

    .company-name 
	{
      margin: 0;
      font-size: 24px;
	  caret-color:transparent;
    }

    .logout-button 
	{
      background-color: red;
      padding: 6px 15px;
      border-radius: 5px;
      color: white;  
      border: none;
      font-size: 15px;  
      cursor: pointer;  
      text-decoration: none;
	  caret-color:transparent;
    }
	
	.logo 
	{
	  width: 25px;
	  height: auto;
	}
		
		

select
{
	border: 5px;
}
.sub
{
	width:8%;
	border-radius:5px;
	background-color:#28373E;
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
}
a:link
{
text-decoration:none;
}

    #salarycut 
	{
        border-color: black;
        height: 6%;
        width: 165px;
        font-family: poppins;
        font-size: 14px;
        padding: 10px;
        text-align: center;
    }

    #salarycut::-webkit-inner-spin-button,
    #salarycut::-webkit-outer-spin-button 
	{
        -webkit-appearance: none;
        margin: 0;
    }

    .number-input 
	{
        position: relative;
        display: inline-flex;
        align-items: center;
    }

    .number-input button 
	{
        width: 30px;
        height: 100%;
        background-color: #ddd;
        color: black;
        border: 1px solid black;
        cursor: pointer;
        font-size: 16px;
    }

    .number-input button:hover 
	{
        background-color: #bbb;
    }

    .number-input button:active 
	{
        background-color: #999;
    }
	
</style>
</head>

<body style="caret-color:transparent;" bottommargin="0" topmargin="0" leftmargin="0" rightmargin="0">	



<?php
session_start();
include "connect.php";
//include "header2.html";


if(isset($_POST['empid']))
{
    $empid = $_POST['empid'];
}

$salarycut=0;
if(isset($_POST['salarycut']))
{
    $salarycut = $_POST['salarycut'];
}


if(isset($_POST['salarycut']))
{
    $salarycut = $_POST['salarycut'];
	$_SESSION['selectedsalarycut'] = $salarycut;
}

$cutoff = 0;
$ogsalarycut = 0;


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
  	
	$sql5 = "SELECT name,empid,cutoff FROM emp WITH (NOLOCK) WHERE empid = '$empid'";
    $res5 = sqlsrv_query($conn, $sql5);
    if ($res5) 
	{
        while ($row5 = sqlsrv_fetch_array($res5, SQLSRV_FETCH_ASSOC)) 
		{
            $name = isset($row5['name']) ? trim($row5['name']) : '';
            $empid = isset($row5['empid']) ? trim($row5['empid']) : '';	
		    $cutoff = isset($row5['cutoff']) ? trim($row5['cutoff']) : '';
        }
    }
	
	if(!$cutoff)
	{
		$cutoff=0;
	}	

	$ogsalarycut = $cutoff + $salarycut;
	
	if (isset($_POST['submitButton'])) 
	{
		$salarycut = $_POST['salarycut'];
		
		$sql2 = "update emp set cutoff='$ogsalarycut' where empid='$empid'";
		$res2=sqlsrv_query($conn, $sql2,); 
			

		$rowsAffected = sqlsrv_rows_affected($res2);
			
			if ($rowsAffected > 0) 
			{
				$_SESSION['message'] = "<p style='color:black;caret-color:transparent;font-family:poppins;margin-left:440;'>You have marked <strong>$salarycut days</strong> as Salary Cut for <strong>$name</strong><br>Total Salary Cutoff Days of $name - <strong>$ogsalarycut Days</strong></p>";
			} 
			else 
			{
				$_SESSION['message'] = "<b><p style='color:red;caret-color:transparent;font-family:poppins;margin-left:520;'>ERROR</p></b>";
			}
		  
		
		  
		header("Location: marksalarycut.php");
		exit();
	}
}

$selectedsalarycut = isset($_SESSION['selectedsalarycut']) ? $_SESSION['selectedsalarycut'] : '';


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
	   
        <label style="caret-color:transparent;margin-left:-8;" for="compodate"><b style="margin-left:125;">Number of Days - </b></label>
        &nbsp;
		
		<div class="number-input">
			<button type="button" onclick="document.getElementById('salarycut').stepDown()">-</button>
			&nbsp;&nbsp;
			<input type="number" placeholder="Enter No of Days" id="salarycut" name="salarycut" min="-5" value="<?php echo $selectedsalarycut; ?>" required>
			&nbsp;&nbsp;	   
			<button type="button" onclick="document.getElementById('salarycut').stepUp()">+</button>
		</div>
		
		

        &nbsp;&nbsp;
        &nbsp;&nbsp;
		
		<br><br><br>
		
		
	</div>
	<br>
	</div>
        
        <input style="caret-color:transparent;margin-left:625;" type="submit" name="submitButton" value="MARK" class="sub" >
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
