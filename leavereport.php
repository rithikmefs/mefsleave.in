<?php
session_start();
include "connect.php";

$empid='';
if(isset($_SESSION['empid']))
{
	$empid=$_SESSION['empid'];
}

$name = '';
if (isset($_POST['name'])) 
{
    $name = $_POST['name'];
}

$emptype = '';
if (isset($_POST['emptype'])) 
{
    $emptype = $_POST['emptype'];    
}
$doj = '';
if (isset($_POST['doj'])) 
{
    $doj = $_POST['doj'];    
}

$dojtype = '';
if (isset($_POST['dojtype'])) 
{
    $dojtype = $_POST['dojtype'];    
}
$designation = '';
if (isset($_POST['designation'])) 
{
    $designation = $_POST['designation'];    
}

$leavedate = '';
if (isset($_POST['leavedate'])) 
{
    $leavedate = $_POST['leavedate'];    
}

$leavetype = '';
if (isset($_POST['leavetype'])) 
{
    $leavetype = $_POST['leavetype'];    
}

$totalLeaveBalance1 = '';
if (isset($_POST['totalLeaveBalance1'])) 
{
    $totalLeaveBalance1 = $_POST['totalLeaveBalance1'];
}

$trdata = '';
$trdata1 = '';
$i = 0;
$startyear = 0;
$cutoff = 0;



?>

<html>
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="icon" href="../leave/images/MEFS.png">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap">
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>

<style>
.sub
{
	border-radius:10px;
	background-color:black;
	text-align:center;
	transition:background-color 0.3s;
	color:white;
    margin: 0;
    cursor: pointer;
    transition: background-color 0.2s ease, transform 0.2s ease;	
}	
.sub:hover 
{
    background:#363c45;
	color:white;
	transform: scale(1.05);
}

.dis:hover 
{
	transform: scale(1.10);
}


.download:hover
{
    //background-color: #657081;
    transform: scale(1.05);	
}

.download1:hover
{
    //background-color: #657081;
    transform: scale(1.05);	
}


.report1
{
	caret-color:transparent;
}

body 
{
    font-family: Arial, sans-serif;
}

.leave-summary 
{
    width: 100%;
    margin: 0 auto;
    text-align: center;
}

.leave-summary h1 
{
    margin-bottom: 20px;
}

.leave-summary p 
{
    margin: 5px 0;
}


 
#leavesummary th
{
    border: 1px solid black;
} 

#leavesummary th, td 
{
    padding: 15px;
    text-align: center;
} 

th 
{
    background-color: #f2f2f2;
}

td 
{
    background-color: #ffffff;
}

tr:nth-child(2) td
{
    background-color: #ffffff;
}



#table-container
{
    max-height: 370px;
    overflow-y: auto;
    width:520px;
	margin:auto;
	border-collapse: collapse;
	
}

td
{
	font-size:15px;
	padding:3px;
}

a:link
{
text-decoration:none;
}

tbody td:nth-child(odd)
{
	//background-color:#e6eaf0;	
}
.gif-container 
{
    text-align: center;
}

.gif-container:hover
{	
	//background-color: #657081;
    transform: scale(1.05);	
}

.gif-image 
{
    width: 7%;
    height: 20px;
}

</style>
</head>
<body class="report1" bottommargin="0" topmargin="0" leftmargin="0" rightmargin="0">

<?php

include "header2.html";


$leaves = '';
$interval = '';
$yearsWorked = '';
$leavesTaken1 = '';
$totalLeaveBalance = '';
$color = '';
$totalleavesperyear = '';
$reason='';
$compodate='';
$formattedcompodate='';
$monthsDifference = 0;
$jan = 0;
$feb = 0;
$mar = 0;
$apr = 0;
$may = 0;
$jun = 0;
$jul = 0;
$aug = 0;
$sep = 0;
$oct = 0;
$nov = 0;
$dec = 0;




function getLeavesTakenPerMonth($empid) 
{
    GLOBAL $dojtype, $conn, $totalMonthsWorked, $leavesTaken1;

    $sql6 = "SELECT doj, name FROM emp WITH (NOLOCK) WHERE empid='$empid'";
    $res6 = sqlsrv_query($conn, $sql6);
    while ($row6 = sqlsrv_fetch_array($res6, SQLSRV_FETCH_ASSOC)) 
	{
        if ($row6['name'] == NULL) 
		{
            $row6['name'] = '';
        }
        $name = trim($row6['name']);

        if ($row6['doj'] == NULL) 
		{
            $row6['doj'] = '';
        }
        $doj = trim($row6['doj']);
		

        $doj = new DateTime($doj);
        $currentDate = new DateTime(); 
        $interval = $doj->diff($currentDate);
        $monthsDifference = ($interval->y * 12) + $interval->m;
        $yearsWorked = $interval->y + ($interval->m > 0 ? 1 : 0);


        $totalMonthsWorked = ($interval->y * 12) + $interval->m;
        $totalLeaveBalance = 0;
    }

    $sql = "SELECT YEAR(leavedate) AS year, MONTH(leavedate) AS month, count(empid) AS total_leave_count, count(leavetype)
    FROM leave WHERE empid = '$empid' AND leavetype = 'CASUAL' GROUP BY YEAR(leavedate), MONTH(leavedate);";
    $stmt = sqlsrv_query($conn, $sql);


    $leavesTakenPerMonth = [];
    $arrofl = [];
    $leavefinal = '';
    if ($stmt !== false) {
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) 
		{
            $date = $row['year'] . "-" . $row['month'];
            $leave = $row['total_leave_count'];
            if ($row['total_leave_count'] == null) 
			{
                $row['total_leave_count'] = 0;
            }
            $leavesTakenPerMonth[$date] = $row['total_leave_count'];
        }

        sqlsrv_free_stmt($stmt);

    } 
	
	else 
	{
        die(print_r(sqlsrv_errors(), true));
    }

    $totalLeaveBalance = $totalMonthsWorked;
    $totalLeaveBalance1 = '';
    $totleaves = 0;
    foreach ($leavesTakenPerMonth as $month => $leavesTaken1) 
	{
        if ($leavesTaken1) 
		{
            $totalLeaveBalance -= $leavesTaken1;
            $totalLeaveBalance1 = $totalLeaveBalance;    
            $totleaves = $totleaves + $leavesTaken1;
        }
    }

    return (int)$totalLeaveBalance1;
}

$leaveCounts = [];

$sql = "SELECT YEAR(leavedate) AS year, MONTH(leavedate) AS month, count(empid) AS total_leave_count
FROM leave WHERE empid = '$empid' GROUP BY YEAR(leavedate), MONTH(leavedate) ORDER BY YEAR(leavedate), MONTH(leavedate) ;"; // AND leavetype='CASUAL'
$res = sqlsrv_query($conn, $sql);

while ($row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC)) 
{
    $year = $row['year'];
    $month = $row['month'];
    $count = $row['total_leave_count'];

    if (!isset($leaveCounts[$year])) 
	{
        $leaveCounts[$year] = 
		[
            '01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0,
            '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0,
            'sumleave' => 0
        ];
    }

    $leaveCounts[$year][str_pad($month, 2, '0', STR_PAD_LEFT)] += $count;
    $leaveCounts[$year]['sumleave'] += $count;
}



	$sql3 = "select empid,leavedate,leavetype,reason from leave with (nolock) where empid='$empid' order by leavedate desc";
	$res3 = sqlsrv_query($conn,$sql3);
	while($row3 = sqlsrv_fetch_array( $res3, SQLSRV_FETCH_ASSOC))
	{
		if($row3['empid']==NULL)
		{
			$row3['empid']='';
		}
		$empid  =  trim($row3['empid']);
		
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
		
		if($row3['reason']==NULL)
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
		
		$date = DateTime::createFromFormat('Y-m-d', $leavedate);

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
		<td title='$titleAttribute' align=center style='$color font-family:Poppins;' $onclickAttribute>$formattedDate</td>
		<td title='$titleAttribute' align=center style='$color font-family:Poppins;' $onclickAttribute>$leavetype</td>
		<td title='$titleAttribute' align=center style='$color font-family:Poppins;' $onclickAttribute>$reason</td>
		</tr>";
	
	
	}



if($leavedate!='')
{
	
	$plus='';
	
    $sql10 = "SELECT name FROM emp WITH (NOLOCK) WHERE empid='$empid'";
    $res10 = sqlsrv_query($conn, $sql10);
    while ($row10 = sqlsrv_fetch_array($res10, SQLSRV_FETCH_ASSOC)) 
	{	
        if ($row10['name'] == NULL) 
		{
            $row10['name'] = '';
        }
        $name = trim($row10['name']);
	}	
	
?>


<script>
function displayCompensatoryDate(compodate,leavedate,leavetype) 
{
	alert('Compensatory date of ' + leavedate + ' is ' + compodate);
}
</script>
	
	<div id='content'>

	<center>
	<br><br>
	<?php	
	echo "<b style='font-size:20px;font-family:Poppins;'>$name ($empid)</b>";
	echo "<p style='font-size:17px;font-family:Poppins;'><b>Leave Report as per : " . $currentDate->format('d-m-Y') . "</b></p>";
	
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
	




    $sql9 = "SELECT * FROM emp WITH (NOLOCK) WHERE empid='$empid' AND flag='Y'";
    $res9 = sqlsrv_query($conn, $sql9);
    while ($row9 = sqlsrv_fetch_array($res9, SQLSRV_FETCH_ASSOC)) 
	{



		if($row9['cutoff']==NULL)
		{
			$row9['cutoff']='';
		}
		$cutoff = trim($row9['cutoff']);
		
		
	}	


	
	?>
	
	
	<div id="table-container">
	<table padding="5px" class="report">
	<tr style="position:sticky;top:0;">
		<td align=center style='background-color:#28373E;color:white;font-family:Poppins;width:50px;'>SL NO</td>
		<td align=center style='background-color:#28373E;color:white;font-family:Poppins;width:145px;'>LEAVE TAKEN DATES</td>
		<td align=center style='background-color:#28373E;color:white;font-family:Poppins;width:80px;'>LEAVE TYPE</td>
		<td align=center style='background-color:#28373E;color:white;font-family:Poppins;width:180px;'>REASON FOR LEAVE</td>
	</tr>

	<?php 
	echo $trdata;
	?>


	</table>
	
	</div>
	<br> 
	
	
	<?php
	
	$casual=0;
	$compo=0;
	$compoleave=0;
	
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
	
	?>	

</div>	
</center>
<br>

	
	<center>
	<img src="../leave/images/print.png" alt="Download image" id='download' style="width:25px;height:25px;" class="download" title="click to download the pdf">
	</center>
	

	<center>


<?php

$col1='';
$col2='';
$col3='';
$col4='';
$col5='';
$col6='';
$col7='';
$col8='';
$col9='';
$col10='';
$col11='';
$col12='';
$month1='';
$leavedate2='';


$currentDate = date('Y-m-d');


$sql4 = "SELECT YEAR(leavedate) AS year, MONTH(leavedate) AS month, leavedate FROM leave 
		WHERE empid = '$empid' AND leavedate >= '$doj' AND leavedate <= '$currentDate'
		ORDER BY year, month";
$res4 = sqlsrv_query($conn, $sql4);
while ($row4 = sqlsrv_fetch_array($res4, SQLSRV_FETCH_ASSOC)) 
{
    $year = $row4['year'];
    $month1 = $row4['month'];
    $leavedate1 = $row4['leavedate'];
	
	$leavedate2.= $leavedate1;
}


///////////////////////////////////////////////////////////////////////////////////

	
$dates = str_split($leavedate2, 10); // Split into chunks of 10 characters (YYYY-MM-DD)
$groupedDates = [];

foreach ($dates as $date) 
{
    if (!empty($date)) 
	{

        $dateTime = DateTime::createFromFormat('Y-m-d', $date);
        $monthYear = $dateTime->format('Y-m'); 
        if (!isset($groupedDates[$monthYear]))
		{
            $groupedDates[$monthYear] = [];
        }
        $groupedDates[$monthYear][] = $date;
		
    }
}


/////////////////////////////////////////////////////////////////////////////////////
$onclick='No leaves taken on this month';


$sql8 = "SELECT * FROM emp WITH (NOLOCK) WHERE empid='$empid' AND flag='Y'";
$res8 = sqlsrv_query($conn, $sql8);
while ($row8 = sqlsrv_fetch_array($res8, SQLSRV_FETCH_ASSOC)) 
{

		if($row8['cutoff']==NULL)
		{
			$row8['cutoff']='';
		}
		$cutoff = trim($row8['cutoff']);
		
		if($row8['dojtype']==NULL)
		{
			$row8['dojtype']='';
		}
		$dojtype = trim($row8['dojtype']);		


		$trdata1 = ""; 
		$s=0;
		foreach ($leaveCounts as $year => $months) 
		{
			$jan = $months['01'];
			$feb = $months['02'];
			$mar = $months['03'];
			$apr = $months['04'];
			$may = $months['05'];
			$jun = $months['06'];
			$jul = $months['07'];
			$aug = $months['08'];
			$sep = $months['09'];
			$oct = $months['10'];
			$nov = $months['11'];
			$dec = $months['12'];
			$sumleave = $months['sumleave'];

			$color1 = "color: red"; 
		    
			$col1 = ($jan == 0) ? 'background-color:#b8f0ad;' : 'background-color:#DAD9DB;';
			$col2 = ($feb == 0) ? 'background-color:#b8f0ad;' : 'background-color:#DAD9DB;';
			$col3 = ($mar == 0) ? 'background-color:#b8f0ad;' : 'background-color:#DAD9DB;';
			$col4 = ($apr == 0) ? 'background-color:#b8f0ad;' : 'background-color:#DAD9DB;';
			$col5 = ($may == 0) ? 'background-color:#b8f0ad;' : 'background-color:#DAD9DB;';
			$col6 = ($jun == 0) ? 'background-color:#b8f0ad;' : 'background-color:#DAD9DB;';
			$col7 = ($jul == 0) ? 'background-color:#b8f0ad;' : 'background-color:#DAD9DB;';
			$col8 = ($aug == 0) ? 'background-color:#b8f0ad;' : 'background-color:#DAD9DB;';
			$col9 = ($sep == 0) ? 'background-color:#b8f0ad;' : 'background-color:#DAD9DB;';
			$col10 = ($oct == 0) ? 'background-color:#b8f0ad;' : 'background-color:#DAD9DB;';
			$col11 = ($nov == 0) ? 'background-color:#b8f0ad;' : 'background-color:#DAD9DB;';
			$col12 = ($dec == 0) ? 'background-color:#b8f0ad;' : 'background-color:#DAD9DB;';
			
	
			//2018
			$jantitle18 = isset($groupedDates['2018-01']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2018-01']))
			: '';
			$febtitle18 = isset($groupedDates['2018-02']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2018-02']))
			: '';
		    $martitle18 = isset($groupedDates['2018-03']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2018-03']))
			: '';
			$aprtitle18 = isset($groupedDates['2018-04']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2018-04']))
			: '';
			$maytitle18 = isset($groupedDates['2018-05']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2018-05']))
			: '';
			$juntitle18 = isset($groupedDates['2018-06']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2018-06']))
			: '';
			$jultitle18 = isset($groupedDates['2018-07']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2018-07']))
			: '';
			$augtitle18 = isset($groupedDates['2018-08']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2018-08']))
			: '';
			$septitle18 = isset($groupedDates['2018-09']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2018-09']))
			: '';
			$octtitle18 = isset($groupedDates['2018-10']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2018-10']))
			: '';
			$novtitle18 = isset($groupedDates['2018-11']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2018-11']))
			: '';
			$dectitle18 = isset($groupedDates['2018-12']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2018-12']))
			: '';


			//2019
			$jantitle19 = isset($groupedDates['2019-01']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2019-01']))
			: '';
			$febtitle19 = isset($groupedDates['2019-02']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2019-02']))
			: '';
		    $martitle19 = isset($groupedDates['2019-03']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2019-03']))
			: '';
			$aprtitle19 = isset($groupedDates['2019-04']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2019-04']))
			: '';
			$maytitle19 = isset($groupedDates['2019-05']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2019-05']))
			: '';
			$juntitle19 = isset($groupedDates['2019-06']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2019-06']))
			: '';
			$jultitle19 = isset($groupedDates['2019-07']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2019-07']))
			: '';
			$augtitle19 = isset($groupedDates['2019-08']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2019-08']))
			: '';
			$septitle19 = isset($groupedDates['2019-09']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2019-09']))
			: '';
			$octtitle19 = isset($groupedDates['2019-10']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2019-10']))
			: '';
			$novtitle19 = isset($groupedDates['2019-11']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2019-11']))
			: '';
			$dectitle19 = isset($groupedDates['2019-12']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2019-12']))
			: '';


			//2020
			$jantitle20 = isset($groupedDates['2020-01']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2020-01']))
			: '';
			$febtitle20 = isset($groupedDates['2020-02']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2020-02']))
			: '';
		    $martitle20 = isset($groupedDates['2020-03']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2020-03']))
			: '';
			$aprtitle20 = isset($groupedDates['2020-04']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2020-04']))
			: '';
			$maytitle20 = isset($groupedDates['2020-05']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2020-05']))
			: '';
			$juntitle20 = isset($groupedDates['2020-06']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2020-06']))
			: '';
			$jultitle20 = isset($groupedDates['2020-07']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2020-07']))
			: '';
			$augtitle20 = isset($groupedDates['2020-08']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2020-08']))
			: '';
			$septitle20 = isset($groupedDates['2020-09']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2020-09']))
			: '';
			$octtitle20 = isset($groupedDates['2020-10']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2020-10']))
			: '';
			$novtitle20 = isset($groupedDates['2020-11']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2020-11']))
			: '';
			$dectitle20 = isset($groupedDates['2020-12']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2020-12']))
			: '';


			//2021
			$jantitle21 = isset($groupedDates['2021-01']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2021-01']))
			: '';
			$febtitle21 = isset($groupedDates['2021-02']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2021-02']))
			: '';
		    $martitle21 = isset($groupedDates['2021-03']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2021-03']))
			: '';
			$aprtitle21 = isset($groupedDates['2021-04']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2021-04']))
			: '';
			$maytitle21 = isset($groupedDates['2021-05']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2021-05']))
			: '';
			$juntitle21 = isset($groupedDates['2021-06']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2021-06']))
			: '';
			$jultitle21 = isset($groupedDates['2021-07']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2021-07']))
			: '';
			$augtitle21 = isset($groupedDates['2021-08']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2021-08']))
			: '';
			$septitle21 = isset($groupedDates['2021-09']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2021-09']))
			: '';
			$octtitle21 = isset($groupedDates['2021-10']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2021-10']))
			: '';
			$novtitle21 = isset($groupedDates['2021-11']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2021-11']))
			: '';
			$dectitle21 = isset($groupedDates['2021-12']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2021-12']))
			: '';

			
			//2022
			$jantitle22 = isset($groupedDates['2022-01']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2022-01']))
			: '';
			$febtitle22 = isset($groupedDates['2022-02']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2022-02']))
			: '';
		    $martitle22 = isset($groupedDates['2022-03']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2022-03']))
			: '';
			$aprtitle22 = isset($groupedDates['2022-04']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2022-04']))
			: '';
			$maytitle22 = isset($groupedDates['2022-05']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2022-05']))
			: '';
			$juntitle22 = isset($groupedDates['2022-06']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2022-06']))
			: '';
			$jultitle22 = isset($groupedDates['2022-07']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2022-07']))
			: '';
			$augtitle22 = isset($groupedDates['2022-08']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2022-08']))
			: '';
			$septitle22 = isset($groupedDates['2022-09']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2022-09']))
			: '';
			$octtitle22 = isset($groupedDates['2022-10']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2022-10']))
			: '';
			$novtitle22 = isset($groupedDates['2022-11']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2022-11']))
			: '';
			$dectitle22 = isset($groupedDates['2022-12']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2022-12']))
			: '';
		
			
			//2023
			$jantitle23 = isset($groupedDates['2023-01']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2023-01']))
			: '';
			$febtitle23 = isset($groupedDates['2023-02']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2023-02']))
			: '';
		    $martitle23 = isset($groupedDates['2022-03']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2022-03']))
			: '';
			$aprtitle23 = isset($groupedDates['2023-04']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2023-04']))
			: '';
			$maytitle23 = isset($groupedDates['2023-05']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2023-05']))
			: '';
			$juntitle23 = isset($groupedDates['2023-06']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2023-06']))
			: '';
			$jultitle23 = isset($groupedDates['2023-07']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2023-07']))
			: '';
			$augtitle23 = isset($groupedDates['2023-08']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2023-08']))
			: '';
			$septitle23 = isset($groupedDates['2023-09']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2023-09']))
			: '';
			$octtitle23 = isset($groupedDates['2023-10']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2023-10']))
			: '';
			$novtitle23 = isset($groupedDates['2023-11']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2023-11']))
			: '';
			$dectitle23 = isset($groupedDates['2023-12']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2023-12']))
			: '';

			
			//2024
			$jantitle24 = isset($groupedDates['2024-01']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2024-01']))
			: '';
			$febtitle24 = isset($groupedDates['2024-02']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2024-02']))
			: '';
		    $martitle24 = isset($groupedDates['2024-03']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2024-03']))
			: '';
			$aprtitle24 = isset($groupedDates['2024-04']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2024-04']))
			: '';
			$maytitle24 = isset($groupedDates['2024-05']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2024-05']))
			: '';
			$juntitle24 = isset($groupedDates['2024-06']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2024-06']))
			: '';
			$jultitle24 = isset($groupedDates['2024-07']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2024-07']))
			: '';
			$augtitle24 = isset($groupedDates['2024-08']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2024-08']))
			: '';
			$septitle24 = isset($groupedDates['2024-09']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2024-09']))
			: '';
			$octtitle24 = isset($groupedDates['2024-10']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2024-10']))
			: '';
			$novtitle24 = isset($groupedDates['2024-11']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2024-11']))
			: '';
			$dectitle24 = isset($groupedDates['2024-12']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2024-12']))
			: '';

			
			//2025
			$jantitle25 = isset($groupedDates['2025-01']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2025-01']))
			: '';
			$febtitle25 = isset($groupedDates['2025-02']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2025-02']))
			: '';
		    $martitle25 = isset($groupedDates['2025-03']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2025-03']))
			: '';
			$aprtitle25 = isset($groupedDates['2025-04']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2025-04']))
			: '';
			$maytitle25 = isset($groupedDates['2025-05']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2025-05']))
			: '';
			$juntitle25 = isset($groupedDates['2025-06']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2025-06']))
			: '';
			$jultitle25 = isset($groupedDates['2025-07']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2025-07']))
			: '';
			$augtitle25 = isset($groupedDates['2025-08']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2025-08']))
			: '';
			$septitle25 = isset($groupedDates['2025-09']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2025-09']))
			: '';
			$octtitle25 = isset($groupedDates['2025-10']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2025-10']))
			: '';
			$novtitle25 = isset($groupedDates['2025-11']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2025-11']))
			: '';
			$dectitle25 = isset($groupedDates['2025-12']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2025-12']))
			: '';

			
			//2026
			$jantitle26 = isset($groupedDates['2026-01']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2026-01']))
			: '';
			$febtitle26 = isset($groupedDates['2026-02']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2026-02']))
			: '';
		    $martitle26 = isset($groupedDates['2026-03']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2026-03']))
			: '';
			$aprtitle26 = isset($groupedDates['2026-04']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2026-04']))
			: '';
			$maytitle26 = isset($groupedDates['2026-05']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2026-05']))
			: '';
			$juntitle26 = isset($groupedDates['2026-06']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2026-06']))
			: '';
			$jultitle26 = isset($groupedDates['2026-07']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2026-07']))
			: '';
			$augtitle26 = isset($groupedDates['2026-08']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2026-08']))
			: '';
			$septitle26 = isset($groupedDates['2026-09']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2026-09']))
			: '';
			$octtitle26 = isset($groupedDates['2026-10']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2026-10']))
			: '';
			$novtitle26 = isset($groupedDates['2026-11']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2026-11']))
			: '';
			$dectitle26 = isset($groupedDates['2026-12']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2026-12']))
			: '';


			//2027
			$jantitle27 = isset($groupedDates['2027-01']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2027-01']))
			: '';
			$febtitle27 = isset($groupedDates['2027-02']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2027-02']))
			: '';
		    $martitle27 = isset($groupedDates['2027-03']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2027-03']))
			: '';
			$aprtitle27 = isset($groupedDates['2027-04']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2027-04']))
			: '';
			$maytitle27 = isset($groupedDates['2027-05']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2027-05']))
			: '';
			$juntitle27 = isset($groupedDates['2027-06']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2027-06']))
			: '';
			$jultitle27 = isset($groupedDates['2027-07']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2027-07']))
			: '';
			$augtitle27 = isset($groupedDates['2027-08']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2027-08']))
			: '';
			$septitle27 = isset($groupedDates['2027-09']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2027-09']))
			: '';
			$octtitle27 = isset($groupedDates['2027-10']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2027-10']))
			: '';
			$novtitle27 = isset($groupedDates['2027-11']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2027-11']))
			: '';
			$dectitle27 = isset($groupedDates['2027-12']) 
			? implode(', ', array_map(fn($date) => DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'), $groupedDates['2027-12']))
			: '';
			




			
			$isleave = ($jan>0);
			$jantitle2018 = $isleave ? "Leave Taken dates of January 2018 -> $jantitle18" : "";
			$jantitle2019 = $isleave ? "Leave Taken dates of January 2019 -> $jantitle19" : "";
			$jantitle2020 = $isleave ? "Leave Taken dates of January 2020 -> $jantitle20" : "";
			$jantitle2021 = $isleave ? "Leave Taken dates of January 2021 -> $jantitle21" : "";
			$jantitle2022 = $isleave ? "Leave Taken dates of January 2022 -> $jantitle22" : "";
			$jantitle2023 = $isleave ? "Leave Taken dates of January 2023 -> $jantitle23" : "";
			$jantitle2024 = $isleave ? "Leave Taken dates of January 2024 -> $jantitle24" : "";
			$jantitle2025 = $isleave ? "Leave Taken dates of January 2025 -> $jantitle25" : "";
			$jantitle2026 = $isleave ? "Leave Taken dates of January 2026 -> $jantitle26" : "";
			$jantitle2027 = $isleave ? "Leave Taken dates of January 2027 -> $jantitle27" : "";

			$isleave = ($feb>0);
			$febtitle2018 = $isleave ? "Leave Taken dates of February 2018 -> $febtitle18" : "";			
			$febtitle2019 = $isleave ? "Leave Taken dates of February 2019 -> $febtitle19" : "";			
			$febtitle2020 = $isleave ? "Leave Taken dates of February 2020 -> $febtitle20" : "";			
			$febtitle2021 = $isleave ? "Leave Taken dates of February 2021 -> $febtitle21" : "";			
			$febtitle2022 = $isleave ? "Leave Taken dates of February 2022 -> $febtitle22" : "";			
			$febtitle2023 = $isleave ? "Leave Taken dates of February 2023 -> $febtitle23" : "";			
			$febtitle2024 = $isleave ? "Leave Taken dates of February 2024 -> $febtitle24" : "";			
			$febtitle2025 = $isleave ? "Leave Taken dates of February 2025 -> $febtitle25" : "";			
			$febtitle2026 = $isleave ? "Leave Taken dates of February 2026 -> $febtitle26" : "";			
			$febtitle2027 = $isleave ? "Leave Taken dates of February 2027 -> $febtitle27" : "";			
			
			$isleave = ($mar>0);
			$martitle2018 = $isleave ? "Leave Taken dates of March 2018 -> $martitle18" : "";			
			$martitle2019 = $isleave ? "Leave Taken dates of March 2019 -> $martitle19" : "";			
			$martitle2020 = $isleave ? "Leave Taken dates of March 2020 -> $martitle20" : "";			
			$martitle2021 = $isleave ? "Leave Taken dates of March 2021 -> $martitle21" : "";			
			$martitle2022 = $isleave ? "Leave Taken dates of March 2022 -> $martitle22" : "";			
			$martitle2023 = $isleave ? "Leave Taken dates of March 2023 -> $martitle23" : "";			
			$martitle2024 = $isleave ? "Leave Taken dates of March 2024 -> $martitle24" : "";			
			$martitle2025 = $isleave ? "Leave Taken dates of March 2025 -> $martitle25" : "";			
			$martitle2026 = $isleave ? "Leave Taken dates of March 2026 -> $martitle26" : "";			
			$martitle2027 = $isleave ? "Leave Taken dates of March 2027 -> $martitle27" : "";			
																    
			$isleave = ($apr>0);                                    
			$aprtitle2018 = $isleave ? "Leave Taken dates of April 2018 -> $aprtitle18" : "";			
			$aprtitle2019 = $isleave ? "Leave Taken dates of April 2019 -> $aprtitle19" : "";			
			$aprtitle2020 = $isleave ? "Leave Taken dates of April 2020 -> $aprtitle20" : "";			
			$aprtitle2021 = $isleave ? "Leave Taken dates of April 2021 -> $aprtitle21" : "";			
			$aprtitle2022 = $isleave ? "Leave Taken dates of April 2022 -> $aprtitle22" : "";			
			$aprtitle2023 = $isleave ? "Leave Taken dates of April 2023 -> $aprtitle23" : "";			
			$aprtitle2024 = $isleave ? "Leave Taken dates of April 2024 -> $aprtitle24" : "";			
			$aprtitle2025 = $isleave ? "Leave Taken dates of April 2025 -> $aprtitle25" : "";			
			$aprtitle2026 = $isleave ? "Leave Taken dates of April 2026 -> $aprtitle26" : "";			
			$aprtitle2027 = $isleave ? "Leave Taken dates of April 2027 -> $aprtitle27" : "";			
			
			$isleave = ($may>0);
			$maytitle2018 = $isleave ? "Leave Taken dates of may 2018 -> $maytitle18" : "";			
			$maytitle2019 = $isleave ? "Leave Taken dates of may 2019 -> $maytitle19" : "";			
			$maytitle2020 = $isleave ? "Leave Taken dates of may 2020 -> $maytitle20" : "";			
			$maytitle2021 = $isleave ? "Leave Taken dates of may 2021 -> $maytitle21" : "";			
			$maytitle2022 = $isleave ? "Leave Taken dates of may 2022 -> $maytitle22" : "";			
			$maytitle2023 = $isleave ? "Leave Taken dates of may 2023 -> $maytitle23" : "";			
			$maytitle2024 = $isleave ? "Leave Taken dates of may 2024 -> $maytitle24" : "";			
			$maytitle2025 = $isleave ? "Leave Taken dates of may 2025 -> $maytitle25" : "";			
			$maytitle2026 = $isleave ? "Leave Taken dates of may 2026 -> $maytitle26" : "";			
			$maytitle2027 = $isleave ? "Leave Taken dates of may 2027 -> $maytitle27" : "";			
			
			$isleave = ($jun>0);
			$juntitle2018 = $isleave ? "Leave Taken dates of June 2018 -> $juntitle18" : "";			
			$juntitle2019 = $isleave ? "Leave Taken dates of June 2019 -> $juntitle19" : "";			
			$juntitle2020 = $isleave ? "Leave Taken dates of June 2020 -> $juntitle20" : "";			
			$juntitle2021 = $isleave ? "Leave Taken dates of June 2021 -> $juntitle21" : "";			
			$juntitle2022 = $isleave ? "Leave Taken dates of June 2022 -> $juntitle22" : "";			
			$juntitle2023 = $isleave ? "Leave Taken dates of June 2023 -> $juntitle23" : "";			
			$juntitle2024 = $isleave ? "Leave Taken dates of June 2024 -> $juntitle24" : "";			
			$juntitle2025 = $isleave ? "Leave Taken dates of June 2025 -> $juntitle25" : "";			
			$juntitle2026 = $isleave ? "Leave Taken dates of June 2026 -> $juntitle26" : "";			
			$juntitle2027 = $isleave ? "Leave Taken dates of June 2027 -> $juntitle27" : "";			
																  
			$isleave = ($jul>0);                                  
			$jultitle2018 = $isleave ? "Leave Taken dates of July 2018 -> $jultitle18" : "";			
			$jultitle2019 = $isleave ? "Leave Taken dates of July 2019 -> $jultitle19" : "";			
			$jultitle2020 = $isleave ? "Leave Taken dates of July 2020 -> $jultitle20" : "";			
			$jultitle2021 = $isleave ? "Leave Taken dates of July 2021 -> $jultitle21" : "";			
			$jultitle2022 = $isleave ? "Leave Taken dates of July 2022 -> $jultitle22" : "";			
			$jultitle2023 = $isleave ? "Leave Taken dates of July 2023 -> $jultitle23" : "";			
			$jultitle2024 = $isleave ? "Leave Taken dates of July 2024 -> $jultitle24" : "";			
			$jultitle2025 = $isleave ? "Leave Taken dates of July 2025 -> $jultitle25" : "";			
			$jultitle2026 = $isleave ? "Leave Taken dates of July 2026 -> $jultitle26" : "";			
			$jultitle2027 = $isleave ? "Leave Taken dates of July 2027 -> $jultitle27" : "";			
			
			$isleave = ($aug>0);
			$augtitle2018 = $isleave ? "Leave Taken dates of August 2018 -> $augtitle18" : "";			
			$augtitle2019 = $isleave ? "Leave Taken dates of August 2019 -> $augtitle19" : "";			
			$augtitle2020 = $isleave ? "Leave Taken dates of August 2020 -> $augtitle20" : "";			
			$augtitle2021 = $isleave ? "Leave Taken dates of August 2021 -> $augtitle21" : "";			
			$augtitle2022 = $isleave ? "Leave Taken dates of August 2022 -> $augtitle22" : "";			
			$augtitle2023 = $isleave ? "Leave Taken dates of August 2023 -> $augtitle23" : "";			
			$augtitle2024 = $isleave ? "Leave Taken dates of August 2024 -> $augtitle24" : "";			
			$augtitle2025 = $isleave ? "Leave Taken dates of August 2025 -> $augtitle25" : "";			
			$augtitle2026 = $isleave ? "Leave Taken dates of August 2026 -> $augtitle26" : "";			
			$augtitle2027 = $isleave ? "Leave Taken dates of August 2027 -> $augtitle27" : "";			
			
			$isleave = ($sep>0);
			$septitle2018 = $isleave ? "Leave Taken dates of September 2018 -> $septitle18" : "";			
			$septitle2019 = $isleave ? "Leave Taken dates of September 2019 -> $septitle19" : "";			
			$septitle2020 = $isleave ? "Leave Taken dates of September 2020 -> $septitle20" : "";			
			$septitle2021 = $isleave ? "Leave Taken dates of September 2021 -> $septitle21" : "";			
			$septitle2022 = $isleave ? "Leave Taken dates of September 2022 -> $septitle22" : "";			
			$septitle2023 = $isleave ? "Leave Taken dates of September 2023 -> $septitle23" : "";			
			$septitle2024 = $isleave ? "Leave Taken dates of September 2024 -> $septitle24" : "";			
			$septitle2025 = $isleave ? "Leave Taken dates of September 2025 -> $septitle25" : "";			
			$septitle2026 = $isleave ? "Leave Taken dates of September 2026 -> $septitle26" : "";			
			$septitle2027 = $isleave ? "Leave Taken dates of September 2027 -> $septitle27" : "";			
			
			$isleave = ($oct>0);
			$octtitle2018 = $isleave ? "Leave Taken dates of October 2018 -> $octtitle18" : "";			
			$octtitle2019 = $isleave ? "Leave Taken dates of October 2019 -> $octtitle19" : "";			
			$octtitle2020 = $isleave ? "Leave Taken dates of October 2020 -> $octtitle20" : "";			
			$octtitle2021 = $isleave ? "Leave Taken dates of October 2021 -> $octtitle21" : "";			
			$octtitle2022 = $isleave ? "Leave Taken dates of October 2022 -> $octtitle22" : "";			
			$octtitle2023 = $isleave ? "Leave Taken dates of October 2023 -> $octtitle23" : "";			
			$octtitle2024 = $isleave ? "Leave Taken dates of October 2024 -> $octtitle24" : "";			
			$octtitle2025 = $isleave ? "Leave Taken dates of October 2025 -> $octtitle25" : "";			
			$octtitle2026 = $isleave ? "Leave Taken dates of October 2026 -> $octtitle26" : "";			
			$octtitle2027 = $isleave ? "Leave Taken dates of October 2027 -> $octtitle27" : "";			
			
			$isleave = ($nov>0);
			$novtitle2018 = $isleave ? "Leave Taken dates of November 2018 -> $novtitle18" : "";			
			$novtitle2019 = $isleave ? "Leave Taken dates of November 2019 -> $novtitle19" : "";			
			$novtitle2020 = $isleave ? "Leave Taken dates of November 2020 -> $novtitle20" : "";			
			$novtitle2021 = $isleave ? "Leave Taken dates of November 2021 -> $novtitle21" : "";			
			$novtitle2022 = $isleave ? "Leave Taken dates of November 2022 -> $novtitle22" : "";			
			$novtitle2023 = $isleave ? "Leave Taken dates of November 2023 -> $novtitle23" : "";			
			$novtitle2024 = $isleave ? "Leave Taken dates of November 2024 -> $novtitle24" : "";			
			$novtitle2025 = $isleave ? "Leave Taken dates of November 2025 -> $novtitle25" : "";			
			$novtitle2026 = $isleave ? "Leave Taken dates of November 2026 -> $novtitle26" : "";			
			$novtitle2027 = $isleave ? "Leave Taken dates of November 2027 -> $novtitle27" : "";			
			
			$isleave = ($dec>0);
			$dectitle2018 = $isleave ? "Leave Taken dates of December 2018 -> $dectitle18" : "";			
			$dectitle2019 = $isleave ? "Leave Taken dates of December 2019 -> $dectitle19" : "";			
			$dectitle2020 = $isleave ? "Leave Taken dates of December 2020 -> $dectitle20" : "";			
			$dectitle2021 = $isleave ? "Leave Taken dates of December 2021 -> $dectitle21" : "";			
			$dectitle2022 = $isleave ? "Leave Taken dates of December 2022 -> $dectitle22" : "";			
			$dectitle2023 = $isleave ? "Leave Taken dates of December 2023 -> $dectitle23" : "";			
			$dectitle2024 = $isleave ? "Leave Taken dates of December 2024 -> $dectitle24" : "";			
			$dectitle2025 = $isleave ? "Leave Taken dates of December 2025 -> $dectitle25" : "";			
			$dectitle2026 = $isleave ? "Leave Taken dates of December 2026 -> $dectitle26" : "";			
			$dectitle2027 = $isleave ? "Leave Taken dates of December 2027 -> $dectitle27" : "";			


			
			$jantitles = [
							'2018' => $jantitle2018,
							'2019' => $jantitle2019,
							'2020' => $jantitle2020,
							'2021' => $jantitle2021,
							'2022' => $jantitle2022,
							'2023' => $jantitle2023,
							'2024' => $jantitle2024,
							'2025' => $jantitle2025,
							'2026' => $jantitle2026,
							'2027' => $jantitle2027
						];
			$jantitle = isset($jantitles[$year]) ? $jantitles[$year] : '';
			
			$febtitles = [
							'2018' => $febtitle2018,
							'2019' => $febtitle2019,
							'2020' => $febtitle2020,
							'2021' => $febtitle2021,
							'2022' => $febtitle2022,
							'2023' => $febtitle2023,
							'2024' => $febtitle2024,
							'2025' => $febtitle2025,
							'2026' => $febtitle2026,
							'2027' => $febtitle2027
						];
			$febtitle = isset($febtitles[$year]) ? $febtitles[$year] : '';			
			
			$martitles = [
							'2018' => $martitle2018,
							'2019' => $martitle2019,
							'2020' => $martitle2020,
							'2021' => $martitle2021,
							'2022' => $martitle2022,
							'2023' => $martitle2023,
							'2024' => $martitle2024,
							'2025' => $martitle2025,
							'2026' => $martitle2026,
							'2027' => $martitle2027
						];
			$martitle = isset($martitles[$year]) ? $martitles[$year] : '';
			
			$aprtitles = [
							'2018' => $aprtitle2018,
							'2019' => $aprtitle2019,
							'2020' => $aprtitle2020,
							'2021' => $aprtitle2021,
							'2022' => $aprtitle2022,
							'2023' => $aprtitle2023,
							'2024' => $aprtitle2024,
							'2025' => $aprtitle2025,
							'2026' => $aprtitle2026,
							'2027' => $aprtitle2027
						];
			$aprtitle = isset($aprtitles[$year]) ? $aprtitles[$year] : '';			
			
			$maytitles = [
							'2018' => $maytitle2018,
							'2019' => $maytitle2019,
							'2020' => $maytitle2020,
							'2021' => $maytitle2021,
							'2022' => $maytitle2022,
							'2023' => $maytitle2023,
							'2024' => $maytitle2024,
							'2025' => $maytitle2025,
							'2026' => $maytitle2026,
							'2027' => $maytitle2027
						];
			$maytitle = isset($maytitles[$year]) ? $maytitles[$year] : '';			
			
			$juntitles = [
							'2018' => $juntitle2018,
							'2019' => $juntitle2019,
							'2020' => $juntitle2020,
							'2021' => $juntitle2021,
							'2022' => $juntitle2022,
							'2023' => $juntitle2023,
							'2024' => $juntitle2024,
							'2025' => $juntitle2025,
							'2026' => $juntitle2026,
							'2027' => $juntitle2027
						];
			$juntitle = isset($juntitles[$year]) ? $juntitles[$year] : '';			
			
			$jultitles = [
							'2018' => $jultitle2018,
							'2019' => $jultitle2019,
							'2020' => $jultitle2020,
							'2021' => $jultitle2021,
							'2022' => $jultitle2022,
							'2023' => $jultitle2023,
							'2024' => $jultitle2024,
							'2025' => $jultitle2025,
							'2026' => $jultitle2026,
							'2027' => $jultitle2027
						];
			$jultitle = isset($jultitles[$year]) ? $jultitles[$year] : '';			
			
			$augtitles = [
							'2018' => $augtitle2018,
							'2019' => $augtitle2019,
							'2020' => $augtitle2020,
							'2021' => $augtitle2021,
							'2022' => $augtitle2022,
							'2023' => $augtitle2023,
							'2024' => $augtitle2024,
							'2025' => $augtitle2025,
							'2026' => $augtitle2026,
							'2027' => $augtitle2027
						];
			$augtitle = isset($augtitles[$year]) ? $augtitles[$year] : '';			
			
			$septitles = [
							'2018' => $septitle2018,
							'2019' => $septitle2019,
							'2020' => $septitle2020,
							'2021' => $septitle2021,
							'2022' => $septitle2022,
							'2023' => $septitle2023,
							'2024' => $septitle2024,
							'2025' => $septitle2025,
							'2026' => $septitle2026,
							'2027' => $septitle2027
						];
			$septitle = isset($septitles[$year]) ? $septitles[$year] : '';			
			
			$octtitles = [
							'2018' => $octtitle2018,
							'2019' => $octtitle2019,
							'2020' => $octtitle2020,
							'2021' => $octtitle2021,
							'2022' => $octtitle2022,
							'2023' => $octtitle2023,
							'2024' => $octtitle2024,
							'2025' => $octtitle2025,
							'2026' => $octtitle2026,
							'2027' => $octtitle2027
						];
			$octtitle = isset($octtitles[$year]) ? $octtitles[$year] : '';			
			
			$novtitles = [
							'2018' => $novtitle2018,
							'2019' => $novtitle2019,
							'2020' => $novtitle2020,
							'2021' => $novtitle2021,
							'2022' => $novtitle2022,
							'2023' => $novtitle2023,
							'2024' => $novtitle2024,
							'2025' => $novtitle2025,
							'2026' => $novtitle2026,
							'2027' => $novtitle2027
						];
			$novtitle = isset($novtitles[$year]) ? $novtitles[$year] : '';			
			
			$dectitles = [
							'2018' => $dectitle2018,
							'2019' => $dectitle2019,
							'2020' => $dectitle2020,
							'2021' => $dectitle2021,
							'2022' => $dectitle2022,
							'2023' => $dectitle2023,
							'2024' => $dectitle2024,
							'2025' => $dectitle2025,
							'2026' => $dectitle2026,
							'2027' => $dectitle2027
						 ];	
			$dectitle = isset($dectitles[$year]) ? $dectitles[$year] : '';	


			if($jan==0 )
			{	
				$jantitle = "No leaves taken on January";
			}	

			if($feb==0 )
			{
				$febtitle = "No leaves taken on February";
			}	

			if($mar==0 )
			{
				$martitle = "No leaves taken on March";
			}	

			if($apr==0 )
			{	
				$aprtitle = "No leaves taken on April";
			}	

			if($may==0 )
			{	
				$maytitle = "No leaves taken on May";
			}	

			if($jun==0 )
			{
				$juntitle = "No leaves taken on June";
			}	

			if($jul==0 )
			{	
				$jultitle = "No leaves taken on July";
			}	

			if($aug==0 )
			{	
				$augtitle = "No leaves taken on August";
			}	

			if($sep==0 )
			{	
				$septitle = "No leaves taken on September";

			}	

			if($oct==0 )
			{	
				$octtitle = "No leaves taken on October";	
			}	

			if($nov==0 )
			{	
				$novtitle = "No leaves taken on November";	
			}				

			if($dec==0 )
			{	
				$dectitle = "No leaves taken on December";	
			}	


			$isleavedate = ($jan>=0);
			$onclick1 = $isleavedate ? "onclick=\"displayJanLeaveDate('$jantitle','$jan')\"" : "";
			
			$isleavedate = ($feb>=0);
			$onclick2 = $isleavedate ? "onclick=\"displayFebLeaveDate('$febtitle','$feb')\"" : "";

			$isleavedate = ($mar>=0);
			$onclick3 = $isleavedate ? "onclick=\"displayMarLeaveDate('$martitle','$mar')\"" : "";
			
			$isleavedate = ($apr>=0);
			$onclick4 = $isleavedate ? "onclick=\"displayAprLeaveDate('$aprtitle','$apr')\"" : "";
			
			$isleavedate = ($may>=0);
			$onclick5 = $isleavedate ? "onclick=\"displayMayLeaveDate('$maytitle','$may')\"" : "";
			
			$isleavedate = ($jun>=0);
			$onclick6 = $isleavedate ? "onclick=\"displayJunLeaveDate('$juntitle','$jun')\"" : "";
			
			$isleavedate = ($jul>=0);
			$onclick7 = $isleavedate ? "onclick=\"displayJulLeaveDate('$jultitle','$jul')\"" : "";
			
			$isleavedate = ($aug>=0);
			$onclick8 = $isleavedate ? "onclick=\"displayAugLeaveDate('$augtitle','$aug')\"" : "";
			
			$isleavedate = ($sep>=0);
			$onclick9 = $isleavedate ? "onclick=\"displaySepLeaveDate('$septitle','$sep')\"" : "";
			
			$isleavedate = ($oct>=0);
			$onclick10 = $isleavedate ? "onclick=\"displayOctLeaveDate('$octtitle','$oct')\"" : "";
			
			$isleavedate = ($nov>=0);
			$onclick11 = $isleavedate ? "onclick=\"displayNovLeaveDate('$novtitle','$nov')\"" : "";
			
			$isleavedate = ($dec>=0);
			$onclick12 = $isleavedate ? "onclick=\"displayDecLeaveDate('$dectitle','$dec')\"" : "";	 	
?>

			<script>
			function displayJanLeaveDate(jantitle, month) 
			{
				const displayDiv = document.getElementById('displayDiv');
				displayDiv.textContent = jantitle; 
				displayDiv.style.display = 'block'; 
				displayDiv.style.opacity = '1'; 
				
				setTimeout(() => 
				{
					displayDiv.style.opacity = '0';
					setTimeout(() => 
					{
						displayDiv.style.display = 'none';
					}, 1000);
				}, 12000);
			}
			function displayFebLeaveDate(febtitle, month) 
			{
				const displayDiv = document.getElementById('displayDiv');
				displayDiv.textContent = febtitle; 
				displayDiv.style.display = 'block'; 
				displayDiv.style.opacity = '1'; 
				
				setTimeout(() => 
				{
					displayDiv.style.opacity = '0';
					setTimeout(() => 
					{
						displayDiv.style.display = 'none';
					}, 1000);
				}, 12000);
			}
			function displayMarLeaveDate(martitle,month) 
			{
				const displayDiv = document.getElementById('displayDiv');
				displayDiv.textContent = martitle; 
				displayDiv.style.display = 'block'; 
				displayDiv.style.opacity = '1'; 
				
				setTimeout(() => 
				{
					displayDiv.style.opacity = '0';
					setTimeout(() => 
					{
						displayDiv.style.display = 'none';
					}, 1000);
				}, 12000);
			}
			function displayAprLeaveDate(aprtitle,month) 
			{
				const displayDiv = document.getElementById('displayDiv');
				displayDiv.textContent = aprtitle; 
				displayDiv.style.display = 'block'; 
				displayDiv.style.opacity = '1'; 
				
				setTimeout(() => 
				{
					displayDiv.style.opacity = '0';
					setTimeout(() => 
					{
						displayDiv.style.display = 'none';
					}, 1000);
				}, 12000); 
			}
			function displayMayLeaveDate(maytitle,month) 
			{
				const displayDiv = document.getElementById('displayDiv');
				displayDiv.textContent = maytitle; 
				displayDiv.style.display = 'block'; 
				displayDiv.style.opacity = '1'; 
				
				setTimeout(() => 
				{
					displayDiv.style.opacity = '0';
					setTimeout(() => 
					{
						displayDiv.style.display = 'none';
					}, 1000);
				}, 12000);
			}
			function displayJunLeaveDate(juntitle,month) 
			{
				const displayDiv = document.getElementById('displayDiv');
				displayDiv.textContent = juntitle; 
				displayDiv.style.display = 'block'; 
				displayDiv.style.opacity = '1'; 
				
				setTimeout(() => 
				{
					displayDiv.style.opacity = '0';
					setTimeout(() => 
					{
						displayDiv.style.display = 'none';
					}, 1000);
				}, 12000);
			}	
			function displayJulLeaveDate(jultitle,month) 
			{
				const displayDiv = document.getElementById('displayDiv');
				displayDiv.textContent = jultitle; 
				displayDiv.style.display = 'block'; 
				displayDiv.style.opacity = '1'; 
				
				setTimeout(() => 
				{
					displayDiv.style.opacity = '0';
					setTimeout(() => 
					{
						displayDiv.style.display = 'none';
					}, 1000);
				}, 12000);
			}
			function displayAugLeaveDate(augtitle,month) 
			{
				const displayDiv = document.getElementById('displayDiv');
				displayDiv.textContent = augtitle; 
				displayDiv.style.display = 'block'; 
				displayDiv.style.opacity = '1'; 
				
				setTimeout(() => 
				{
					displayDiv.style.opacity = '0';
					setTimeout(() => 
					{
						displayDiv.style.display = 'none';
					}, 1000);
				}, 12000);
			}
			function displaySepLeaveDate(septitle,month) 
			{
				const displayDiv = document.getElementById('displayDiv');
				displayDiv.textContent = septitle; 
				displayDiv.style.display = 'block'; 
				displayDiv.style.opacity = '1'; 
				
				setTimeout(() => 
				{
					displayDiv.style.opacity = '0';
					setTimeout(() => 
					{
						displayDiv.style.display = 'none';
					}, 1000);
				}, 12000);
			}
			function displayOctLeaveDate(octtitle,month) 
			{
				const displayDiv = document.getElementById('displayDiv');
				displayDiv.textContent = octtitle; 
				displayDiv.style.display = 'block'; 
				displayDiv.style.opacity = '1'; 
				
				setTimeout(() => 
				{
					displayDiv.style.opacity = '0';
					setTimeout(() => 
					{
						displayDiv.style.display = 'none';
					}, 1000);
				}, 12000);
			}
			function displayNovLeaveDate(novtitle,month) 
			{
				const displayDiv = document.getElementById('displayDiv');
				displayDiv.textContent = novtitle; 
				displayDiv.style.display = 'block'; 
				displayDiv.style.opacity = '1'; 
				
				setTimeout(() => 
				{
					displayDiv.style.opacity = '0';
					setTimeout(() => 
					{
						displayDiv.style.display = 'none';
					}, 1000);
				}, 12000);
			}
			function displayDecLeaveDate(dectitle,month) 
			{
				const displayDiv = document.getElementById('displayDiv');
				displayDiv.textContent = dectitle; 
				displayDiv.style.display = 'block'; 
				displayDiv.style.opacity = '1'; 
				
				setTimeout(() => 
				{
					displayDiv.style.opacity = '0';
					setTimeout(() => 
					{
						displayDiv.style.display = 'none';
					}, 1000);
				}, 12000);
			} 
			</script>

<?php			
		
		
			
			$trdata1 .= "
			<tr style='padding:3px;'>
				<td style='background-color:#E6EAF0;padding:3px;'>$year</td>
				<td title='$jantitle' style=' $col1 padding:3px;' $onclick1>$jan</td>
				<td title='$febtitle' style=' $col2 padding:3px;' $onclick2>$feb</td>
				<td title='$martitle' style=' $col3 padding:3px;' $onclick3>$mar</td>
				<td title='$aprtitle' style=' $col4 padding:3px;' $onclick4>$apr</td>
				<td title='$maytitle' style=' $col5 padding:3px;' $onclick5>$may</td>
				<td title='$juntitle' style=' $col6 padding:3px;' $onclick6>$jun</td>
				<td title='$jultitle' style=' $col7 padding:3px;' $onclick7>$jul</td>
				<td title='$augtitle' style=' $col8 padding:3px;' $onclick8>$aug</td>
				<td title='$septitle' style=' $col9 padding:3px;' $onclick9>$sep</td>
				<td title='$octtitle' style=' $col10 padding:3px;' $onclick10>$oct</td>
				<td title='$novtitle' style=' $col11 padding:3px;' $onclick11>$nov</td>
				<td title='$dectitle' style=' $col12 padding:3px;' $onclick12>$dec</td>
				<td style='$color1;background-color:#E6EAF0'>$sumleave</td>
			</tr>";
			
			$s=$s+$sumleave;
		}


		$totalLeaveBalance1 = getLeavesTakenPerMonth($empid);

		$totalLeaveBalance1 = $totalLeaveBalance1 + 1;

		$plus='+';

		$color='color:green;';


	if($dojtype=='N')
	{	
	    $totalLeaveBalance1=$totalLeaveBalance1-1;	
	}

	
	if($totalLeaveBalance1==0)
	{
		$color='color:#ed9911;';
		$plus='';
		$plus1='';
	}	
	
	if($totalLeaveBalance1<0)
	{
		$color='color:#f75f54;';
		$plus='';
		$plus1='';
	}



	$sql7 = "select count(*) as cnt from leave where empid='$empid' AND leavetype!='COMPO'";
	$res7= sqlsrv_query($conn,$sql7);
	$row7 = sqlsrv_fetch_array($res7,SQLSRV_FETCH_ASSOC);
	$count = $row7['cnt'];
 	if($empid)
	{
		if($count==0)
		{
			if($dojtype=='N')
			{	
				$totalLeaveBalance1 = $monthsDifference;
				$totalLeaveBalance1=$totalLeaveBalance1-1;
				$color='color:green;';
				$plus='+';
			}
			else
			{
				$totalLeaveBalance1 = $monthsDifference;
				$totalLeaveBalance1=$totalLeaveBalance1+1;
				$color='color:green;';
				$plus='+';	
			}	
		}	
	} 




	if($cutoff>0)
	{
		$color='color:green;';
		$plus='+';
		$totalLeaveBalance1 = ($totalLeaveBalance1 + $cutoff);

		if($totalLeaveBalance1==0)
		{
			$color='color:#ed9911;';
			$plus='';
			$plus1='';
		}	
		
		if($totalLeaveBalance1<0)
		{
			$color='color:#f75f54;';
			$plus='';
			$plus1='';
		}		
	}

}
			
			

			 if(!$leavedate && $monthsDifference<1)
		    {
				
				$totalLeaveBalance2 = getLeavesTakenPerMonth($empid);
				if($dojtype=='Y')
				{
					$totalLeaveBalance1 = $totalLeaveBalance2 + 1;
				}
				else
				{
					$totalLeaveBalance1 = $totalLeaveBalance2;
					$totalLeaveBalance1 = 0;
				}
				
		    } 



$currentDate = new DateTime();
?>

<br>
<button id="detailedReportButton" title="click to view the Detailed Leave Report">Detailed Report</button>
<style>
#content1 
{
    display:none;
}
#content2 
{
    display:none;
}

        #displayDiv 
		{
            position: fixed;
            top: 362px;
            left: 50%;
            transform: translateX(-50%);
			//color:white;
            background-color: #E6AA2E; /*#454E52*/
			border-radius:8px;
		    font-weight: bold;
            padding: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: none;
            opacity: 1;
            transition: opacity 1s ease-out;		
        }

        #detailedReportButton 
		{
            padding: 12px 24px;
            font-size: 16px;
            font-weight: bold;
            color: #ffffff;
            background-color: #28373E;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        #detailedReportButton:hover 
		{
            background-color: #4c5559;
            box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.3);
            transform: translateY(-2px);
        }

        #detailedReportButton:active 
		{
            background-color: #4c5559;
            transform: translateY(1px);
            box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.2);
        }
		
</style>

<div id='content1'>

<?php

$sql11 = "SELECT doj FROM emp WITH (NOLOCK) WHERE empid='$empid'";
$res11 = sqlsrv_query($conn, $sql11);
while ($row11 = sqlsrv_fetch_array($res11, SQLSRV_FETCH_ASSOC)) 
{

        if ($row11['doj'] == NULL) 
		{
            $row11['doj'] = '';
        }
        $doj = trim($row11['doj']);

}
$date1 = DateTime::createFromFormat('Y-m-d', $doj);

if ($date1) 
{
    $formattedDoj = $date1->format('d-m-Y');
} 
else 
{
    $formattedDoj = 'Invalid date';
}	


$col = 'color:green;';

if($cutoff>0)
{			

	if($compoleave > 0)
	{
		$col = 'color:green;';
	}	
	
	echo "
	<div class='leave-summary'>
		<h1><b style='font-size:25px;'>Leave Summary Report</b></h1>
		<p><b>Name - $name</b></p>
		<p><b>Date of Joining - $formattedDoj</b></p><br>
		<center>
		<div id='displayDiv'></div>
		<table id='leavesummary' style='width:300px;height:50px;padding:5px;'>
			<thead>
				<tr style='background-color:#28373E;color:white;padding:5px;'>
					<th style='background-color:#28373E;color:white;padding:5px;'>Year</th>
					<th style='background-color:#28373E;color:white;padding:5px;'>Jan</th>
					<th style='background-color:#28373E;color:white;padding:5px;'>Feb</th>
					<th style='background-color:#28373E;color:white;padding:5px;'>Mar</th>
					<th style='background-color:#28373E;color:white;padding:5px;'>Apr</th>
					<th style='background-color:#28373E;color:white;padding:5px;'>May</th>
					<th style='background-color:#28373E;color:white;padding:5px;'>Jun</th>
					<th style='background-color:#28373E;color:white;padding:5px;'>Jul</th>
					<th style='background-color:#28373E;color:white;padding:5px;'>Aug</th>
					<th style='background-color:#28373E;color:white;padding:5px;'>Sep</th>
					<th style='background-color:#28373E;color:white;padding:5px;'>Oct</th>
					<th style='background-color:#28373E;color:white;padding:5px;'>Nov</th>
					<th style='background-color:#28373E;color:white;padding:5px;'>Dec</th>
					<th style='background-color:#28373E;color:white;padding:5px;'>Leaves/Year</th>
				</tr>
			</thead>
			<tbody>
				$trdata1
			</tbody>
			<tr>
			<td colspan='13' style='background-color:#454e52;color:white;'><b>TOTAL LEAVES</b></td>
			<td style='background-color:#E6EAF0;'><b>$s</b></td>
			</tr>
		</table>
		
		<br>
		<br><b style='font-size:20px;font-family:Poppins;'>Casual Leaves taken : <b style='color:black;font-size:20px;'>$casual</b></b>	
		<br><b style='font-size:20px;font-family:Poppins;'> Compo Leaves taken : <b style='color:black;font-size:20px;'>$compo</b></b>	
		
		<br><b style='font-size:20px;font-family:Poppins;'>Salary Cut Leave Count : <b style='color:black;font-size:20px;'>$cutoff</b></b><br>	
		
		<b style='font-size:28px;font-family:Poppins;'>Current Leave Balance : </b><b class='dis' style='$color font-size:30px;font-family:Poppins;'>$plus$totalLeaveBalance1</b>
		<br>
		<b style='font-size:28px;font-family:Poppins;'>Compo Leave Balance : </b><b class='dis' style='$col font-size:30px;font-family:Poppins;'>$compoleave</b>		
		<br>
		</center>
	</div>

	";

}

else
{

	if($compoleave > 0)
	{
		$col = 'color:green;';
	}	
	
	echo "
	<div class='leave-summary'>
		<h1><b style='font-size:25px;'>Leave Summary Report</b></h1>
		<p><b>Name - $name</b></p>
		<p><b>Date of Joining - $formattedDoj</b></p><br>
		<center>
		<div id='displayDiv'></div>
		<table id='leavesummary' style='width:300px;height:50px;padding:5px;'>
			<thead>
				<tr style='background-color:#28373E;color:white;padding:5px;'>
					<th style='background-color:#28373E;color:white;padding:5px;'>Year</th>
					<th style='background-color:#28373E;color:white;padding:5px;'>Jan</th>
					<th style='background-color:#28373E;color:white;padding:5px;'>Feb</th>
					<th style='background-color:#28373E;color:white;padding:5px;'>Mar</th>
					<th style='background-color:#28373E;color:white;padding:5px;'>Apr</th>
					<th style='background-color:#28373E;color:white;padding:5px;'>May</th>
					<th style='background-color:#28373E;color:white;padding:5px;'>Jun</th>
					<th style='background-color:#28373E;color:white;padding:5px;'>Jul</th>
					<th style='background-color:#28373E;color:white;padding:5px;'>Aug</th>
					<th style='background-color:#28373E;color:white;padding:5px;'>Sep</th>
					<th style='background-color:#28373E;color:white;padding:5px;'>Oct</th>
					<th style='background-color:#28373E;color:white;padding:5px;'>Nov</th>
					<th style='background-color:#28373E;color:white;padding:5px;'>Dec</th>
					<th style='background-color:#28373E;color:white;padding:5px;'>Leaves/Year</th>
				</tr>
			</thead>
			<tbody>
				$trdata1
			</tbody>
			<tr>
			<td colspan='13' style='background-color:#454e52;color:white;'><b>TOTAL LEAVES</b></td>
			<td style='background-color:#E6EAF0;'><b>$s</b></td>
			</tr>
		</table>
		
		<br>
		<br><b style='font-size:20px;font-family:Poppins;'>Casual Leaves taken : <b style='color:black;font-size:20px;'>$casual</b></b>	
		<br><b style='font-size:20px;font-family:Poppins;'> Compo Leaves taken : <b style='color:black;font-size:20px;'>$compo</b></b><br>	
		
		<b style='font-size:28px;font-family:Poppins;'>Current Leave Balance : </b><b class='dis' style='$color font-size:30px;font-family:Poppins;'>$plus$totalLeaveBalance1</b>
		<br>
		<b style='font-size:28px;font-family:Poppins;'>Compo Leave Balance : </b><b class='dis' style='$col font-size:30px;font-family:Poppins;'>$compoleave</b>		
		<br><br>
		</center>
	</div>

	";
	
}	

?>

</div>

<div id='content2'>
<img src="../leave/images/print.png" alt="Download image" id='download1' style="width:25px;height:25px;" class="download1" title="click to download the pdf">
</div>

<script>


document.getElementById('detailedReportButton').addEventListener('click', function() 
{
    var content1 = document.getElementById('content1');
    var content2 = document.getElementById('content2');

    if (content1.style.display === 'none' || content1.style.display === '') 
	{
        content1.style.display = 'block'; 
        content2.style.display = 'block'; 
    } 
	else 
	{
        content1.style.display = 'none'; 
        content2.style.display = 'none'; 
    }

    if (content1.style.display === 'block') 
	{
        content2.scrollIntoView({ behavior: 'smooth' });
    }
	
});

	
</script>

<center>

<br><br><br>
</center>

<?php

}


else //LEAVE NOT TAKEN EMPLOYEES
{	

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
		
		
	?>
	<center>
	<br><br><br><br><br>
	<div style='background-color:#FFFFFF;border-radius:10px;width:35%;margin-right:140;height:180px;box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);'>
	<b><br><br><br>
	<?php
	echo 'YOU HAVE NOT TAKEN ANY LEAVES YET';
	?>
	</div>
	</center></b>
	<?php
	
}


?>


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

				var opt = 
				{
					margin: 0.5,
					filename:`${name} Leave Taken Dates.pdf`,
					image: { type: 'jpeg', quality: 0.99 },
					html2canvas: { scale: 2},
					jsPDF: {unit: 'in', format: 'a4', orientation: 'portrait'}
					 
				
				};
				html2pdf().from(content).set(opt).toPdf().save().then(function() 
				{
					tableContainer.style.maxHeight = originalMaxHeight;
					tableContainer.style.overflow = originalOverflow;
				})
            });


		    var name = "<?php echo $name; ?>";
		    document.getElementById('download1').addEventListener('click', function () 
			{
					
				var content = document.getElementById('content1');
				var tableContainer = document.getElementById('table-container');

				var originalMaxHeight = tableContainer.style.maxHeight;
				var originalOverflow = tableContainer.style.overflow;
		  
				tableContainer.style.maxHeight = 'none';
				tableContainer.style.overflow = 'visible';

				var opt = 
				{
					margin: 0.5,
					filename:`${name} Leave Summary Report.pdf`,
					image: { type: 'jpeg', quality: 0.99 },
					html2canvas: { scale: 4 }, 
					jsPDF: { unit: 'in', format: [40, 10] }
					 
				
				};
				html2pdf().from(content).set(opt).toPdf().save().then(function() 
				{
					tableContainer.style.maxHeight = originalMaxHeight;
					tableContainer.style.overflow = originalOverflow;
				})
            });
		</script>		



</body>
</html>
