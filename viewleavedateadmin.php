<html>
<head>
<link rel="icon" href="../leave/images/MEFS.png">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>

	.sub
	{
		cursor: pointer;
		transition: background-color 0.3s ease, transform 0.3s ease;	
	}
		
	.sub:hover 
	{
		transform: scale(1.10);
	}

	a:link
	{
	text-decoration:none;
	}

	td
	{
		font-size:15px;
		padding:3px;
	}
	
	tr:hover 
	{
		background-color:#fcfc72;
	}

	 
	#table-container 
	{
		max-height: 340px;
		overflow-y: auto;
		width:520px;
		margin:auto;
		margin-left:410px;
	}

	.report
	{
		caret-color:transparent;
	}
	

</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
</head>


<body class="report" bottommargin="0" topmargin="0" leftmargin="0" rightmargin="0">

<?php
include "header2.html";
?>


<br>
<br>
<br>
<?php
include "connect.php";

$empid='';
if(isset($_GET['empid']))
{
	$empid=$_GET['empid'];
}

$name='';
if(isset($_GET['name']))
{
	$name=$_GET['name'];
}

$totalLeaveBalance1='';
if(isset($_GET['totalLeaveBalance1']))
{
	$totalLeaveBalance1=$_GET['totalLeaveBalance1'];
}

$cutoff='';
if(isset($_GET['cutoff']))
{
	$cutoff=$_GET['cutoff'];
}

$leavetype='';
$trdata='';
$i=0;
$reason='';
$compodate='';


if($empid)
{
	$sql2 = "select name,empid from [MISGlobal].[dbo].[emp] with (nolock) where empid='$empid'";
	$res2 = sqlsrv_query($conn,$sql2);
	$row2 = sqlsrv_fetch_array( $res2, SQLSRV_FETCH_ASSOC);
		if($row2['name']==NULL)
		{
			$row2['name']='';
		}
		$name = trim($row2['name']);
		
		if($row2['empid']==NULL)
		{
			$row2['empid']='';
		}
		$empid  =  trim($row2['empid']);	
    
  
	$sql3 = "select empid,leavedate,leavetype,reason from [MISGlobal].[dbo].[leave] with (nolock) where empid='$empid' order by leavedate desc";
	$res3 = sqlsrv_query($conn,$sql3);
	while($row3 = sqlsrv_fetch_array( $res3, SQLSRV_FETCH_ASSOC))
	{

		if($row3['leavedate']==NULL)
		{
			$row3['leavedate']='';
		}
		$leavedate  =  trim($row3['leavedate']); 
		
		if($row3['leavetype']==NULL)
		{
			$row3['leavetype']='';
		}
		$leavetype  =  trim($row3['leavetype']);	

		if($row3['reason']==NULL || $row3['reason']=='NULL')
		{
			$row3['reason']='';
		}
		$reason  =  trim($row3['reason']);		
		
		
		$color='background-color:#dad9db;';

		
		if($leavetype=='COMPO')
		{
			$color='background-color:#b8f0ad;';
		}
		
		$currentDate = new DateTime(); 
		$date = DateTime::createFromFormat('Y-m-d',$leavedate);
		$formattedDate = $date->format('d-m-Y');
        
		$i=$i+1;
		
		if($reason=='')
		{
			$reason='N/A';
		}	
		
		$sql5 = "select compodate from leave with (nolock) where empid='$empid' and leavetype='COMPO' and leavedate='$leavedate'";
		$res5 = sqlsrv_query($conn,$sql5);
		while($row5 = sqlsrv_fetch_array( $res5, SQLSRV_FETCH_ASSOC))
		{
			if($row5['compodate']==NULL)
			{
				$row5['compodate']='';
			}
			$compodate  =  trim($row5['compodate']);
	
			if($compodate)
			{	
				$currentDate = new DateTime();
				$date2 = DateTime::createFromFormat('Y-m-d', $compodate);
				$formattedcompodate = $date2->format('d-m-Y');
			}	
		}

			
		if($compodate=='')
	    {
			$formattedcompodate='N/A';
		}		

		$iscompodate = ($leavetype=='COMPO');
		$titleAttribute = $iscompodate ? "Compensatory date of $formattedDate is $formattedcompodate" : "";
		$onclickAttribute = $iscompodate ? "onclick=\"displayCompensatoryDate('$formattedcompodate','$formattedDate')\"" : "";	
		
		
			$trdata .= "
			<tr>
			<td title='$titleAttribute' align=center style='$color font-family:Poppins;' $onclickAttribute>$i</td>
			<td title='$titleAttribute' 
			align='center' 
			style='$color font-family:Poppins;' $onclickAttribute>$formattedDate</td>
			<td title='$titleAttribute' align=center style='$color font-family:Poppins;' $onclickAttribute>$leavetype</td>
			<td title='$titleAttribute' align=center style='$color font-family:Poppins;' $onclickAttribute>$reason</td>
			</tr>";
		
	
   }




?>


<script>
function displayCompensatoryDate(compodate,leavedate,leavetype) 
{
	alert('Compensatory date of ' + leavedate + ' is ' + compodate);
}
</script>




<?php
}



if($leavetype=='')
{	

	if($totalLeaveBalance1>0)
	{
		$plus='+';
		$color1='color:green;';
	}
	elseif($totalLeaveBalance1 == 0)
	{
		$plus='';
		$color1='color:#ed9d2d;';
	}	
	else
	{
		$plus='';
		$color1='color:#f75f54;';
	}	
	?>
	<p style="margin-left:444px;"><b>
	<?php
	echo $name.' HAS NOT TAKEN ANY LEAVES YET';
	echo "<br><b style='font-size:30px;font-family:Poppins;'>Current Leave Balance : <b style='$color1 font-size:35px;'>".$plus."".$totalLeaveBalance1."</b></b><br><br>";		
	?>
	</b></p>
	<?php
	
}	
else
{

		
?>	

	<div id='content'>
	
	<?php
	echo "<b style='font-size:20px;font-family:Poppins;top:0;margin-left:545px;'>$name ($empid)</b><br><br>";
	
		if($totalLeaveBalance1>0)
		{
			$plus='+';
			$color1='color:green;';
		}
		elseif($totalLeaveBalance1==0)
		{
			$color1='color:#ed9d2d;';
			$plus='';			
		}
		elseif($totalLeaveBalance1<0)
		{
			$plus='';
			$color1='color:#f75f54;';
		}	
		
	echo "<b style='font-size:17px;font-family:Poppins;top:0;margin-left:520px;'>Leave Report as per : " . $currentDate->format('d-m-Y') . "</b>";
	?>
	<br>
	
	<div id="table-container">
	
		<table padding="5px">
		
		<tr style="position:sticky;top:0;">
		<td align=center style='background-color:#2B1A28;color:white;font-family:Poppins;width:50px;'>SL NO</td>
		<td align=center style='background-color:#2B1A28;color:white;font-family:Poppins;width:145px;'>LEAVE TAKEN DATES</td>
		<td align=center style='background-color:#2B1A28;color:white;font-family:Poppins;width:80px;'>LEAVE TYPE</td>
		<td align=center style='background-color:#2B1A28;color:white;font-family:Poppins;width:180px;'>REASON FOR LEAVE</td>
		</tr>

		<?php 
		
		echo $trdata;
		
		?>
		
		</table>
		
	</div>
	
	<?php
	
	$casual=0;
	$compo=0;
	
	$sq="select count(leavetype) as casualcount from leave where leavetype='CASUAL' and empid='$empid'";
    $rs = sqlsrv_query($conn, $sq);
    while ($rw = sqlsrv_fetch_array($rs, SQLSRV_FETCH_ASSOC)) 
	{
		$casual = $rw['casualcount'];
	}
	
	$sqll="select count(leavetype) as compocount from leave where leavetype='COMPO' and empid='$empid'";
    $ress = sqlsrv_query($conn, $sqll);
    while ($roww = sqlsrv_fetch_array($ress, SQLSRV_FETCH_ASSOC)) 
	{
		$compo = $roww['compocount'];
	}	

	$sql8 = " select count(empid) as compoleave from compo where empid='$empid'";
	$res8 = sqlsrv_query($conn,$sql8);
	while($row8 = sqlsrv_fetch_array( $res8, SQLSRV_FETCH_ASSOC))
	{
		$compoleave = $row8['compoleave'];	
	}
	
	
	echo "<br><b style='font-size:20px;font-family:Poppins;margin-left:535px;'>Casual Leaves taken : <b style='color:black;font-size:20px;'>".$casual."</b></b>";	
	echo "<br><b style='font-size:20px;font-family:Poppins;margin-left:535px;'> Compo Leaves taken : <b style='color:black;font-size:20px;'>".$compo."</b></b><br>";	
	
	
	
	if($cutoff>0)
	{	
		echo "<b style='font-size:20px;font-family:Poppins;margin-left:520px;'>Salary Cut Leave Count : <b style='color:black;font-size:20px;'>".$cutoff."</b></b>";
		echo "<br><b style='font-size:25px;font-family:Poppins;margin-left:490px;'>Current Leave Balance : <b style='$color1 font-size:30px;'>".$plus."".$totalLeaveBalance1."</b></b>";
		echo "<br><b style='font-size:25px;font-family:Poppins;margin-left:500px;'>Compo Leave Balance : <b style='$color1 font-size:30px;'>".$compoleave."</b></b><br>";		
	}
	else
	{
		echo "<b style='font-size:25px;font-family:Poppins;margin-left:490px;'>Current Leave Balance : <b style='$color1 font-size:30px;'>".$plus."".$totalLeaveBalance1."</b></b>";
		echo "<br><b style='font-size:25px;font-family:Poppins;margin-left:500px;'>Compo Leave Balance : <b style='$color1 font-size:30px;'>".$compoleave."</b></b><br>";		
	}	
	

	
	?>
	
	
	</div>


	<img src="../leave/images/print.png" id='download' class='sub' style="width:25px;height:25px;margin-left:650px;" title="click to download the pdf">
	<br><br><br><br><br><br>
	

<script>
    var name = "<?php echo $name; ?>";
    document.getElementById('download').addEventListener('click', function () 
	{
        var content = document.getElementById('content');
        var tableContainer = document.getElementById('table-container');
        var originalMaxHeight = tableContainer.style.maxHeight;
        var originalOverflow = tableContainer.style.overflow;
        tableContainer.style.maxHeight = 'none';
        tableContainer.style.overflow = 'visible';
        content.style.textAlign.marginLeft = '-600';
        content.style.marginLeft = '-300';
        content.style.marginRight = 'auto';
        content.style.width = '100%';

        var opt = 
		{
            margin: [0.5, 0.5, 0.5, 0.5],
            filename: `${name} Leave Report.pdf`,
            image: { type: 'jpeg', quality: 0.99 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
        };

        html2pdf().from(content).set(opt).toPdf().save().then(function() 
		{

            tableContainer.style.maxHeight = originalMaxHeight;
            tableContainer.style.overflow = originalOverflow;
            content.style.textAlign = '';
            content.style.marginLeft = '';
            content.style.marginRight = '';
            content.style.width = '';
        });
		
    });
</script>



<?php
}
?>

</html>