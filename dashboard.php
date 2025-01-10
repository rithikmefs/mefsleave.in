<style>
tr.dis1:hover 
{
	background-color:#E8AB2E;
}
.sub1
{
	width:12%;
	border-radius:8px;
	background-color:#665763;
	text-align:center;
	font-family:poppins;
	font-size:16px;
	transition:background-color 0.3s;
	height:8%;
	color:white;
    margin: 0;
    cursor: pointer;
    transition: background-color 0.2s ease, transform 0.2s ease;	
}	
.sub1:hover 
{
	transform: scale(1.05);
}
</style>

<?php
session_start();
include "connect.php";

$empid='';
if(isset($_SESSION['empid']))
{
	$empid=$_SESSION['empid'];	
}

$flag='';
if(isset($_SESSION['flag']))
{
	$flag=$_SESSION['flag'];	
}

$username='';
if(isset($_POST['username']))
{
	$username=$_POST['username'];
}
$password='';
if(isset($_POST['password']))
{
	$password=$_POST['password'];	
}

$gender='';
if(isset($_POST['gender']))
{
	$gender=$_POST['gender'];
}

$emptype = ['ADMIN'];
// Display both values
foreach ($emptype as $etype) 
{
	if (isset($_POST['emptype'])) 
	{
		$etype = $_POST['emptype'];
	}
}


$name='';
if(isset($_POST['name']))
{
	$name=$_POST['name'];	
}

$doj='';
if(isset($_POST['doj']))
{
	$doj=$_POST['doj'];	
}

$dojtype='';
if(isset($_POST['dojtype']))
{
	$dojtype=$_POST['dojtype'];	
}

$designation='';
if(isset($_POST['designation']))
{
	$designation=$_POST['designation'];	
}

$rowid='';
if(isset($_POST['rowid']))
{
	$rowid=$_POST['rowid'];	
}

$leavedate='';
if(isset($_POST['leavedate']))
{
	$leavedate=$_POST['leavedate'];	
}

$leavetype='';
if(isset($_POST['leavetype']))
{
	$leavetype=$_POST['leavetype'];	
}

$power = $_SESSION['power'] ?? 'Y'; // Default to 'N' if not set

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="icon" href="../leave/images/MEFS.png">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">   
</head>

<?php


function getLeavesTakenPerMonth($empid) 
{
	GLOBAL $dojtype,$conn,$totalMonthsWorked;
	
	$sql6 = "select doj,name from emp with (nolock) where empid='$empid'";
	$res6 = sqlsrv_query($conn,$sql6);
	$row6 = sqlsrv_fetch_array( $res6, SQLSRV_FETCH_ASSOC);

	if($row6['name']==NULL)
	{
		$row6['name']='';
	}
	$name = trim($row6['name']);
	
	if($row6['doj']==NULL)
	{
		$row6['doj']='';
	}
	$doj = trim($row6['doj']);
	$doj = new DateTime($doj);
	$currentDate = new DateTime(); 
	$interval = $doj->diff($currentDate);
	$monthsDifference = ($interval->y * 12) + $interval->m;
	//$yearsWorked = $interval->y + ($interval->m > 0 ? 1 : 0);
    
	$yy=0;
	$mm=0;
	$time=0;
	
	if( $monthsDifference < 12 )
	{
		$mm = $monthsDifference;
	}		
	else
	{
		$mm = $monthsDifference % 12;
		$yy = $monthsDifference / 12;
		$time="TIME PERIOD - ".(int)$yy." YEARS AND".$mm." MONTHS";
	}	

	$totalMonthsWorked = ($interval->y * 12 )+ $interval->m;
	$totalLeaveBalance = 0;
	
	$sql = "SELECT YEAR(leavedate) AS year, MONTH(leavedate) AS month, count(empid) AS total_leave_count, count(leavetype)
	FROM leave WHERE empid = '$empid' AND leavetype = 'CASUAL'  GROUP BY YEAR(leavedate), MONTH(leavedate);";
    $stmt = sqlsrv_query($conn, $sql);

	
    $leavesTakenPerMonth = [];
    if ($stmt !== false) 
	{
        while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) 
		{
			$date = $row['year']."-".$row['month'];
			$leave=$row['total_leave_count'];
			if($row['total_leave_count']==null)
			{
				$row['total_leave_count']=0;

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
	foreach ($leavesTakenPerMonth as $month => $leavesTaken1) 
{	

    if ($leavesTaken1) 
	{
			$totalLeaveBalance -= $leavesTaken1;
			$totalLeaveBalance1 = $totalLeaveBalance ;			
	}
	
	
}

return (int)$totalLeaveBalance1;

}

$trdata = '';


if($empid=='1118' || $empid=='1119')
{
	$emptype='TEST';
}	


if(($empid=='999' && $power == 'Y') || ($empid=='1108' && $power == 'Y' ))//Admin Dashboard
{

	$power = 'N';
	
	date_default_timezone_set("Asia/Kolkata");

	if(date("h:i:sa") > '05:50:00' && date("h:i:sa") < '05:58:00')
	{
	?>	

	<body class="report" bottommargin="0" topmargin="0" leftmargin="0" rightmargin="0">	

	<center>
	<br>
	<img src="../leave/images/BugFixing.png" style="width:600px;height:600px;">
	<pre>
	System Maintenance from 05:00 to 06:00 IST
	Sorry for your inconvinience.
	</pre>
	</center>

	
	<script>
		function loadContent(url) 
		{
			document.getElementById('contentFrame').src = url;
		}

		const sidebar = document.getElementById('sidebar');
		const content = document.getElementById('content');

		sidebar.addEventListener('mouseover', () => 
		{
			content.style.marginLeft = '260px';
		});

		sidebar.addEventListener('mouseout', () => 
		{
			content.style.marginLeft = '85px';
		});

		// Add event listeners to sidebar links
		const sidebarLinks = document.querySelectorAll('.sidebar-links li a');
		sidebarLinks.forEach(link => 
		{
			
			link.addEventListener('click', function(e) 
			{
				e.preventDefault(); // Prevent default link behavior

				// Remove active class from all links
				sidebarLinks.forEach(link => link.classList.remove('active'));

				// Add active class to the clicked link
				this.classList.add('active');

				const href = this.getAttribute('href');
				loadContent(href); // Load content into iframe
			});
		});

		function logoutAndRedirect(event) 
		{
			event.preventDefault();
			window.top.location.href = 'logout.php';
		}
		
		
		function goBack() 
		{
		  history.back();
		}	
		
	</script>


	<?php
	exit;
	}
	else //Admin Dashboard
	{	
    global $leave;
	$sql5 = "select leavedate,empid from leave with (nolock) where empid='$empid'";
	$res5 = sqlsrv_query($conn,$sql5);
	while($row5 = sqlsrv_fetch_array( $res5, SQLSRV_FETCH_ASSOC))
	{
		
		global $mm,$time,$yy;
		if($row5['leavedate']==NULL)
		{
			$row5['leavedate']='';
		} 	
		$leavedate = trim($row5['leavedate']);
				
		if($row5['empid']==NULL)
		{
			$row5['empid']='';
		}
		$empid = trim($row5['empid']);

	}
	
	$sql2 = "select * from emp with (nolock) where flag='Y' and empid='$empid'";
	$res2 = sqlsrv_query($conn,$sql2);
	while($row2 = sqlsrv_fetch_array( $res2, SQLSRV_FETCH_ASSOC))
	{

		if($row2['name']==NULL)
		{
			$row2['name']='';
		} 
		$name = trim($row2['name']); 

		if($row2['dob']==NULL)
		{
			$row2['dob']='';
		}
		$dob = trim($row2['dob']); 

		if($row2['doj']==NULL)
		{
			$row2['doj']='';
		}
		$doj = trim($row2['doj']); 
		
		if($row2['cutoff']==NULL)
		{
			$row2['cutoff']='';
		}
		$cutoff = trim($row2['cutoff']);		

		if($row2['address']==NULL)
		{
			$row2['address']='';
		}
		$address = trim($row2['address']); 

		if($row2['gender']==NULL)
		{
			$row2['gender']='';
		}
		$gender = trim($row2['gender']); 

		if($row2['email']==NULL)
		{
			$row2['email']='';
		}
		$email = trim($row2['email']); 

		if($row2['mob']==NULL)
		{
			$row2['mob']='';
		}
		$mob = trim($row2['mob']); 

		if($row2['designation']==NULL)
		{
			$row2['designation']='';
		}
		$designation = trim($row2['designation']); 

		if($row2['dojtype']==NULL)
		{
			$row2['dojtype']='';
		}
		$dojtype = trim($row2['dojtype']); 

		if($row2['empid']==NULL)
		{
			$row2['empid']='';
		}
		$empid = trim($row2['empid']);
		if($row2['emptype']==NULL)
		{
			$row2['emptype']='';
		}
		$emptype  =  trim($row2['emptype']);


		$date1 = DateTime::createFromFormat('Y-m-d', $dob);
		$formattedDob = $date1->format('d-m-Y');

		$date2 = DateTime::createFromFormat('Y-m-d', $doj);
		$formattedDoj = $date2->format('d-m-Y');

	$doj = new DateTime($doj);
	$currentDate = new DateTime(); 
	$interval = $doj->diff($currentDate);
	$monthsDifference = ($interval->y * 12) + $interval->m;
	$yearsWorked = $interval->y + ($interval->m > 0 ? 1 : 0);
    
	$yy=0;
	$mm=0;
	$time=0;
	
	if( $monthsDifference < 12 )
	{
		$mm = $monthsDifference;
		$time = $mm." MONTHS";
	}		
	else
	{
		$mm = $monthsDifference % 12;
		$yy = $monthsDifference / 12;
		if((int)$yy == 1 && $mm == 0 )
		{
			$time = (int)$yy." YEAR";
		}
		elseif((int)$yy == 2 && $mm == 0  || (int)$yy == 3 && $mm == 0 || (int)$yy == 4 && $mm == 0 || (int)$yy == 5 && $mm == 0 || (int)$yy == 6 && $mm == 0 || (int)$yy == 7 && $mm == 0  || (int)$yy == 8 && $mm == 0  || (int)$yy == 9 && $mm == 0  || (int)$yy == 10 && $mm == 0)
		{
			$time = (int)$yy." YEARS";
		}
		elseif((int)$yy == 1 && $mm == 1)
		{
			$time = (int)$yy." YEAR AND ".$mm." MONTH";
		}
		elseif((int)$yy == 2 && $mm == 1 || (int)$yy == 3 && $mm == 1 || (int)$yy == 4 && $mm == 1 || (int)$yy == 5 && $mm == 1 || (int)$yy == 6 && $mm == 1 || (int)$yy == 7 && $mm == 1 || (int)$yy == 8 && $mm == 1 || (int)$yy == 9 && $mm == 1 || (int)$yy == 10 && $mm == 1)
		{
			$time = (int)$yy." YEARS AND ".$mm." MONTH";
		}		
		elseif((int)$yy == 1 )
		{
			$time = (int)$yy." YEAR AND ".$mm." MONTHS";
		}
		else
		{
			$time = (int)$yy." YEARS AND ".$mm." MONTHS";
		}	
	
	}


	$totalLeaveBalance1 = getLeavesTakenPerMonth($empid);
		
	$color='background-color:green;';
	$plus='+';
	$plus1='%2B';
	
	global $leave;
	
	$totalLeaveBalance1 = $totalLeaveBalance1 + 1;
	
	if($dojtype=='N')
	{	
	    $totalLeaveBalance1=$totalLeaveBalance1-1;	
	}
	
	if($totalLeaveBalance1==0)
	{
	$color='background-color:#ed9911;';
	$plus='';
	$plus1='';
	}	
	
	if($totalLeaveBalance1<0)
	{
	$color='background-color:#f75f54;';
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
				$color='background-color:green;';
				$plus='+';
			}
			else
			{
				$totalLeaveBalance1 = $monthsDifference;
				$totalLeaveBalance1=$totalLeaveBalance1+1;
				$color='background-color:green;';
				$plus='+';	
			}	
		}	
	} 	

	if($cutoff>0)
	{
		$color='background-color:green;';
		$totalLeaveBalance1 = ($totalLeaveBalance1 + $cutoff);

		if($totalLeaveBalance1==0)
		{
			$color='background-color:#ed9911;';
			$plus='';
			$plus1='';
		}	
		
		if($totalLeaveBalance1<0)
		{
			$color='background-color:#f75f54;';
			$plus='';
			$plus1='';
		}

		if($totalLeaveBalance1>0)
		{
			$color='background-color:green;';
			$plus='+';
			$plus1='+';
		}
	}

	$trholiday='';
	$trholiday1='';
	$trholiday2='';
	$titleholiday='';
	$holiday_day='';
	$holiday='';
	$i=1;
	$j=1;
	$k=1;
	$h=1;
	$aspirants = '';
	$aspirantscount = 0;
	$lastleavedate  = '';
	$formattedLastleavedate = '';
	$totalaspirants1 = '';
	$totalaspirants2 = '';
	$totalaspirants = '';
	$trholi='';
	$leaveemp=0;
	$totalleaveemp1='';
	$totalleaveemp2='';
	$totalleaveemp='';
	$leaveempname='';
	$compoleave='';
	$formattedcompodate='';
	$totalcompodate='';
	$totalcompodate1='';
	$totalcompodate2='';
	$reason='N/A';


		$sql8 = " select count(empid) as compoleave from compo where empid='$empid'";
		$res8 = sqlsrv_query($conn,$sql8);
		while($row8 = sqlsrv_fetch_array( $res8, SQLSRV_FETCH_ASSOC))
		{
			$compoleave = $row8['compoleave'];	
		}
		
		$sql9 = " select compodate from compo where empid='$empid' order by compodate";
		$res9 = sqlsrv_query($conn,$sql9);
		while($row9 = sqlsrv_fetch_array( $res9, SQLSRV_FETCH_ASSOC))
		{
			if($row9['compodate']==NULL)
			{
				$row9['compodate']='';
			}
			$compodate = trim($row9['compodate']);
			
			$date3 = DateTime::createFromFormat('Y-m-d', $compodate);
			$formattedcompodate = $date3->format('d-m-Y');
			
			if($h<$compoleave)
			{			
				$totalcompodate1 .=	$formattedcompodate.',&nbsp;';
				$h++;
			}
		}		

		$totalcompodate1 .= $formattedcompodate;

		$totalcompodate = $totalcompodate1 . $totalcompodate2;

		

		$sql = "  SELECT CAST(COUNT(holiday) AS VARCHAR(50)) AS result FROM holiday  
                  WHERE date >= GETDATE() AND date <= EOMONTH(GETDATE())";
		$res = sqlsrv_query($conn,$sql);
		while($row = sqlsrv_fetch_array( $res, SQLSRV_FETCH_ASSOC))
		{

			$result = $row['result'];	

		}
		
		$sql1 = "SELECT holiday, DAY(date) AS holiday_day FROM holiday
		         WHERE date >= CONVERT(DATE, GETDATE()) AND date <= EOMONTH(GETDATE())";
		$res1 = sqlsrv_query($conn,$sql1);
		while($row1 = sqlsrv_fetch_array( $res1, SQLSRV_FETCH_ASSOC))
		{

			$holiday_day = $row1['holiday_day'];
			
			if($row1['holiday']==NULL)
			{
				$row1['holiday']='';
			}
			$holiday = trim($row1['holiday']); 	
			
				if($i<$result)
				{
					$trholiday1 .= '&nbsp;<b>'.$holiday .'</b>&nbsp;on <b>'.$holiday_day.'th</b>,&nbsp;';
					$i++;
				}
				$titleholiday .= '&nbsp;'.$holiday .'&nbsp;on '.$holiday_day.'th,';			
		
		}
		
		

	$trholiday2 .= '&nbsp;<b>'.$holiday .'</b>&nbsp;on <b>'.$holiday_day.'th</b>';

	$trholi = $trholiday1 . $trholiday2;

	if(!$holiday)
	{
		$trholi='<b>NO PUBLIC HOLIDAYS THIS MONTH</b>';
	}	


		$sql3 = "SELECT COUNT(empid) AS leaveemp FROM leave WHERE CAST(leavedate AS DATE) = CAST(GETDATE() AS DATE)
		         AND empid NOT IN ('1118', '1119');";
		$res3 = sqlsrv_query($conn,$sql3);
		while($row3 = sqlsrv_fetch_array( $res3, SQLSRV_FETCH_ASSOC))
		{
			$leaveemp = $row3['leaveemp'];		
		}	


	$sql4 = "SELECT e.name AS leaveempname,reason FROM leave l JOIN emp e ON l.empid = e.empid 
				 WHERE CAST(l.leavedate AS DATE) = CAST(GETDATE() AS DATE) AND l.empid NOT IN ('1118', '1119');";
	$res4 = sqlsrv_query($conn,$sql4);
	while($row4 = sqlsrv_fetch_array( $res4, SQLSRV_FETCH_ASSOC))
	{
		$leaveempname = $row4['leaveempname'];
		$reason = $row4['reason'];
		if($j<$leaveemp)
		{	
			if($reason=='')
			{
				$reason='N/A';
			}
			
			$totalleaveemp1 .= '<b>'.$leaveempname.'</b>'.'['.$reason.']'.',&nbsp;&nbsp;';	
			$j++;
	    
		}	
	
	} 
	
	if($reason=='')
	{
		$reason='N/A';
	}	

	$totalleaveemp2 .= '<b>'.$leaveempname.'</b>'.'['.$reason.']';

	$totalleaveemp = $totalleaveemp1 . $totalleaveemp2;	

	if($leaveemp==0)
	{
		$totalleaveemp='<b>EVERYONE IS PRESENT TODAY</b>';
	}


$sql10 = "SELECT COUNT(DISTINCT e.empid) AS aspirantscount FROM emp e WHERE 
          e.flag = 'Y' AND e.emptype != 'TEST' AND e.empid != 1108 AND e.empid != 1070 AND e.empid NOT IN 
		  ( SELECT DISTINCT l.empid FROM leave l
          WHERE MONTH(l.leavedate) = MONTH(GETDATE()) AND YEAR(l.leavedate) = YEAR(GETDATE()))";	
$res10 = sqlsrv_query($conn,$sql10);
while($row10 = sqlsrv_fetch_array( $res10, SQLSRV_FETCH_ASSOC))
{
	
	$aspirantscount = $row10['aspirantscount'];	
	
}


$sql11 = "SELECT e.name AS aspirants, MAX(l.leavedate) AS lastleavedate FROM emp e 
          LEFT JOIN leave l ON e.empid = l.empid AND l.leavedate < GETDATE()
          WHERE e.flag = 'Y'  AND e.emptype != 'TEST' AND e.empid NOT IN (1108, 1070) AND e.empid NOT IN (
          SELECT DISTINCT l.empid FROM leave l 
          WHERE MONTH(l.leavedate) = MONTH(GETDATE()) AND YEAR(l.leavedate) = YEAR(GETDATE())) GROUP BY e.name;";
$res11 = sqlsrv_query($conn,$sql11);
while($row11 = sqlsrv_fetch_array( $res11, SQLSRV_FETCH_ASSOC))
{
	
	$aspirants = $row11['aspirants'];	
	$lastleavedate = $row11['lastleavedate'];  

	// Ensure $lastleavedate is not null or empty
	if (!empty($lastleavedate)) 
	{
		
		$d3 = DateTime::createFromFormat('Y-m-d', $lastleavedate);
		if ($d3 !== false) 
		{
			$formattedLastleavedate = $d3->format('d-m-Y');
		} 
		else 
		{
			$formattedLastleavedate = "Invalid date format"; 
		}
	} 
	else 
	{
		$formattedLastleavedate = "Date not available"; 
	}


		
	if($k<$aspirantscount)
	{	
			
		$totalaspirants1 .= '<b>'.$aspirants.'</b>[Last leave on '.$formattedLastleavedate.'],&nbsp;&nbsp;';	
		$k++;
	    
	}
	
}	

	$totalaspirants2 .= '<b>'.$aspirants.'</b>[Last leave on '.$formattedLastleavedate.']';

	$totalaspirants = $totalaspirants1 . $totalaspirants2;	
	
	if($aspirantscount == 0)
	{
		$totalaspirants = '<b>EVERY EMPLOYEE TOOK ATLEAST 1 LEAVE THIS MONTH</b>';
	}	
	

if($empid=='999' || $empid=='1108')
{
	$sql12 = "select count(empid) as empcount from emp with (nolock) where flag='Y' AND empid!='1118' AND empid!='1119'";
	$res12 = sqlsrv_query($conn,$sql12);
	while($row12 = sqlsrv_fetch_array( $res12, SQLSRV_FETCH_ASSOC))
	{
		
		$empcount=$row12['empcount'];

	}


$name1=''; 
$designation1=''; 
	
	
	$sql15 = "select name,designation from emp with (nolock) where empid='$empid'";
	$res15 = sqlsrv_query($conn,$sql15);
	while($row15 = sqlsrv_fetch_array( $res15, SQLSRV_FETCH_ASSOC))
	{
		
		if($row15['name']==NULL)
		{
			$row15['name']='';
		} 
		$name1 = trim($row15['name']); 

		if($row15['designation']==NULL)
		{
			$row15['designation']='';
		}
		$designation1 = trim($row15['designation']); 
		
		if($name1=='ARUN S PANICKER')
		{
			$name1='ARUN S PANICKER';
			$designation1='SENIOR DEVELOPER';
		}
		elseif($designation1='HEAD OF OPERATIONS')
		{
			$name1='RAJAGOPAL PULIYATHU';
			$designation1='HEAD OF OPERATIONS';
		}
		else
		{
			$name1='';	
		}	
	
	}	

	
	$action = '';

	$sql7 = "select * from emp with (nolock) where flag = 'Y' AND empid!='1118' AND empid!='1119' order by dob";
	$res7 = sqlsrv_query($conn,$sql7);
	while($row7 = sqlsrv_fetch_array( $res7, SQLSRV_FETCH_ASSOC))
	{
		
		if($row7['name']==NULL)
		{
			$row7['name']='';
		} 
		$name = trim($row7['name']); 

		if($row7['designation']==NULL)
		{
			$row7['designation']='';
		}
		$designation = trim($row7['designation']); 

		if($row7['empid']==NULL)
		{
			$row7['empid']='';
		}
		$empid = trim($row7['empid']);	
		
		if($row7['flag']==NULL)
		{
			$row7['flag']='';
		}
		$flag = trim($row7['flag']);			

		
		$dobDate = new DateTime($dob);
		$currentDate = new DateTime();
		$ageInterval = $currentDate->diff($dobDate);
		$age = $ageInterval->y;
			
		if($flag == 'Y')
		{
			$color = 'color:green';
		}
		else
		{
			$color = 'color:#f75f54';
		}	
				$checkbox = "<input type='checkbox' 
                class='flag-checkbox' 
                data-empid='$empid' 
                data-name='$name' 
                " . ($flag == 'Y' ? 'checked' : '') . ">";

				
				
		$trdata .= "<tr class='dis1'>
        <td align=center style='font-family:Poppins;padding:5px;font-size:14px;'>$empid</td>
        <td align=center style='font-family:Poppins;padding:5px;font-size:14px;'>$name</td>
        <td align=center style='font-family:Poppins;padding:5px;font-size:14px;'>$designation</td>
        <td align=center style='$color; font-family:Poppins;padding:5px;font-size:14px;'>$checkbox</td>
        </tr>";	

	}
	
	
?>

<script>

document.addEventListener('DOMContentLoaded', function () 
{
	
    const checkboxes = document.querySelectorAll('.flag-checkbox');

    checkboxes.forEach(function (checkbox)
	{
        checkbox.addEventListener('change', function () 
		{
            const empid = this.getAttribute('data-empid');
            const name = this.getAttribute('data-name');
            const formattedName = name.toLowerCase().replace(/\b\w/g, (char) => char.toUpperCase());
            const flag = this.checked ? 'Y' : 'N';
            const exitdate = this.checked ? new Date().toISOString().split('T')[0] : null;

            fetch('update_flag.php', 
			{
                method: 'POST',
                headers: 
				{
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ empid: empid, flag: flag, exitdate: exitdate }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) 
				{
                    if (flag === 'Y') 
					{
                        alert(`${formattedName} has been marked Enabled.`);
                    } 
					else 
					{
                        alert(`${formattedName} has been marked Disabled.`);
                    }					
					location.reload();					
                } 
				else 
				{
                    alert('Failed to update flag or exit date.');
                }
            })
            .catch(error => 
			{
                console.error('Error:', error);
                alert('An error occurred.');
            });
        });
    });
});



</script>




<style>
#holiday 
{
    display: none;
}

#leave 
{
    display: none;
}

#aspirants 
{
    display:none;
}

#table 
{
    display: none;
}

#content 
{
    display: none;
}

tbody tr:nth-child(odd)
{
	background-color:#e6eaf0;	
}

tbody tr:nth-child(even)
{
	background-color:#f2f3f5;	
}

.hide-scrollbar 
{
    overflow: hidden;
}

button:hover
{
    transform: scale(1.05);	
}

#congrats-message 
{ 
	font-size: 50px; 
	font-weight: bold; 
	color: #ff6347; 
	opacity: 0; 
	transform: scale(0.5); 
	transition: opacity 0.5s ease, transform 0.5s ease; 
} 

#congrats-message.show 
{ 
	opacity: 1; 
	transform: scale(1); 
}

@media screen and (max-width: 768px) 
{
    .button
	{
        width: 100px;
    }

    #content 
	{
        width: calc(100% - 60px);
        left: 200px;
    }

    #content nav .nav-link 
	{
        display: none;
    }
	
}
</style>


<body class="report" bottommargin="0" topmargin="0" leftmargin="0" rightmargin="0">

<br><br><br><br>
<div>
<br><br>

<button style="background-color:#665763;color:white;font-size:16px;width:220px;height:150px;border-radius:10px;margin-left:140px;" id="toggleButton"
title="<?php echo 'Hi there! Total Employees in this company is '.$empcount;?>">
    <div style="justify-content: space-around; display: flex; align-items: center; height: 71px;">
        <?php echo "<b><p style='font-size:30px;font-family:poppins;'>$empcount</p></b>"; ?>
        <img src="../leave/images/emp.png" style="width:60px;height:60px;">
    </div>
    <div>
        <span style="font-family:poppins;">
            <p>Total Employees</p>
        </span>
    </div>
</button>


<button style="background-color:#665763;color:white;font-size:16px;width:220px;height:150px;border-radius:10px;margin-left:70px;"  id='leaveemp'
title="<?php if($leaveemp==0){echo 'Everyone is present today';}elseif($leaveemp==1){echo '1 Employee is on leave today';}else{echo $leaveemp.' Employees are on leave today';}?>">
    <div style="justify-content: space-around; display: flex; align-items: center; height: 71px;">
        <?php echo "<b><p style='font-size:30px;font-family:poppins;'>$leaveemp</p></b>"; ?>
        <img src="../leave/images/grpicon.png" style="width:60px;height:60px;">
    </div>
    <div>
        <span style="font-family:poppins;">
            <p>Leave Today</p>
        </span>
    </div>
</button>


<button style="background-color:#665763;color:white;font-size:16px;width:220px;height:150px;border-radius:10px;margin-left:70px;" id='showHolidayBtn'
title="<?php echo $titleholiday; ?>">
    <div style="justify-content: space-around; display: flex; align-items: center; height: 71px;">
        <?php echo "<b><p style='font-size:30px;font-family:poppins;'>$result</p></b>"; ?>
        <img src="../leave/images/holiday.png" style="width:60px;height:60px;">
    </div>
    <div>
        <span style="font-family:poppins;">
            <p>Holidays</p>
        </span>
    </div>
</button>



<button style="background-color:#665763;color:white;font-size:16px;width:220px;height:150px;border-radius:10px;margin-left:70px;" id='workaspirants'
title="<?php echo $aspirantscount.' Work Aspirants'; ?>">

<div style="justify-content: space-around;
    display: flex;
    align-items: center;
    height: 71px;">
<?php echo "<b><p style='font-size:30px;font-family:poppins;'>$aspirantscount</p></b>"; ?>
<img src="../leave/images/balance.png" style="width:60px;height:60px;">
</div>

<div>
<span style="font-family:poppins;">
<p>Work Aspirants</p>
</span>
</div>

</button>


<?php

}

?>



</div>

<br>
<p id='leave' style="margin-left:140px;font-size:18px;font-family:poppins;">Leave Today - <i><?php echo $totalleaveemp; ?></i></p>
<p id='holiday' style="margin-left:140px;font-size:18px;font-family:poppins;">Public Holidays this month - <i><?php echo $trholi; ?></i></p>
<p id='aspirants' style="margin-left:140px;font-size:18px;font-family:poppins;">
Work Aspirants of the month - <i><?php echo $totalaspirants; ?></i>
</p>
<br><br><br>

<div>
    <div>
        <span style="font-family:poppins;">
            <h2 style="margin-left:145px;font-size:20px;font-family:poppins;">
			<b>
				<?php echo "MEFS ADMIN&nbsp;<br>"; ?>	
				<span style="font-size:18px;display:inline-block;width:84%;border-bottom:2px solid black;margin-top:10px;margin-bottom:10px;"></span>
			</b>
			</h2>
        </span>
    </div>
</div>

  <style>

    .popup 
	{
      display: none;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 800px;
      background: white;
      border: 1px solid #ccc;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      padding: 20px;
      z-index: 1000;
    }
    .popup-header 
	{
      font-size: 18px;
      margin-bottom: 10px;
      text-align: center;
    }
    .popup-close 
	{
      position: absolute;
      top: 0px;
      right: 10px;
      cursor: pointer;
      font-size: 40px;
	  font-weight:bold;
      color: black;
    }
	.popup-close:hover
	{
      color: red;
    }	
    .overlay 
	{
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      z-index: 999;
    }

  </style>
</head>
<body>





<script>

  function showPopup() 
  {
	  
    document.getElementById('overlay').style.display = 'block';
    document.getElementById('popup').style.display = 'block';

    fetch('exit_employees.php')
      .then(response => response.text())
      .then(data => 
	  {
        document.getElementById('popup-content').innerHTML = data;
      })
      .catch(err => 
	  {
        document.getElementById('popup-content').innerHTML = 'Failed to load data.';
        console.error(err);
      });
	  
  }

  function closePopup() 
  {
    document.getElementById('overlay').style.display = 'none';
    document.getElementById('popup').style.display = 'none';
  }
  
  
</script>



<div id="content">

<button type="button" class="sub1" style="margin-left:1060px;" onclick="showPopup()">EXIT EMPLOYEES</button>
<div class="overlay" id="overlay"></div>
<div class="popup" id="popup">
  <span class="popup-close" onclick="closePopup()">×</span>
  <div class="popup-header" style='font-family:poppins;font-weight:bold;'>Exit Employees</div>
  <div id="popup-content" style='font-family:poppins;'>Loading...</div>
</div>



    <table style="margin-left:425px;">
        <tr>
            <td align="center" style="background-color:#665763;color:white;font-family:Poppins;padding:5px;">EMPLOYEE ID</td>
            <td align="center" style="background-color:#665763;color:white;font-family:Poppins;padding:5px;">EMPLOYEE NAME</td>
            <td align="center" style="background-color:#665763;color:white;font-family:Poppins;padding:5px;">DESIGNATION</td>
            <td align="center" style="background-color:#665763;color:white;font-family:Poppins;padding:5px;">STATUS</td>
        </tr>

        <?php echo $trdata; ?>

    </table>
</div>
<br><br><br>
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>


<script>


function goBack() 
{
    history.go(-1);
}

	

let birthdayTriggered = false;


document.addEventListener('mousemove', function () 
{
    if (birthdayTriggered) return;

    const currentDate = new Date();
    const currentMonthDay = (currentDate.getMonth() + 1).toString().padStart(2, '0') + '-' + currentDate.getDate().toString().padStart(2, '0');
    const dob = "<?php echo date('m-d', strtotime($dob)); ?>"; 

    if (currentMonthDay === dob) {
        const currentDay = currentDate.getDay();

        if (currentDay === 6 || currentDay === 0) 
		{
            const daysUntilMonday = (1 - currentDay + 7) % 7; 
            const monday = new Date(currentDate);
            monday.setDate(currentDate.getDate() + daysUntilMonday);

            setTimeout(function () 
			{
                showBirthdayMessage();
            }, monday.getTime() - Date.now());
        } 
		else 
		{
            showBirthdayMessage(); // Show wish immediately
        }

        birthdayTriggered = true;
        localStorage.setItem('birthdayWishGiven', 'true'); // Mark birthday wish as shown
    }
});



function showBirthdayMessage() 
{
    const birthdayMessage = document.createElement('div');
    birthdayMessage.innerHTML = "🎉 Happy Birthday! 🎉<br>Wishing you many happy returns of the day!";
    birthdayMessage.style.position = 'fixed';
    birthdayMessage.style.top = '50%';
    birthdayMessage.style.left = '50%';
    birthdayMessage.style.transform = 'translate(-50%, -50%)';
    birthdayMessage.style.padding = '20px';
    birthdayMessage.style.backgroundColor = '#f9f9f9';
    birthdayMessage.style.borderRadius = '10px';
    birthdayMessage.style.boxShadow = '0 0 15px rgba(0, 0, 0, 0.1)';
    birthdayMessage.style.textAlign = 'center';
    birthdayMessage.style.fontSize = '24px';
    birthdayMessage.style.fontFamily = 'Arial, sans-serif';
    birthdayMessage.style.color = '#28373E';
    birthdayMessage.style.zIndex = '9999';
    birthdayMessage.style.opacity = '0';
    birthdayMessage.style.transition = 'opacity 1.5s ease-in-out';

    document.body.appendChild(birthdayMessage);

    setTimeout(function () 
	{
        birthdayMessage.style.opacity = '1';
    }, 100);

    const duration = 6 * 1000; // 6 seconds for confetti celebration
    const animationEnd = Date.now() + duration;
    const defaults = 
	{
        startVelocity: 30,
        spread: 360,
        ticks: 60,
        zIndex: 0
    };

    function randomInRange(min, max) {
        return Math.random() * (max - min) + min;
    }

    const interval = setInterval(function () 
	{
        const timeLeft = animationEnd - Date.now();
        if (timeLeft <= 0) 
		{
            clearInterval(interval);
            birthdayMessage.style.opacity = '0';
            setTimeout(function () 
			{
                birthdayMessage.remove();
            }, 500);
            return;
        }

        const particleCount = 50 * (timeLeft / duration);

        confetti(Object.assign({}, defaults, 
		{
            particleCount,
            origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 }
        }));

        confetti(Object.assign({}, defaults, 
		{
            particleCount,
            origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 }
        }));
    }, 250);
}


document.addEventListener('DOMContentLoaded', function () 
{
    const storedBirthday = localStorage.getItem('birthdayWishGiven');
    if (!storedBirthday) 
	{
        const currentDate = new Date();
        const currentMonthDay = (currentDate.getMonth() + 1).toString().padStart(2, '0') + '-' + currentDate.getDate().toString().padStart(2, '0');
        const dob = "<?php echo date('m-d', strtotime($dob)); ?>"; 


        if (currentMonthDay === dob) 
		{
            showBirthdayMessage();
            localStorage.setItem('birthdayWishGiven', 'true');
        } 
		else 
		{

            const previousDate = new Date(currentDate);
            previousDate.setDate(currentDate.getDate() - 1);
            const previousMonthDay = (previousDate.getMonth() + 1).toString().padStart(2, '0') + '-' + previousDate.getDate().toString().padStart(2, '0');


            if (previousMonthDay === dob) 
			{
                const previousDay = previousDate.getDay();
                if (previousDay === 6 || previousDay === 0) 
				{

                    const daysUntilMonday = (1 - previousDay + 7) % 7;
                    previousDate.setDate(previousDate.getDate() + daysUntilMonday);
                    setTimeout(function () 
					{
                        showBirthdayMessage();
                    }, previousDate.getTime() - Date.now());
                } 
				else 
				{
                    showBirthdayMessage(); 
                }
            }
        }
    }
});


// Check if the employee has missed the birthday wish (on the next day login)
document.addEventListener('DOMContentLoaded', function() 
{
    const storedBirthday = localStorage.getItem('birthdayWishGiven');
    if (!storedBirthday)
	{
        const currentDate = new Date();
        const currentMonthDay = (currentDate.getMonth() + 1).toString().padStart(2, '0') + '-' + currentDate.getDate().toString().padStart(2, '0');
        const dob = "<?php echo date('m-d', strtotime($dob)); ?>"; 

        if (currentMonthDay === dob) 
		{
            showBirthdayMessage();
            localStorage.setItem('birthdayWishGiven', 'true');
        } 
		else 
		{
            const previousDate = new Date(currentDate);
            previousDate.setDate(currentDate.getDate() - 1);
            const previousMonthDay = (previousDate.getMonth() + 1).toString().padStart(2, '0') + '-' + previousDate.getDate().toString().padStart(2, '0');

            if (previousMonthDay === dob) 
			{

                const previousDay = previousDate.getDay();
                if (previousDay === 6 || previousDay === 0) 
				{

                    const daysUntilMonday = (1 - previousDay + 7) % 7;
                    previousDate.setDate(previousDate.getDate() + daysUntilMonday);
                    setTimeout(function() 
					{
                        showBirthdayMessage();
                    }, previousDate.getTime() - Date.now());
                } 
				else 
				{
                    showBirthdayMessage();
                }
            }
        }
    }
});



		
	    document.getElementById('leaveemp').addEventListener('click', function() 
		{
			var holidayParagraph = document.getElementById('leave');
			if (holidayParagraph.style.display === 'none' || holidayParagraph.style.display === '') 
			{
				holidayParagraph.style.display = 'block'; 
			} 
			else 
			{
				holidayParagraph.style.display = 'none'; 
			}
		});		

	    document.getElementById('showHolidayBtn').addEventListener('click', function() 
		{
			var holidayParagraph = document.getElementById('holiday');
			if (holidayParagraph.style.display === 'none' || holidayParagraph.style.display === '') 
			{
				holidayParagraph.style.display = 'block'; 
			} 
			else 
			{
				holidayParagraph.style.display = 'none'; 
			}
		});
		
	    document.getElementById('workaspirants').addEventListener('click', function() 
		{
			var workaspirants = document.getElementById('aspirants');
			if (workaspirants.style.display === 'none' || workaspirants.style.display === '') 
			{
				workaspirants.style.display = 'block'; 
			} 
			else 
			{
				workaspirants.style.display = 'none'; 
			}
		});		

	    document.getElementById('toggleButton').addEventListener('click', function() 
		{		
			var contentDiv = document.getElementById('content');
			if (contentDiv.style.display === 'none' || contentDiv.style.display === '') 
			{
				contentDiv.style.display = 'block'; 
			} 
			else 
			{
				contentDiv.style.display = 'none'; 
			} 
		});

		</script>	

<?php
	}	
	
	}

}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

elseif(($emptype != 'TEST' && $emptype!='EMPLOYEE' && $power == 'N'))//Admin's Employee Dashboard
{

	date_default_timezone_set("Asia/Kolkata");

	if(date("h:i:sa") > '05:50:00' && date("h:i:sa") < '05:58:00')
	{
		?>	

		<body class="report" bottommargin="0" topmargin="0" leftmargin="0" rightmargin="0">	

		<center>
		<br>
		<img src="../leave/images/BugFixing.png" style="width:600px;height:600px;">
		<pre>
		System Maintenance from 05:00 to 06:00 IST
		Sorry for your inconvinience.
		</pre>
		</center>

		
		<script>
			function loadContent(url) 
			{
				document.getElementById('contentFrame').src = url;
			}

			const sidebar = document.getElementById('sidebar');
			const content = document.getElementById('content');

			sidebar.addEventListener('mouseover', () => 
			{
				content.style.marginLeft = '260px';
			});

			sidebar.addEventListener('mouseout', () => 
			{
				content.style.marginLeft = '85px';
			});

			// Add event listeners to sidebar links
			const sidebarLinks = document.querySelectorAll('.sidebar-links li a');
			sidebarLinks.forEach(link => 
			{
				
				link.addEventListener('click', function(e) 
				{
					e.preventDefault(); // Prevent default link behavior

					// Remove active class from all links
					sidebarLinks.forEach(link => link.classList.remove('active'));

					// Add active class to the clicked link
					this.classList.add('active');

					const href = this.getAttribute('href');
					loadContent(href); // Load content into iframe
				});
			});

			function logoutAndRedirect(event) 
			{
				event.preventDefault();
				window.top.location.href = 'logout.php';
			}
			
			
			function goBack() 
			{
			  history.back();
			}	
			
		</script>


		<?php
		exit;
	}
	else //Employee Dashboard
	{	

		global $leave;
		$sql5 = "select leavedate,empid from leave with (nolock) where empid='$empid'";
		$res5 = sqlsrv_query($conn,$sql5);
		while($row5 = sqlsrv_fetch_array( $res5, SQLSRV_FETCH_ASSOC))
		{
			
			global $mm,$time,$yy;
			if($row5['leavedate']==NULL)
			{
				$row5['leavedate']='';
			} 	
			$leavedate = trim($row5['leavedate']);
					
			if($row5['empid']==NULL)
			{
				$row5['empid']='';
			}
			$empid = trim($row5['empid']);

		}
		
		$sql2 = "select * from emp with (nolock) where flag='Y' and empid='$empid'";
		$res2 = sqlsrv_query($conn,$sql2);
		while($row2 = sqlsrv_fetch_array( $res2, SQLSRV_FETCH_ASSOC))
		{

			if($row2['name']==NULL)
			{
				$row2['name']='';
			} 
			$name = trim($row2['name']); 

			if($row2['dob']==NULL)
			{
				$row2['dob']='';
			}
			$dob = trim($row2['dob']); 

			if($row2['doj']==NULL)
			{
				$row2['doj']='';
			}
			$doj = trim($row2['doj']); 
			
			if($row2['cutoff']==NULL)
			{
				$row2['cutoff']='';
			}
			$cutoff = trim($row2['cutoff']);		

			if($row2['address']==NULL)
			{
				$row2['address']='';
			}
			$address = trim($row2['address']); 

			if($row2['gender']==NULL)
			{
				$row2['gender']='';
			}
			$gender = trim($row2['gender']); 

			if($row2['email']==NULL)
			{
				$row2['email']='';
			}
			$email = trim($row2['email']); 

			if($row2['mob']==NULL)
			{
				$row2['mob']='';
			}
			$mob = trim($row2['mob']); 

			if($row2['designation']==NULL)
			{
				$row2['designation']='';
			}
			$designation = trim($row2['designation']); 

			if($row2['dojtype']==NULL)
			{
				$row2['dojtype']='';
			}
			$dojtype = trim($row2['dojtype']); 

			if($row2['empid']==NULL)
			{
				$row2['empid']='';
			}
			$empid = trim($row2['empid']);
			
			if($row2['emptype']==NULL)
			{
				$row2['emptype']='';
			}
			$emptype  =  trim($row2['emptype']);
			
			if($row2['power']==NULL)
			{
				$row2['power']='';
			}
			$power  =  trim($row2['power']);			
			
			
			$dobDate = new DateTime($dob);
			$currentDate = new DateTime();
			$ageInterval = $currentDate->diff($dobDate);
			$age = $ageInterval->y;
			
			$date2 = DateTime::createFromFormat('Y-m-d', $doj);
			$formattedDoj = $date2->format('d-m-Y');

		$doj = new DateTime($doj);
		$currentDate = new DateTime(); 
		$interval = $doj->diff($currentDate);
		$monthsDifference = ($interval->y * 12) + $interval->m;
		//$yearsWorked = $interval->y + ($interval->m > 0 ? 1 : 0);
		
		$yy=0;
		$mm=0;
		$time=0;
		$congratulate=false;
		
		if( $monthsDifference == 0 )
		{
			$time = "LESS THAN 1 MONTH";
		}
		elseif( $monthsDifference == 1 )
		{
			$time = "1 MONTH";
		}	
		elseif( $monthsDifference > 1 && $monthsDifference < 12 )
		{
			$mm = $monthsDifference;
			$time = $mm." MONTHS";
		}	
		else
		{
			$mm = $monthsDifference % 12;
			$yy = $monthsDifference / 12;
			if((int)$yy == 1 && $mm == 0 )
			{
				$time = (int)$yy." YEAR";
				$congratulate = true; 				
			}
			elseif((int)$yy == 2 && $mm == 0  || (int)$yy == 3 && $mm == 0 || (int)$yy == 4 && $mm == 0 || (int)$yy == 5 && $mm == 0 || (int)$yy == 6 && $mm == 0 || (int)$yy == 7 && $mm == 0  || (int)$yy == 8 && $mm == 0  || (int)$yy == 9 && $mm == 0  || (int)$yy == 10 && $mm == 0)
			{
				$time = (int)$yy." YEARS";
				$congratulate = true; 
			}
			elseif((int)$yy == 1 && $mm == 1)
			{
				$time = (int)$yy." YEAR AND ".$mm." MONTH";
			}
			elseif((int)$yy == 2 && $mm == 1 || (int)$yy == 3 && $mm == 1 || (int)$yy == 4 && $mm == 1 || (int)$yy == 5 && $mm == 1 || (int)$yy == 6 && $mm == 1 || (int)$yy == 7 && $mm == 1 || (int)$yy == 8 && $mm == 1 || (int)$yy == 9 && $mm == 1 || (int)$yy == 10 && $mm == 1)
			{
				$time = (int)$yy." YEARS AND ".$mm." MONTH";
			}		
			elseif((int)$yy == 1 )
			{
				$time = (int)$yy." YEAR AND ".$mm." MONTHS";
			}
			else
			{
				$time = (int)$yy." YEARS AND ".$mm." MONTHS";
			}	
		
		}


		$totalLeaveBalance1 = getLeavesTakenPerMonth($empid);
			
		$color='background-color:green;';
		$plus='+';
		$plus1='%2B';
		
		global $leave;
		
		$totalLeaveBalance1 = $totalLeaveBalance1 + 1;
		
		if($dojtype=='N')
		{	
			$totalLeaveBalance1=$totalLeaveBalance1-1;	
		}
		
		if($totalLeaveBalance1==0)
		{
		$color='background-color:#ed9911;';
		$plus='';
		$plus1='';
		}	
		
		if($totalLeaveBalance1<0)
		{
		$color='background-color:#f75f54;';
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
					$color='background-color:green;';
					$plus='+';
				}
				else
				{
					$totalLeaveBalance1 = $monthsDifference;
					$totalLeaveBalance1=$totalLeaveBalance1+1;
					$color='background-color:green;';
					$plus='+';	
				}	
			}	
		} 	

		if($cutoff>0)
		{
			$color='background-color:green;';
			$totalLeaveBalance1 = ($totalLeaveBalance1 + $cutoff);

			if($totalLeaveBalance1==0)
			{
				$color='background-color:#ed9911;';
				$plus='';
				$plus1='';
			}	
			
			if($totalLeaveBalance1<0)
			{
				$color='background-color:#f75f54;';
				$plus='';
				$plus1='';
			}

			if($totalLeaveBalance1>0)
			{
				$color='background-color:green;';
				$plus='+';
				$plus1='+';
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


		$trholiday='';
		$trholiday1='';
		$trholiday2='';
		$holiday_day='';
		$holiday='';
		$leaveemp=0;
		$trholi='';
		$titleholiday='';
		$totalleaveemp1='';
		$totalleaveemp2='';
		$totalleaveemp='';
		$leaveempname='';
		$compoleave='';
		$compodate='';
		$totalcompodate1='';
		$totalcompodate2='';
		$totalcompodate='';
		$formattedcompodate='';
		$reason='N/A';
		$h=1;
		$i=1;
		$j=1;


		$sql8 = " select count(empid) as compoleave from compo where empid='$empid'";
		$res8 = sqlsrv_query($conn,$sql8);
		while($row8 = sqlsrv_fetch_array( $res8, SQLSRV_FETCH_ASSOC))
		{
			$compoleave = $row8['compoleave'];	
		}
		
		$sql9 = " select compodate from compo where empid='$empid' order by compodate";
		$res9 = sqlsrv_query($conn,$sql9);
		while($row9 = sqlsrv_fetch_array( $res9, SQLSRV_FETCH_ASSOC))
		{
			if($row9['compodate']==NULL)
			{
				$row9['compodate']='';
			}
			$compodate = trim($row9['compodate']);
			
		$date3 = DateTime::createFromFormat('Y-m-d', $compodate);
		$formattedcompodate = $date3->format('d-m-Y');
			
			if($h<$compoleave)
			{			
				$totalcompodate1 .=	$formattedcompodate.',&nbsp;';
				$h++;
			}
		}		

		$totalcompodate1 .= $formattedcompodate;

		$totalcompodate = $totalcompodate1 . $totalcompodate2;
		

		$sql = "  SELECT CAST(COUNT(holiday) AS VARCHAR(50)) AS result FROM holiday  
                  WHERE date >= GETDATE() AND date <= EOMONTH(GETDATE())";
		$res = sqlsrv_query($conn,$sql);
		while($row = sqlsrv_fetch_array( $res, SQLSRV_FETCH_ASSOC))
		{

			$result = $row['result'];	

		}
		
		$sql1 = "SELECT holiday, DAY(date) AS holiday_day FROM holiday
		         WHERE date >= CONVERT(DATE, GETDATE()) AND date <= EOMONTH(GETDATE())";
		$res1 = sqlsrv_query($conn,$sql1);
		while($row1 = sqlsrv_fetch_array( $res1, SQLSRV_FETCH_ASSOC))
		{

			$holiday_day = $row1['holiday_day'];
			
			if($row1['holiday']==NULL)
			{
				$row1['holiday']='';
			}
			$holiday = trim($row1['holiday']); 	
			
				if($i<$result)
				{
					$trholiday1 .= '&nbsp;<b>'.$holiday .'</b>&nbsp;on <b>'.$holiday_day.'th</b>,&nbsp;';
					$i++;
				}
				$titleholiday .= '&nbsp;'.$holiday .'&nbsp;on '.$holiday_day.'th,';			
		
		}
		
		

	$trholiday2 .= '&nbsp;<b>'.$holiday .'</b>&nbsp;on <b>'.$holiday_day.'th</b>';

	$trholi = $trholiday1 . $trholiday2;

	if(!$holiday)
	{
		$trholi='<b>NO PUBLIC HOLIDAYS THIS MONTH</b>';
	}	


		$sql3 = "SELECT COUNT(empid) AS leaveemp FROM leave WHERE CAST(leavedate AS DATE) = CAST(GETDATE() AS DATE)
		         AND empid NOT IN ('1118', '1119');";
		$res3 = sqlsrv_query($conn,$sql3);
		while($row3 = sqlsrv_fetch_array( $res3, SQLSRV_FETCH_ASSOC))
		{
			$leaveemp = $row3['leaveemp'];		
		}	


		$sql4 = "SELECT e.name AS leaveempname,reason FROM leave l JOIN emp e ON l.empid = e.empid 
				 WHERE CAST(l.leavedate AS DATE) = CAST(GETDATE() AS DATE) AND l.empid NOT IN ('1118', '1119');";
		$res4 = sqlsrv_query($conn,$sql4);
		while($row4 = sqlsrv_fetch_array( $res4, SQLSRV_FETCH_ASSOC))
		{
			
			$leaveempname = $row4['leaveempname'];
			$reason = $row4['reason'];	
			
			if($j<$leaveemp)
			{	
				if($reason=='')
				{
					$reason='N/A';
				}
				
				$totalleaveemp1 .= '<b>'.$leaveempname.'</b>'.'['.$reason.']'.',&nbsp;&nbsp;';	
				$j++;
			
			}	
		
		} 
		
		if($reason=='')
		{
			$reason='N/A';
		}	

		$totalleaveemp2 .= '<b>'.$leaveempname.'</b>'.'['.$reason.']';

		$totalleaveemp = $totalleaveemp1 . $totalleaveemp2;	


		if($leaveemp==0)
		{
			$totalleaveemp='<b>EVERYONE IS PRESENT TODAY</b>';
		}



	if($emptype=='EMPLOYEE' || $emptype=='TEST' || $power == 'Y')
	{
		$sql12 = "select count(empid) as empcount from emp with (nolock) where flag='Y'";
		$res12 = sqlsrv_query($conn,$sql12);
		while($row12 = sqlsrv_fetch_array( $res12, SQLSRV_FETCH_ASSOC))
		{
			$empcount=$row12['empcount'];
		}	
	?>

	<style>
#holidays 
{
	display:none;
}
#leave
{
	display:none;
}
#leavebalance 
{
	display:none;
}
	button:hover
	{
		transform: scale(1.05);	
	}
#congrats-message 
{ 
	font-size: 50px; font-weight: bold; color: #ff6347; opacity: 0; transform: scale(0.5); transition: opacity 0.5s ease, transform 0.5s ease; 
} 
#congrats-message.show 
{ 
	opacity: 1; 
	transform: scale(1); 
}


@media screen and (max-width: 768px) 
{
    .button
	{
        width: 100px;
    }

    #content 
	{
        width: calc(100% - 60px);
        left: 200px;
    }

    #content nav .nav-link 
	{
        display: none;
    }
	
}
	
	</style>


	<body class="report" bottommargin="0" topmargin="0" leftmargin="0" rightmargin="0">

	<br><br><br><br>
	<div>
	<br><br>
	<button id="experience-btn" style="background-color:#54646B;color:white;font-size:16px;width:220px;height:150px;border-radius:10px;margin-left:160px;" title="<?php echo 'You have '.strtolower($time).' of experience.';?>">

	<div style="justify-content: space-around;
		display: flex;
		align-items: center;
		height: 71px;">
	<?php echo "<b><p style='font-size:20px;font-family:poppins;'>$time</p></b>"; ?>
	</div>

	<div>
	<span style="font-family:poppins;">
	<p>Experience</p>
	</span>
	</div>

	</button>


	<button style="background-color:#54646B;color:white;font-size:16px;width:220px;height:150px;border-radius:10px;margin-left:70px;" id="leaveemp"
	title="<?php if($leaveemp==0){echo 'Everyone is present today';}elseif($leaveemp==1){echo '1 Employee is on leave today';}else{echo $leaveemp.' Employees are on leave today';}?>">
		<div style="justify-content: space-around; display: flex; align-items: center; height: 71px;">
			<?php echo "<b><p style='font-size:30px;font-family:poppins;'>$leaveemp</p></b>"; ?>
			<img src="../leave/images/grpicon.png" style="width:60px;height:60px;">
		</div>
		<div>
			<span style="font-family:poppins;">
				<p>Leave Today</p>
			</span>
		</div>
	</button>

	<button style="background-color:#54646B;color:white;font-size:16px;width:220px;height:150px;border-radius:10px;margin-left:70px;" id='showHoliday'
	title="<?php echo $titleholiday ;?>">
		<div style="justify-content: space-around; display: flex; align-items: center; height: 71px;">
			<?php echo "<b><p style='font-size:30px;font-family:poppins;'>$result</p></b>"; ?>
			<img src="../leave/images/holiday.png" style="width:60px;height:60px;">
		</div>
		<div>
			<span style="font-family:poppins;">
				<p>Holidays</p>
			</span>
		</div>
	</button>



	<button style="background-color:#54646B;color:white;font-size:16px;width:220px;height:150px;border-radius:10px;margin-left:70px;"
		title="<?php 
		if($totalLeaveBalance1==1)
		{echo 'Your leave balance is running low. Please plan accordingly!' ;}
		elseif($totalLeaveBalance1<2)
		{echo 'Your leave balance is running low. You currently have no casual leaves left. Please plan accordingly!' ;}
		else
		{echo 'Your casual leave balance is running strong.';}
		if($compoleave)
		{
			echo ' Your Compo leave balance is '.$compoleave.' Compensatory Dates - '.$totalcompodate;
		}
		else
		{
			echo ' You have no Compo leaves left';
		}
		?>" id='currentleavebalance'>

		<div style="justify-content: space-around;
			display: flex;
			align-items: center;
			height: 71px;">
			<?php echo "<b><p style='font-size:30px;font-family:poppins;'>$plus$totalLeaveBalance1</p></b>"; ?>
			<img src="../leave/images/balance.png" style="width:60px;height:60px;">
		</div>

		<div>
			<span style="font-family:poppins;">
				<p>Current Leave Balance</p>
			</span>
		</div>

	</button>

<!--<div id="congrats-message">Congratulations!</div>-->

	<?php
	}
		
	
	
	?>



	</div>

	<br>
	<p id='leave' style="margin-left:160px;font-size:18px;font-family:poppins;">Leave Today - <i><?php echo $totalleaveemp; ?></i></p>
	<p id='holidays' style="margin-left:160px;font-size:18px;font-family:poppins;">Public Holidays this month - <i><?php echo $trholi; ?></i></p>
	<p id='leavebalance' style="margin-left:160px;font-size:18px;font-family:poppins;">
	<i>
	<?php 
	if($totalLeaveBalance1==1)
		{echo 'Your leave balance is running low. Please plan accordingly!' ;} 
	elseif($totalLeaveBalance1<1)
		{echo 'Your <b>leave balance is running low.</b><br>You currently have <b>no casual leaves left</b>. Your current leave balance is <b>'.$totalLeaveBalance1.'</b>.  Please plan accordingly!' ;}
	else
		{echo 'Your casual leave balance is running strong. Your current leave balance is <b>+'.$totalLeaveBalance1.'</b>';}
	if($compoleave)
	{
		echo '<br>Your Compo leave balance is <b>'.$compoleave.'</b><br>Compensatory Dates - <b>'.$totalcompodate;
	}
	else
	{
		echo '<br>You have no Compo leaves left';
	}	
	?>
	</i>
	</p>

	<br><br>
	<div>
		<div>
			<span style="font-family:poppins;">
				<h2 style="margin-left:165px;font-size:20px;font-family:poppins;">
				<b>
					<?php echo $name.",&nbsp;".$age."<br>"; ?>
					<i>
					<span style="font-size:18px;"><?php echo $designation."<br>"; ?></span>
					</i>	
					<span style="font-size:18px;display:inline-block;width:85%;border-bottom:2px solid black;margin-top:10px;margin-bottom:10px;"></span>
				</b>
				</h2>
			</span>
		</div>
	</div>


<style>
#notification 
{
    display: none;
    position: fixed;
    top: 100px;
    right: 10px;
    background-color: #ffeb3b;
    color: #333;
    padding: 10px;
    border-radius: 5px;
}
</style>


			<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>


			<script>
						
			function goBack() 
			{
				history.go(-1);
			}



let birthdayTriggered = false;


document.addEventListener('mousemove', function () 
{
    if (birthdayTriggered) return;

    const currentDate = new Date();
    const currentMonthDay = (currentDate.getMonth() + 1).toString().padStart(2, '0') + '-' + currentDate.getDate().toString().padStart(2, '0');
    const dob = "<?php echo date('m-d', strtotime($dob)); ?>"; 

    if (currentMonthDay === dob) {
        const currentDay = currentDate.getDay();

        if (currentDay === 6 || currentDay === 0) 
		{
            const daysUntilMonday = (1 - currentDay + 7) % 7; 
            const monday = new Date(currentDate);
            monday.setDate(currentDate.getDate() + daysUntilMonday);

            setTimeout(function () 
			{
                showBirthdayMessage();
            }, monday.getTime() - Date.now());
        } 
		else 
		{
            showBirthdayMessage(); // Show wish immediately
        }

        birthdayTriggered = true;
        localStorage.setItem('birthdayWishGiven', 'true'); // Mark birthday wish as shown
    }
});



function showBirthdayMessage() 
{
    const birthdayMessage = document.createElement('div');
    birthdayMessage.innerHTML = "🎉 Happy Birthday! 🎉<br>Wishing you many happy returns of the day!";
    birthdayMessage.style.position = 'fixed';
    birthdayMessage.style.top = '50%';
    birthdayMessage.style.left = '50%';
    birthdayMessage.style.transform = 'translate(-50%, -50%)';
    birthdayMessage.style.padding = '20px';
    birthdayMessage.style.backgroundColor = '#f9f9f9';
    birthdayMessage.style.borderRadius = '10px';
    birthdayMessage.style.boxShadow = '0 0 15px rgba(0, 0, 0, 0.1)';
    birthdayMessage.style.textAlign = 'center';
    birthdayMessage.style.fontSize = '24px';
    birthdayMessage.style.fontFamily = 'Arial, sans-serif';
    birthdayMessage.style.color = '#28373E';
    birthdayMessage.style.zIndex = '9999';
    birthdayMessage.style.opacity = '0';
    birthdayMessage.style.transition = 'opacity 1.5s ease-in-out';

    document.body.appendChild(birthdayMessage);

    setTimeout(function () 
	{
        birthdayMessage.style.opacity = '1';
    }, 100);

    const duration = 6 * 1000; // 6 seconds for confetti celebration
    const animationEnd = Date.now() + duration;
    const defaults = 
	{
        startVelocity: 30,
        spread: 360,
        ticks: 60,
        zIndex: 0
    };

    function randomInRange(min, max) {
        return Math.random() * (max - min) + min;
    }

    const interval = setInterval(function () 
	{
        const timeLeft = animationEnd - Date.now();
        if (timeLeft <= 0) 
		{
            clearInterval(interval);
            birthdayMessage.style.opacity = '0';
            setTimeout(function () 
			{
                birthdayMessage.remove();
            }, 500);
            return;
        }

        const particleCount = 50 * (timeLeft / duration);

        confetti(Object.assign({}, defaults, 
		{
            particleCount,
            origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 }
        }));

        confetti(Object.assign({}, defaults, 
		{
            particleCount,
            origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 }
        }));
    }, 250);
}


document.addEventListener('DOMContentLoaded', function () 
{
    const storedBirthday = localStorage.getItem('birthdayWishGiven');
    if (!storedBirthday) 
	{
        const currentDate = new Date();
        const currentMonthDay = (currentDate.getMonth() + 1).toString().padStart(2, '0') + '-' + currentDate.getDate().toString().padStart(2, '0');
        const dob = "<?php echo date('m-d', strtotime($dob)); ?>"; 


        if (currentMonthDay === dob) 
		{
            showBirthdayMessage();
            localStorage.setItem('birthdayWishGiven', 'true');
        } 
		else 
		{

            const previousDate = new Date(currentDate);
            previousDate.setDate(currentDate.getDate() - 1);
            const previousMonthDay = (previousDate.getMonth() + 1).toString().padStart(2, '0') + '-' + previousDate.getDate().toString().padStart(2, '0');


            if (previousMonthDay === dob) 
			{
                const previousDay = previousDate.getDay();
                if (previousDay === 6 || previousDay === 0) 
				{

                    const daysUntilMonday = (1 - previousDay + 7) % 7;
                    previousDate.setDate(previousDate.getDate() + daysUntilMonday);
                    setTimeout(function () 
					{
                        showBirthdayMessage();
                    }, previousDate.getTime() - Date.now());
                } 
				else 
				{
                    showBirthdayMessage(); 
                }
            }
        }
    }
});


// Check if the employee has missed the birthday wish (on the next day login)
document.addEventListener('DOMContentLoaded', function() 
{
    const storedBirthday = localStorage.getItem('birthdayWishGiven');
    if (!storedBirthday)
	{
        const currentDate = new Date();
        const currentMonthDay = (currentDate.getMonth() + 1).toString().padStart(2, '0') + '-' + currentDate.getDate().toString().padStart(2, '0');
        const dob = "<?php echo date('m-d', strtotime($dob)); ?>"; 

        if (currentMonthDay === dob) 
		{
            showBirthdayMessage();
            localStorage.setItem('birthdayWishGiven', 'true');
        } 
		else 
		{
            const previousDate = new Date(currentDate);
            previousDate.setDate(currentDate.getDate() - 1);
            const previousMonthDay = (previousDate.getMonth() + 1).toString().padStart(2, '0') + '-' + previousDate.getDate().toString().padStart(2, '0');

            if (previousMonthDay === dob) 
			{

                const previousDay = previousDate.getDay();
                if (previousDay === 6 || previousDay === 0) 
				{

                    const daysUntilMonday = (1 - previousDay + 7) % 7;
                    previousDate.setDate(previousDate.getDate() + daysUntilMonday);
                    setTimeout(function() 
					{
                        showBirthdayMessage();
                    }, previousDate.getTime() - Date.now());
                } 
				else 
				{
                    showBirthdayMessage();
                }
            }
        }
    }
});


<?php

if ($time != '1 YEAR') 
{
    $time1 = substr($time, 0, 1); 
} 
else 
{
    $time1 = '1'; 
}


		$sql16 = "select * from emp with (nolock) where flag='Y' and empid='$empid'";
		$res16 = sqlsrv_query($conn,$sql16);
		while($row16 = sqlsrv_fetch_array( $res16, SQLSRV_FETCH_ASSOC))
		{
			if($row16['doj']==NULL)
			{
				$row16['doj']='';
			}
			$doj = trim($row16['doj']); 
		}	


$today = date('Y-m-d'); // Current date
$anniversaryFlag = (date('m-d', strtotime($doj)) === date('m-d')) ? 'true' : 'false';
$time1 = date('Y') - date('Y', strtotime($doj)); // Years of service


?>




let congratulateTriggered = false;
const time1 = "<?php echo $time1; ?>"; // Years of service
const anniversaryFlag = "<?php echo $anniversaryFlag; ?>"; // Check if today is the anniversary
const doj = new Date("<?php echo $doj; ?>"); // Employee's date of joining
const today = new Date(); // Today's date


function isAnniversary(doj, today) 
{
    return doj.getDate() === today.getDate() && doj.getMonth() === today.getMonth();
}

document.addEventListener('mousemove', function () 
{
    if (congratulateTriggered) return;

    if (anniversaryFlag === 'true' && isAnniversary(doj, today)) 
	{
        congratulateTriggered = true;
        const congratsMessage = document.createElement('div');
        congratsMessage.innerHTML = `🎉 Congratulations on your ${time1 === '1' ? "one-year" : `${time1}-year`} Anniversary! 🎉<br>Well done on achieving this milestone!`;

        congratsMessage.style.position = 'fixed';
        congratsMessage.style.top = '50%';
        congratsMessage.style.left = '50%';
        congratsMessage.style.transform = 'translate(-50%, -50%)';
        congratsMessage.style.padding = '20px';
        congratsMessage.style.backgroundColor = '#f9f9f9';
        congratsMessage.style.borderRadius = '10px';
        congratsMessage.style.boxShadow = '0 0 15px rgba(0, 0, 0, 0.1)';
        congratsMessage.style.textAlign = 'center';
        congratsMessage.style.fontSize = '24px';
        congratsMessage.style.fontFamily = 'Arial, sans-serif';
        congratsMessage.style.color = '#28373E';
        congratsMessage.style.zIndex = '9999';
        congratsMessage.style.opacity = '0';
        congratsMessage.style.transition = 'opacity 1.5s ease-in-out';

        document.body.appendChild(congratsMessage);

        setTimeout(function () {
            congratsMessage.style.opacity = '1';
        }, 100);

        // Confetti animation logic
        const duration = 6 * 1000;
        const animationEnd = Date.now() + duration;
        const defaults = {
            startVelocity: 30,
            spread: 360,
            ticks: 60,
            zIndex: 0,
        };

        function randomInRange(min, max) {
            return Math.random() * (max - min) + min;
        }

        const interval = setInterval(function () {
            const timeLeft = animationEnd - Date.now();
            if (timeLeft <= 0) {
                clearInterval(interval);
                congratsMessage.style.opacity = '0';
                setTimeout(function () {
                    congratsMessage.remove();
                }, 500);
                return;
            }

            const particleCount = 50 * (timeLeft / duration);

            confetti(Object.assign({}, defaults, {
                particleCount,
                origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 },
            }));

            confetti(Object.assign({}, defaults, {
                particleCount,
                origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 },
            }));
        }, 250);
    }
});








const congratulate = <?php echo json_encode($congratulate); ?>;


document.getElementById('experience-btn').addEventListener('click', function() 
{
    var time = "<?php echo strtolower($time); ?>";
    if (congratulate) 
	{
        const congratsMessage = document.createElement('div');
        if (time1 == '1') 
		{
            congratsMessage.innerHTML = "🎉 Congratulations on your one year! 🎉<br>Well done on achieving this milestone!";
        } 
		else if (time1 == '2') 
		{
            congratsMessage.innerHTML = "🎉 Congratulations on your two year Anniversary! 🎉<br>Well done on achieving this milestone!";
        } 
		else if (time1 == '3') 
		{
            congratsMessage.innerHTML = "🎉 Congratulations on your three year Anniversary! 🎉<br>Well done on achieving this milestone!";
        } 
		else if (time1 == '4') 
		{
            congratsMessage.innerHTML = "🎉 Congratulations on your four year Anniversary! 🎉<br>Well done on achieving this milestone!";
        } 
		else if (time1 == '5') 
		{
            congratsMessage.innerHTML = "🎉 Congratulations on your five year Anniversary! 🎉<br>Well done on achieving this milestone!";
        } 
		else if (time1 == '6') 
		{
            congratsMessage.innerHTML = "🎉 Congratulations on your six year Anniversary! 🎉<br>Well done on achieving this milestone!";
        } 
		else if (time1 == '7') 
		{
            congratsMessage.innerHTML = "🎉 Congratulations on your seven year Anniversary! 🎉<br>Well done on achieving this milestone!";
        } 
		else if (time1 == '8') 
		{
            congratsMessage.innerHTML = "🎉 Congratulations on your eight year Anniversary! 🎉<br>Well done on achieving this milestone!";
        }
		else if (time1 == '9') 
		{
            congratsMessage.innerHTML = "🎉 Congratulations on your nine year Anniversary! 🎉<br>Well done on achieving this milestone!";
        } 
		else if (time1 == '10') 
		{
            congratsMessage.innerHTML = "🎉 Congratulations on your ten year Anniversary! 🎉<br>Well done on achieving this milestone!";
        } 
		
        congratsMessage.style.position = 'fixed';
        congratsMessage.style.top = '50%';
        congratsMessage.style.left = '50%';
        congratsMessage.style.transform = 'translate(-50%, -50%)';
        congratsMessage.style.padding = '20px';
        congratsMessage.style.backgroundColor = '#f9f9f9';
        congratsMessage.style.borderRadius = '10px';
        congratsMessage.style.boxShadow = '0 0 15px rgba(0, 0, 0, 0.1)';
        congratsMessage.style.textAlign = 'center';
        congratsMessage.style.fontSize = '24px';
        congratsMessage.style.fontFamily = 'Arial, sans-serif';
        congratsMessage.style.color = '#28373E';
        congratsMessage.style.zIndex = '9999';
        congratsMessage.style.opacity = '0';
        congratsMessage.style.transition = 'opacity 1.5s ease-in-out';
        
        document.body.appendChild(congratsMessage);

        setTimeout(function() 
		{
            congratsMessage.style.opacity = '1';
        }, 100);

        const duration = 7 * 1000;
        const animationEnd = Date.now() + duration;
        const defaults = 
		{
            startVelocity: 30,
            spread: 360,
            ticks: 60,
            zIndex: 0
        };

        function randomInRange(min, max) 
		{
            return Math.random() * (max - min) + min;
        }

        const interval = setInterval(function() 
		{
            const timeLeft = animationEnd - Date.now();
            if (timeLeft <= 0) 
			{
                clearInterval(interval);
                congratsMessage.style.opacity = '0'; 
                setTimeout(function() 
				{
                    congratsMessage.remove();
                }, 500);
                return;
            }

            const particleCount = 50 * (timeLeft / duration); 

            confetti(Object.assign({}, defaults,			
			{
                particleCount,
                origin: 
				{
                    x: randomInRange(0.1, 0.3), 
                    y: Math.random() - 0.2 
                }
            }));

            confetti(Object.assign({}, defaults, 
			{
                particleCount,
                origin: 
				{
                    x: randomInRange(0.7, 0.9), 
                    y: Math.random() - 0.2 
                }
            }));
        }, 250); 
    } 
	else 
	{
        //alert(`You have ${time} of experience!`); 
    }
});




			document.getElementById('leaveemp').addEventListener('click', function() 
			{
				var holidayParagraph = document.getElementById('leave');
				if (holidayParagraph.style.display === 'none' || holidayParagraph.style.display === '') 
				{
					holidayParagraph.style.display = 'block'; 
				} 
				else 
				{
					holidayParagraph.style.display = 'none'; 
				}
			});
			
			document.getElementById('showHoliday').addEventListener('click', function() 
			{
				var holidayParagraph = document.getElementById('holidays');
				if (holidayParagraph.style.display === 'none' || holidayParagraph.style.display === '') 
				{
					holidayParagraph.style.display = 'block'; 
				} 
				else 
				{
					holidayParagraph.style.display = 'none'; 
				}
			});
			
			
			document.getElementById('currentleavebalance').addEventListener('click', function() 
			{
				var holidayParagraph = document.getElementById('leavebalance');
				if (holidayParagraph.style.display === 'none' || holidayParagraph.style.display === '') 
				{
					holidayParagraph.style.display = 'block';
				} 
				else 
				{
					holidayParagraph.style.display = 'none';
				}
			});
			</script>	

<?php
		}		
	
	}	
	
	
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

elseif($flag == 'Y' && $emptype != 'ADMIN' && $emptype != 'TEST')//Employee Dashboard
{
	
	date_default_timezone_set("Asia/Kolkata");

	if(date("h:i:sa") > '05:50:00' && date("h:i:sa") < '05:58:00')
	{
		?>	

		<body class="report" bottommargin="0" topmargin="0" leftmargin="0" rightmargin="0">	

		<center>
		<br>
		<img src="../leave/images/BugFixing.png" style="width:600px;height:600px;">
		<pre>
		System Maintenance from 05:00 to 06:00 IST
		Sorry for your inconvinience.
		</pre>
		</center>

		
		<script>
			function loadContent(url) 
			{
				document.getElementById('contentFrame').src = url;
			}

			const sidebar = document.getElementById('sidebar');
			const content = document.getElementById('content');

			sidebar.addEventListener('mouseover', () => 
			{
				content.style.marginLeft = '260px';
			});

			sidebar.addEventListener('mouseout', () => 
			{
				content.style.marginLeft = '85px';
			});

			// Add event listeners to sidebar links
			const sidebarLinks = document.querySelectorAll('.sidebar-links li a');
			sidebarLinks.forEach(link => 
			{
				
				link.addEventListener('click', function(e) 
				{
					e.preventDefault(); // Prevent default link behavior

					// Remove active class from all links
					sidebarLinks.forEach(link => link.classList.remove('active'));

					// Add active class to the clicked link
					this.classList.add('active');

					const href = this.getAttribute('href');
					loadContent(href); // Load content into iframe
				});
			});

			function logoutAndRedirect(event) 
			{
				event.preventDefault();
				window.top.location.href = 'logout.php';
			}
			
			
			function goBack() 
			{
			  history.back();
			}	
			
		</script>


		<?php
		exit;
	}
	else //Employee Dashboard
	{	

		global $leave;
		$sql5 = "select leavedate,empid from leave with (nolock) where empid='$empid'";
		$res5 = sqlsrv_query($conn,$sql5);
		while($row5 = sqlsrv_fetch_array( $res5, SQLSRV_FETCH_ASSOC))
		{
			
			global $mm,$time,$yy;
			if($row5['leavedate']==NULL)
			{
				$row5['leavedate']='';
			} 	
			$leavedate = trim($row5['leavedate']);
					
			if($row5['empid']==NULL)
			{
				$row5['empid']='';
			}
			$empid = trim($row5['empid']);

		}
		
		$sql2 = "select * from emp with (nolock) where flag='Y' and empid='$empid'";
		$res2 = sqlsrv_query($conn,$sql2);
		while($row2 = sqlsrv_fetch_array( $res2, SQLSRV_FETCH_ASSOC))
		{

			if($row2['name']==NULL)
			{
				$row2['name']='';
			} 
			$name = trim($row2['name']); 

			if($row2['dob']==NULL)
			{
				$row2['dob']='';
			}
			$dob = trim($row2['dob']); 

			if($row2['doj']==NULL)
			{
				$row2['doj']='';
			}
			$doj = trim($row2['doj']); 
			
			if($row2['cutoff']==NULL)
			{
				$row2['cutoff']='';
			}
			$cutoff = trim($row2['cutoff']);		

			if($row2['address']==NULL)
			{
				$row2['address']='';
			}
			$address = trim($row2['address']); 

			if($row2['gender']==NULL)
			{
				$row2['gender']='';
			}
			$gender = trim($row2['gender']); 

			if($row2['email']==NULL)
			{
				$row2['email']='';
			}
			$email = trim($row2['email']); 

			if($row2['mob']==NULL)
			{
				$row2['mob']='';
			}
			$mob = trim($row2['mob']); 

			if($row2['designation']==NULL)
			{
				$row2['designation']='';
			}
			$designation = trim($row2['designation']); 

			if($row2['dojtype']==NULL)
			{
				$row2['dojtype']='';
			}
			$dojtype = trim($row2['dojtype']); 

			if($row2['empid']==NULL)
			{
				$row2['empid']='';
			}
			$empid = trim($row2['empid']);
			
			if($row2['emptype']==NULL)
			{
				$row2['emptype']='';
			}
			$emptype = trim($row2['emptype']);
			
			if($row2['power']==NULL)
			{
				$row2['power']='';
			}
			$power = trim($row2['power']);			
			
			
			$dobDate = new DateTime($dob);
			$currentDate = new DateTime();
			$ageInterval = $currentDate->diff($dobDate);
			$age = $ageInterval->y;
			
			$date2 = DateTime::createFromFormat('Y-m-d', $doj);
			$formattedDoj = $date2->format('d-m-Y');

		$doj = new DateTime($doj);
		$currentDate = new DateTime(); 
		$interval = $doj->diff($currentDate);
		$monthsDifference = ($interval->y * 12) + $interval->m;
		//$yearsWorked = $interval->y + ($interval->m > 0 ? 1 : 0);
		
		$yy=0;
		$mm=0;
		$time=0;
		$congratulate=false;
		
		if( $monthsDifference == 0 )
		{
			$time = "LESS THAN 1 MONTH";
		}
		elseif( $monthsDifference == 1 )
		{
			$time = "1 MONTH";
		}	
		elseif( $monthsDifference > 1 && $monthsDifference < 12 )
		{
			$mm = $monthsDifference;
			$time = $mm." MONTHS";
		}	
		else
		{
			$mm = $monthsDifference % 12;
			$yy = $monthsDifference / 12;
			if((int)$yy == 1 && $mm == 0 )
			{
				$time = (int)$yy." YEAR";
				$congratulate = true; 				
			}
			elseif((int)$yy == 2 && $mm == 0  || (int)$yy == 3 && $mm == 0 || (int)$yy == 4 && $mm == 0 || (int)$yy == 5 && $mm == 0 || (int)$yy == 6 && $mm == 0 || (int)$yy == 7 && $mm == 0  || (int)$yy == 8 && $mm == 0  || (int)$yy == 9 && $mm == 0  || (int)$yy == 10 && $mm == 0)
			{
				$time = (int)$yy." YEARS";
				$congratulate = true; 
			}
			elseif((int)$yy == 1 && $mm == 1)
			{
				$time = (int)$yy." YEAR AND ".$mm." MONTH";
			}
			elseif((int)$yy == 2 && $mm == 1 || (int)$yy == 3 && $mm == 1 || (int)$yy == 4 && $mm == 1 || (int)$yy == 5 && $mm == 1 || (int)$yy == 6 && $mm == 1 || (int)$yy == 7 && $mm == 1 || (int)$yy == 8 && $mm == 1 || (int)$yy == 9 && $mm == 1 || (int)$yy == 10 && $mm == 1)
			{
				$time = (int)$yy." YEARS AND ".$mm." MONTH";
			}		
			elseif((int)$yy == 1 )
			{
				$time = (int)$yy." YEAR AND ".$mm." MONTHS";
			}
			else
			{
				$time = (int)$yy." YEARS AND ".$mm." MONTHS";
			}	
		
		}


		$totalLeaveBalance1 = getLeavesTakenPerMonth($empid);
			
		$color='background-color:green;';
		$plus='+';
		$plus1='%2B';
		
		global $leave;
		
		$totalLeaveBalance1 = $totalLeaveBalance1 + 1;
		
		if($dojtype=='N')
		{	
			$totalLeaveBalance1=$totalLeaveBalance1-1;	
		}
		
		if($totalLeaveBalance1==0)
		{
		$color='background-color:#ed9911;';
		$plus='';
		$plus1='';
		}	
		
		if($totalLeaveBalance1<0)
		{
		$color='background-color:#f75f54;';
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
					$color='background-color:green;';
					$plus='+';
				}
				else
				{
					$totalLeaveBalance1 = $monthsDifference;
					$totalLeaveBalance1=$totalLeaveBalance1+1;
					$color='background-color:green;';
					$plus='+';	
				}	
			}	
		} 	

		if($cutoff>0)
		{
			$color='background-color:green;';
			$totalLeaveBalance1 = ($totalLeaveBalance1 + $cutoff);

			if($totalLeaveBalance1==0)
			{
				$color='background-color:#ed9911;';
				$plus='';
				$plus1='';
			}	
			
			if($totalLeaveBalance1<0)
			{
				$color='background-color:#f75f54;';
				$plus='';
				$plus1='';
			}

			if($totalLeaveBalance1>0)
			{
				$color='background-color:green;';
				$plus='+';
				$plus1='+';
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


		$trholiday='';
		$trholiday1='';
		$trholiday2='';
		$holiday_day='';
		$holiday='';
		$leaveemp=0;
		$trholi='';
		$titleholiday='';
		$totalleaveemp1='';
		$totalleaveemp2='';
		$totalleaveemp='';
		$leaveempname='';
		$compoleave='';
		$compodate='';
		$totalcompodate1='';
		$totalcompodate2='';
		$totalcompodate='';
		$formattedcompodate='';
		$reason='N/A';
		$h=1;
		$i=1;
		$j=1;


		$sql8 = " select count(empid) as compoleave from compo where empid='$empid'";
		$res8 = sqlsrv_query($conn,$sql8);
		while($row8 = sqlsrv_fetch_array( $res8, SQLSRV_FETCH_ASSOC))
		{
			$compoleave = $row8['compoleave'];	
		}
		
		$sql9 = " select compodate from compo where empid='$empid' order by compodate";
		$res9 = sqlsrv_query($conn,$sql9);
		while($row9 = sqlsrv_fetch_array( $res9, SQLSRV_FETCH_ASSOC))
		{
			if($row9['compodate']==NULL)
			{
				$row9['compodate']='';
			}
			$compodate = trim($row9['compodate']);
			
		$date3 = DateTime::createFromFormat('Y-m-d', $compodate);
		$formattedcompodate = $date3->format('d-m-Y');
			
			if($h<$compoleave)
			{			
				$totalcompodate1 .=	$formattedcompodate.',&nbsp;';
				$h++;
			}
		}		

		$totalcompodate1 .= $formattedcompodate;

		$totalcompodate = $totalcompodate1 . $totalcompodate2;
		

		$sql = "  SELECT CAST(COUNT(holiday) AS VARCHAR(50)) AS result FROM holiday  
                  WHERE date >= GETDATE() AND date <= EOMONTH(GETDATE())";
		$res = sqlsrv_query($conn,$sql);
		while($row = sqlsrv_fetch_array( $res, SQLSRV_FETCH_ASSOC))
		{

			$result = $row['result'];	

		}
		
		$sql1 = "SELECT holiday, DAY(date) AS holiday_day FROM holiday
		         WHERE date >= CONVERT(DATE, GETDATE()) AND date <= EOMONTH(GETDATE())";
		$res1 = sqlsrv_query($conn,$sql1);
		while($row1 = sqlsrv_fetch_array( $res1, SQLSRV_FETCH_ASSOC))
		{

			$holiday_day = $row1['holiday_day'];
			
			if($row1['holiday']==NULL)
			{
				$row1['holiday']='';
			}
			$holiday = trim($row1['holiday']); 	
			
				if($i<$result)
				{
					$trholiday1 .= '&nbsp;<b>'.$holiday .'</b>&nbsp;on <b>'.$holiday_day.'th</b>,&nbsp;';
					$i++;
				}
				$titleholiday .= '&nbsp;'.$holiday .'&nbsp;on '.$holiday_day.'th,';			
		
		}
		
		

	$trholiday2 .= '&nbsp;<b>'.$holiday .'</b>&nbsp;on <b>'.$holiday_day.'th</b>';

	$trholi = $trholiday1 . $trholiday2;

	if(!$holiday)
	{
		$trholi='<b>NO PUBLIC HOLIDAYS THIS MONTH</b>';
	}	


		$sql3 = "SELECT COUNT(empid) AS leaveemp FROM leave WHERE CAST(leavedate AS DATE) = CAST(GETDATE() AS DATE)
		         AND empid NOT IN ('1118', '1119');";
		$res3 = sqlsrv_query($conn,$sql3);
		while($row3 = sqlsrv_fetch_array( $res3, SQLSRV_FETCH_ASSOC))
		{
			$leaveemp = $row3['leaveemp'];		
		}	


		$sql4 = "SELECT e.name AS leaveempname,reason FROM leave l JOIN emp e ON l.empid = e.empid 
				 WHERE CAST(l.leavedate AS DATE) = CAST(GETDATE() AS DATE) AND l.empid NOT IN ('1118', '1119');";
		$res4 = sqlsrv_query($conn,$sql4);
		while($row4 = sqlsrv_fetch_array( $res4, SQLSRV_FETCH_ASSOC))
		{
			
			$leaveempname = $row4['leaveempname'];
			$reason = $row4['reason'];	
			
			if($j<$leaveemp)
			{	
				if($reason=='')
				{
					$reason='N/A';
				}
				
				$totalleaveemp1 .= '<b>'.$leaveempname.'</b>'.'['.$reason.']'.',&nbsp;&nbsp;';	
				$j++;
			
			}	
		
		} 
		
		if($reason=='')
		{
			$reason='N/A';
		}	

		$totalleaveemp2 .= '<b>'.$leaveempname.'</b>'.'['.$reason.']';

		$totalleaveemp = $totalleaveemp1 . $totalleaveemp2;	


		if($leaveemp==0)
		{
			$totalleaveemp='<b>EVERYONE IS PRESENT TODAY</b>';
		}



	if($emptype=='EMPLOYEE' || $emptype=='TEST' || $power == 'Y')
	{
		$sql12 = "select count(empid) as empcount from emp with (nolock) where flag='Y'";
		$res12 = sqlsrv_query($conn,$sql12);
		while($row12 = sqlsrv_fetch_array( $res12, SQLSRV_FETCH_ASSOC))
		{
			$empcount=$row12['empcount'];
		}	
	?>

	<style>
#holidays 
{
	display:none;
}
#leave
{
	display:none;
}
#leavebalance 
{
	display:none;
}
	button:hover
	{
		transform: scale(1.05);	
	}
#congrats-message 
{ 
	font-size: 50px; font-weight: bold; color: #ff6347; opacity: 0; transform: scale(0.5); transition: opacity 0.5s ease, transform 0.5s ease; 
} 
#congrats-message.show 
{ 
	opacity: 1; 
	transform: scale(1); 
}


@media screen and (max-width: 768px) 
{
    .button
	{
        width: 100px;
    }

    #content 
	{
        width: calc(100% - 60px);
        left: 200px;
    }

    #content nav .nav-link 
	{
        display: none;
    }
	
}
	
	</style>


	<body class="report" bottommargin="0" topmargin="0" leftmargin="0" rightmargin="0">

	<br><br><br><br>
	<div>
	<br><br>
	<button id="experience-btn" style="background-color:#28373E;color:white;font-size:16px;width:220px;height:150px;border-radius:10px;margin-left:160px;" title="<?php echo 'You have '.strtolower($time).' of experience.';?>">

	<div style="justify-content: space-around;
		display: flex;
		align-items: center;
		height: 71px;">
	<?php echo "<b><p style='font-size:25px;font-family:poppins;'>$time</p></b>"; ?>
	</div>

	<div>
	<span style="font-family:poppins;">
	<p>Experience</p>
	</span>
	</div>

	</button>


	<button style="background-color:#28373E;color:white;font-size:16px;width:220px;height:150px;border-radius:10px;margin-left:70px;" id="leaveemp"
	title="<?php if($leaveemp==0){echo 'Everyone is present today';}elseif($leaveemp==1){echo '1 Employee is on leave today';}else{echo $leaveemp.' Employees are on leave today';}?>">
		<div style="justify-content: space-around; display: flex; align-items: center; height: 71px;">
			<?php echo "<b><p style='font-size:40px;font-family:poppins;'>$leaveemp</p></b>"; ?>
			<img src="../leave/images/grpicon.png" style="width:60px;height:60px;">
		</div>
		<div>
			<span style="font-family:poppins;">
				<p>Leave Today</p>
			</span>
		</div>
	</button>

	<button style="background-color:#28373E;color:white;font-size:16px;width:220px;height:150px;border-radius:10px;margin-left:70px;" id='showHoliday'
	title="<?php echo $titleholiday ;?>">
		<div style="justify-content: space-around; display: flex; align-items: center; height: 71px;">
			<?php echo "<b><p style='font-size:40px;font-family:poppins;'>$result</p></b>"; ?>
			<img src="../leave/images/holiday.png" style="width:60px;height:60px;">
		</div>
		<div>
			<span style="font-family:poppins;">
				<p>Holidays</p>
			</span>
		</div>
	</button>



	<button style="background-color:#28373E;color:white;font-size:16px;width:220px;height:150px;border-radius:10px;margin-left:70px;"
		title="<?php 
		if($totalLeaveBalance1==1)
		{echo 'Your leave balance is running low. Please plan accordingly!' ;}
		elseif($totalLeaveBalance1<2)
		{echo 'Your leave balance is running low. You currently have no casual leaves left. Please plan accordingly!' ;}
		else
		{echo 'Your casual leave balance is running strong.';}
		if($compoleave)
		{
			echo ' Your Compo leave balance is '.$compoleave.' Compensatory Dates - '.$totalcompodate;
		}
		else
		{
			echo ' You have no Compo leaves left';
		}
		?>" id='currentleavebalance'>

		<div style="justify-content: space-around;
			display: flex;
			align-items: center;
			height: 71px;">
			<?php echo "<b><p style='font-size:40px;font-family:poppins;'>$plus$totalLeaveBalance1</p></b>"; ?>
			<img src="../leave/images/balance.png" style="width:60px;height:60px;">
		</div>

		<div>
			<span style="font-family:poppins;">
				<p>Current Leave Balance</p>
			</span>
		</div>

	</button>

<!--<div id="congrats-message">Congratulations!</div>-->

	<?php
	}
		
	
	
	?>



	</div>

	<br>
	<p id='leave' style="margin-left:160px;font-size:18px;font-family:poppins;">Leave Today - <i><?php echo $totalleaveemp; ?></i></p>
	<p id='holidays' style="margin-left:160px;font-size:18px;font-family:poppins;">Public Holidays this month - <i><?php echo $trholi; ?></i></p>
	<p id='leavebalance' style="margin-left:160px;font-size:18px;font-family:poppins;">
	<i>
	<?php 
	if($totalLeaveBalance1==1)
		{echo 'Your leave balance is running low. Please plan accordingly!' ;} 
	elseif($totalLeaveBalance1<1)
		{echo 'Your <b>leave balance is running low.</b><br>You currently have <b>no casual leaves left</b>. Your current leave balance is <b>'.$totalLeaveBalance1.'</b>.  Please plan accordingly!' ;}
	else
		{echo 'Your casual leave balance is running strong. Your current leave balance is <b>+'.$totalLeaveBalance1.'</b>';}
	if($compoleave)
	{
		echo '<br>Your Compo leave balance is <b>'.$compoleave.'</b><br>Compensatory Dates - <b>'.$totalcompodate;
	}
	else
	{
		echo '<br>You have no Compo leaves left';
	}	
	?>
	</i>
	</p>

	<br><br>
	<div>
		<div>
			<span style="font-family:poppins;">
				<h2 style="margin-left:165px;font-size:25px;font-family:poppins;">
				<b>
					<?php echo $name.",&nbsp;".$age."<br>"; ?>
					<i>
					<span style="font-size:18px;"><?php echo $designation."<br>"; ?></span>
					</i>	
					<span style="font-size:18px;display:inline-block;width:85%;border-bottom:2px solid black;margin-top:10px;margin-bottom:10px;"></span>
				</b>
				</h2>
			</span>
		</div>
	</div>


<style>
#notification 
{
    display: none;
    position: fixed;
    top: 100px;
    right: 10px;
    background-color: #ffeb3b;
    color: #333;
    padding: 10px;
    border-radius: 5px;
}
</style>


			<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>


			<script>
						
			function goBack() 
			{
				history.go(-1);
			}



let birthdayTriggered = false;


document.addEventListener('mousemove', function () 
{
    if (birthdayTriggered) return;

    const currentDate = new Date();
    const currentMonthDay = (currentDate.getMonth() + 1).toString().padStart(2, '0') + '-' + currentDate.getDate().toString().padStart(2, '0');
    const dob = "<?php echo date('m-d', strtotime($dob)); ?>"; 

    if (currentMonthDay === dob) 
	{
        const currentDay = currentDate.getDay();

        if (currentDay === 6 || currentDay === 0) 
		{
            const daysUntilMonday = (1 - currentDay + 7) % 7; 
            const monday = new Date(currentDate);
            monday.setDate(currentDate.getDate() + daysUntilMonday);

            setTimeout(function () 
			{
                showBirthdayMessage();
            }, monday.getTime() - Date.now());
        } 
		else 
		{
            showBirthdayMessage(); // Show wish immediately
        }

        birthdayTriggered = true;
        localStorage.setItem('birthdayWishGiven', 'true'); // Mark birthday wish as shown
    }
	
});



function showBirthdayMessage() 
{
  
    const birthdayMessage = document.createElement('div');
    birthdayMessage.innerHTML = "🎉 Happy Birthday! 🎉<br>Wishing you many happy returns of the day!";
    birthdayMessage.style.position = 'fixed';
    birthdayMessage.style.top = '50%';
    birthdayMessage.style.left = '50%';
    birthdayMessage.style.transform = 'translate(-50%, -50%)';
    birthdayMessage.style.padding = '20px';
    birthdayMessage.style.backgroundColor = '#f9f9f9';
    birthdayMessage.style.borderRadius = '10px';
    birthdayMessage.style.boxShadow = '0 0 15px rgba(0, 0, 0, 0.1)';
    birthdayMessage.style.textAlign = 'center';
    birthdayMessage.style.fontSize = '24px';
    birthdayMessage.style.fontFamily = 'Arial, sans-serif';
    birthdayMessage.style.color = '#28373E';
    birthdayMessage.style.zIndex = '9999';
    birthdayMessage.style.opacity = '0';
    birthdayMessage.style.transition = 'opacity 1.5s ease-in-out';

    document.body.appendChild(birthdayMessage);

    setTimeout(function () 
	{
        birthdayMessage.style.opacity = '1';
    }, 100);

    const duration = 6 * 1000; // 6 seconds for confetti celebration
    const animationEnd = Date.now() + duration;
    const defaults = 
	{
        startVelocity: 30,
        spread: 360,
        ticks: 60,
        zIndex: 0
    };

    function randomInRange(min, max) 
	{
        return Math.random() * (max - min) + min;
    }

    const interval = setInterval(function () 
	{
        const timeLeft = animationEnd - Date.now();
        if (timeLeft <= 0) 
		{
            clearInterval(interval);
            birthdayMessage.style.opacity = '0';
            setTimeout(function () 
			{
                birthdayMessage.remove();
            }, 500);
            return;
        }

        const particleCount = 50 * (timeLeft / duration);

        confetti(Object.assign({}, defaults, 
		{
            particleCount,
            origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 }
        }));

        confetti(Object.assign({}, defaults, 
		{
            particleCount,
            origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 }
        }));
    }, 250);
	
}


// Check if the employee has missed the birthday wish (on the next day login)
document.addEventListener('DOMContentLoaded', function() 
{
    const storedBirthday = localStorage.getItem('birthdayWishGiven');
    if (!storedBirthday)
	{
        const currentDate = new Date();
        const currentMonthDay = (currentDate.getMonth() + 1).toString().padStart(2, '0') + '-' + currentDate.getDate().toString().padStart(2, '0');
        const dob = "<?php echo date('m-d', strtotime($dob)); ?>"; 

        if (currentMonthDay === dob) 
		{
            showBirthdayMessage();
            localStorage.setItem('birthdayWishGiven', 'true');
        } 
		else 
		{
            const previousDate = new Date(currentDate);
            previousDate.setDate(currentDate.getDate() - 1);
            const previousMonthDay = (previousDate.getMonth() + 1).toString().padStart(2, '0') + '-' + previousDate.getDate().toString().padStart(2, '0');

            if (previousMonthDay === dob) 
			{

                const previousDay = previousDate.getDay();
                if (previousDay === 6 || previousDay === 0) 
				{

                    const daysUntilMonday = (1 - previousDay + 7) % 7;
                    previousDate.setDate(previousDate.getDate() + daysUntilMonday);
                    setTimeout(function() 
					{
                        showBirthdayMessage();
                    }, previousDate.getTime() - Date.now());
                } 
				else 
				{
                    showBirthdayMessage();
                }
            }
        }
    }
});


<?php

if ($time != '1 YEAR') 
{
    $time1 = substr($time, 0, 1); 
} 
else 
{
    $time1 = '1'; 
}


		$sql16 = "select * from emp with (nolock) where flag='Y' and empid='$empid'";
		$res16 = sqlsrv_query($conn,$sql16);
		while($row16 = sqlsrv_fetch_array( $res16, SQLSRV_FETCH_ASSOC))
		{
			if($row16['doj']==NULL)
			{
				$row16['doj']='';
			}
			$doj = trim($row16['doj']); 
		}	


$today = date('Y-m-d'); // Current date
$anniversaryFlag = (date('m-d', strtotime($doj)) === date('m-d')) ? 'true' : 'false';
$time1 = date('Y') - date('Y', strtotime($doj)); // Years of service


?>




let congratulateTriggered = false;
const time1 = "<?php echo $time1; ?>"; // Years of service
const anniversaryFlag = "<?php echo $anniversaryFlag; ?>"; // Check if today is the anniversary
const doj = new Date("<?php echo $doj; ?>"); // Employee's date of joining
const today = new Date(); // Today's date


function isAnniversary(doj, today) 
{
    return doj.getDate() === today.getDate() && doj.getMonth() === today.getMonth();
}

document.addEventListener('mousemove', function () 
{
    if (congratulateTriggered) return;

    if (anniversaryFlag === 'true' && isAnniversary(doj, today)) 
	{
        congratulateTriggered = true;
        const congratsMessage = document.createElement('div');
        congratsMessage.innerHTML = `🎉 Congratulations on your ${time1 === '1' ? "one-year" : `${time1}-year`} Anniversary! 🎉<br>Well done on achieving this milestone!`;

        congratsMessage.style.position = 'fixed';
        congratsMessage.style.top = '50%';
        congratsMessage.style.left = '50%';
        congratsMessage.style.transform = 'translate(-50%, -50%)';
        congratsMessage.style.padding = '20px';
        congratsMessage.style.backgroundColor = '#f9f9f9';
        congratsMessage.style.borderRadius = '10px';
        congratsMessage.style.boxShadow = '0 0 15px rgba(0, 0, 0, 0.1)';
        congratsMessage.style.textAlign = 'center';
        congratsMessage.style.fontSize = '24px';
        congratsMessage.style.fontFamily = 'Arial, sans-serif';
        congratsMessage.style.color = '#28373E';
        congratsMessage.style.zIndex = '9999';
        congratsMessage.style.opacity = '0';
        congratsMessage.style.transition = 'opacity 1.5s ease-in-out';

        document.body.appendChild(congratsMessage);

        setTimeout(function () 
		{
            congratsMessage.style.opacity = '1';
        }, 100);

        // Confetti animation logic
        const duration = 6 * 1000;
        const animationEnd = Date.now() + duration;
        const defaults = {
            startVelocity: 30,
            spread: 360,
            ticks: 60,
            zIndex: 0,
        };

        function randomInRange(min, max) 
		{
            return Math.random() * (max - min) + min;
        }

        const interval = setInterval(function () 
		{
            const timeLeft = animationEnd - Date.now();
            if (timeLeft <= 0) 
			{
                clearInterval(interval);
                congratsMessage.style.opacity = '0';
                setTimeout(function () 
				{
                    congratsMessage.remove();
                }, 500);
                return;
            }

            const particleCount = 50 * (timeLeft / duration);

            confetti(Object.assign({}, defaults, 
			{
                particleCount,
                origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 },
            }));

            confetti(Object.assign({}, defaults, 
			{
                particleCount,
                origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 },
            }));
        }, 250);
    }
	
});








const congratulate = <?php echo json_encode($congratulate); ?>;


document.getElementById('experience-btn').addEventListener('click', function() 
{
    var time = "<?php echo strtolower($time); ?>";
    if (congratulate) 
	{
        const congratsMessage = document.createElement('div');
        if (time1 == '1') 
		{
            congratsMessage.innerHTML = "🎉 Congratulations on your one year! 🎉<br>Well done on achieving this milestone!";
        } 
		else if (time1 == '2') 
		{
            congratsMessage.innerHTML = "🎉 Congratulations on your two year Anniversary! 🎉<br>Well done on achieving this milestone!";
        } 
		else if (time1 == '3') 
		{
            congratsMessage.innerHTML = "🎉 Congratulations on your three year Anniversary! 🎉<br>Well done on achieving this milestone!";
        } 
		else if (time1 == '4') 
		{
            congratsMessage.innerHTML = "🎉 Congratulations on your four year Anniversary! 🎉<br>Well done on achieving this milestone!";
        } 
		else if (time1 == '5') 
		{
            congratsMessage.innerHTML = "🎉 Congratulations on your five year Anniversary! 🎉<br>Well done on achieving this milestone!";
        } 
		else if (time1 == '6') 
		{
            congratsMessage.innerHTML = "🎉 Congratulations on your six year Anniversary! 🎉<br>Well done on achieving this milestone!";
        } 
		else if (time1 == '7') 
		{
            congratsMessage.innerHTML = "🎉 Congratulations on your seven year Anniversary! 🎉<br>Well done on achieving this milestone!";
        } 
		else if (time1 == '8') 
		{
            congratsMessage.innerHTML = "🎉 Congratulations on your eight year Anniversary! 🎉<br>Well done on achieving this milestone!";
        }
		else if (time1 == '9') 
		{
            congratsMessage.innerHTML = "🎉 Congratulations on your nine year Anniversary! 🎉<br>Well done on achieving this milestone!";
        } 
		else if (time1 == '10') 
		{
            congratsMessage.innerHTML = "🎉 Congratulations on your ten year Anniversary! 🎉<br>Well done on achieving this milestone!";
        } 
		
        congratsMessage.style.position = 'fixed';
        congratsMessage.style.top = '50%';
        congratsMessage.style.left = '50%';
        congratsMessage.style.transform = 'translate(-50%, -50%)';
        congratsMessage.style.padding = '20px';
        congratsMessage.style.backgroundColor = '#f9f9f9';
        congratsMessage.style.borderRadius = '10px';
        congratsMessage.style.boxShadow = '0 0 15px rgba(0, 0, 0, 0.1)';
        congratsMessage.style.textAlign = 'center';
        congratsMessage.style.fontSize = '24px';
        congratsMessage.style.fontFamily = 'Arial, sans-serif';
        congratsMessage.style.color = '#28373E';
        congratsMessage.style.zIndex = '9999';
        congratsMessage.style.opacity = '0';
        congratsMessage.style.transition = 'opacity 1.5s ease-in-out';
        
        document.body.appendChild(congratsMessage);

        setTimeout(function() 
		{
            congratsMessage.style.opacity = '1';
        }, 100);

        const duration = 7 * 1000;
        const animationEnd = Date.now() + duration;
        const defaults = 
		{
            startVelocity: 30,
            spread: 360,
            ticks: 60,
            zIndex: 0
        };

        function randomInRange(min, max) 
		{
            return Math.random() * (max - min) + min;
        }

        const interval = setInterval(function() 
		{
            const timeLeft = animationEnd - Date.now();
            if (timeLeft <= 0) 
			{
                clearInterval(interval);
                congratsMessage.style.opacity = '0'; 
                setTimeout(function() 
				{
                    congratsMessage.remove();
                }, 500);
                return;
            }

            const particleCount = 50 * (timeLeft / duration); 

            confetti(Object.assign({}, defaults,			
			{
                particleCount,
                origin: 
				{
                    x: randomInRange(0.1, 0.3), 
                    y: Math.random() - 0.2 
                }
            }));

            confetti(Object.assign({}, defaults, 
			{
                particleCount,
                origin: 
				{
                    x: randomInRange(0.7, 0.9), 
                    y: Math.random() - 0.2 
                }
            }));
        }, 250); 
    } 
	else 
	{
        //alert(`You have ${time} of experience!`); 
    }
});




			document.getElementById('leaveemp').addEventListener('click', function() 
			{
				var holidayParagraph = document.getElementById('leave');
				if (holidayParagraph.style.display === 'none' || holidayParagraph.style.display === '') 
				{
					holidayParagraph.style.display = 'block'; 
				} 
				else 
				{
					holidayParagraph.style.display = 'none'; 
				}
			});
			
			document.getElementById('showHoliday').addEventListener('click', function() 
			{
				var holidayParagraph = document.getElementById('holidays');
				if (holidayParagraph.style.display === 'none' || holidayParagraph.style.display === '') 
				{
					holidayParagraph.style.display = 'block'; 
				} 
				else 
				{
					holidayParagraph.style.display = 'none'; 
				}
			});
			
			
			document.getElementById('currentleavebalance').addEventListener('click', function() 
			{
				var holidayParagraph = document.getElementById('leavebalance');
				if (holidayParagraph.style.display === 'none' || holidayParagraph.style.display === '') 
				{
					holidayParagraph.style.display = 'block';
				} 
				else 
				{
					holidayParagraph.style.display = 'none';
				}
			});
			</script>	

<?php
		}		
	
	}		
	
}

elseif($flag == 'Y' && $emptype == 'TEST')//Test Employee Dashboard
{

	date_default_timezone_set("Asia/Kolkata");

	if(date("h:i:sa") > '05:50:00' && date("h:i:sa") < '05:58:00')
	{
		?>	

		<body class="report" bottommargin="0" topmargin="0" leftmargin="0" rightmargin="0">	

		<center>
		<br>
		<img src="../leave/images/BugFixing.png" style="width:600px;height:600px;">
		<pre>
		System Maintenance from 05:00 to 06:00 IST
		Sorry for your inconvinience.
		</pre>
		</center>

		
		<script>
			function loadContent(url) 
			{
				document.getElementById('contentFrame').src = url;
			}

			const sidebar = document.getElementById('sidebar3');
			const content = document.getElementById('content');

			sidebar.addEventListener('mouseover', () => 
			{
				content.style.marginLeft = '260px';
			});

			sidebar.addEventListener('mouseout', () => 
			{
				content.style.marginLeft = '85px';
			});

			// Add event listeners to sidebar links
			const sidebarLinks = document.querySelectorAll('.sidebar3-links li a');
			sidebarLinks.forEach(link => 
			{
				
				link.addEventListener('click', function(e) 
				{
					e.preventDefault(); // Prevent default link behavior

					// Remove active class from all links
					sidebarLinks.forEach(link => link.classList.remove('active'));

					// Add active class to the clicked link
					this.classList.add('active');

					const href = this.getAttribute('href');
					loadContent(href); // Load content into iframe
				});
			});

			function logoutAndRedirect(event) 
			{
				event.preventDefault();
				window.top.location.href = 'logout.php';
			}
			
			
			function goBack() 
			{
			  history.back();
			}	
			
		</script>


		<?php
		exit;
	}
	else //Employee Dashboard
	{	

		global $leave;
		$sql5 = "select leavedate,empid from leave with (nolock) where empid='$empid'";
		$res5 = sqlsrv_query($conn,$sql5);
		while($row5 = sqlsrv_fetch_array( $res5, SQLSRV_FETCH_ASSOC))
		{
			
			global $mm,$time,$yy;
			if($row5['leavedate']==NULL)
			{
				$row5['leavedate']='';
			} 	
			$leavedate = trim($row5['leavedate']);
					
			if($row5['empid']==NULL)
			{
				$row5['empid']='';
			}
			$empid = trim($row5['empid']);

		}
		
		$sql2 = "select * from emp with (nolock) where flag='Y' and empid='$empid'";
		$res2 = sqlsrv_query($conn,$sql2);
		while($row2 = sqlsrv_fetch_array( $res2, SQLSRV_FETCH_ASSOC))
		{

			if($row2['name']==NULL)
			{
				$row2['name']='';
			} 
			$name = trim($row2['name']); 

			if($row2['dob']==NULL)
			{
				$row2['dob']='';
			}
			$dob = trim($row2['dob']); 

			if($row2['doj']==NULL)
			{
				$row2['doj']='';
			}
			$doj = trim($row2['doj']); 
			
			if($row2['cutoff']==NULL)
			{
				$row2['cutoff']='';
			}
			$cutoff = trim($row2['cutoff']);		

			if($row2['address']==NULL)
			{
				$row2['address']='';
			}
			$address = trim($row2['address']); 

			if($row2['gender']==NULL)
			{
				$row2['gender']='';
			}
			$gender = trim($row2['gender']); 

			if($row2['email']==NULL)
			{
				$row2['email']='';
			}
			$email = trim($row2['email']); 

			if($row2['mob']==NULL)
			{
				$row2['mob']='';
			}
			$mob = trim($row2['mob']); 

			if($row2['designation']==NULL)
			{
				$row2['designation']='';
			}
			$designation = trim($row2['designation']); 

			if($row2['dojtype']==NULL)
			{
				$row2['dojtype']='';
			}
			$dojtype = trim($row2['dojtype']); 

			if($row2['empid']==NULL)
			{
				$row2['empid']='';
			}
			$empid = trim($row2['empid']);
			
			if($row2['emptype']==NULL)
			{
				$row2['emptype']='';
			}
			$emptype  =  trim($row2['emptype']);
			
			if($row2['power']==NULL)
			{
				$row2['power']='';
			}
			$power  =  trim($row2['power']);			
			
			
			$dobDate = new DateTime($dob);
			$currentDate = new DateTime();
			$ageInterval = $currentDate->diff($dobDate);
			$age = $ageInterval->y;
			
			$date2 = DateTime::createFromFormat('Y-m-d', $doj);
			$formattedDoj = $date2->format('d-m-Y');

		$doj = new DateTime($doj);
		$currentDate = new DateTime(); 
		$interval = $doj->diff($currentDate);
		$monthsDifference = ($interval->y * 12) + $interval->m;
		//$yearsWorked = $interval->y + ($interval->m > 0 ? 1 : 0);
		
		$yy=0;
		$mm=0;
		$time=0;
		$congratulate=false;
		
		if( $monthsDifference == 0 )
		{
			$time = "LESS THAN 1 MONTH";
		}
		elseif( $monthsDifference == 1 )
		{
			$time = "1 MONTH";
		}	
		elseif( $monthsDifference > 1 && $monthsDifference < 12 )
		{
			$mm = $monthsDifference;
			$time = $mm." MONTHS";
		}	
		else
		{
			$mm = $monthsDifference % 12;
			$yy = $monthsDifference / 12;
			if((int)$yy == 1 && $mm == 0 )
			{
				$time = (int)$yy." YEAR";
				$congratulate = true; 				
			}
			elseif((int)$yy == 2 && $mm == 0  || (int)$yy == 3 && $mm == 0 || (int)$yy == 4 && $mm == 0 || (int)$yy == 5 && $mm == 0 || (int)$yy == 6 && $mm == 0 || (int)$yy == 7 && $mm == 0  || (int)$yy == 8 && $mm == 0  || (int)$yy == 9 && $mm == 0  || (int)$yy == 10 && $mm == 0)
			{
				$time = (int)$yy." YEARS";
				$congratulate = true; 
			}
			elseif((int)$yy == 1 && $mm == 1)
			{
				$time = (int)$yy." YEAR AND ".$mm." MONTH";
			}
			elseif((int)$yy == 2 && $mm == 1 || (int)$yy == 3 && $mm == 1 || (int)$yy == 4 && $mm == 1 || (int)$yy == 5 && $mm == 1 || (int)$yy == 6 && $mm == 1 || (int)$yy == 7 && $mm == 1 || (int)$yy == 8 && $mm == 1 || (int)$yy == 9 && $mm == 1 || (int)$yy == 10 && $mm == 1)
			{
				$time = (int)$yy." YEARS AND ".$mm." MONTH";
			}		
			elseif((int)$yy == 1 )
			{
				$time = (int)$yy." YEAR AND ".$mm." MONTHS";
			}
			else
			{
				$time = (int)$yy." YEARS AND ".$mm." MONTHS";
			}	
		
		}


		$totalLeaveBalance1 = getLeavesTakenPerMonth($empid);
			
		$color='background-color:green;';
		$plus='+';
		$plus1='%2B';
		
		global $leave;
		
		$totalLeaveBalance1 = $totalLeaveBalance1 + 1;
		
		if($dojtype=='N')
		{	
			$totalLeaveBalance1=$totalLeaveBalance1-1;	
		}
		
		if($totalLeaveBalance1==0)
		{
		$color='background-color:#ed9911;';
		$plus='';
		$plus1='';
		}	
		
		if($totalLeaveBalance1<0)
		{
		$color='background-color:#f75f54;';
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
					$color='background-color:green;';
					$plus='+';
				}
				else
				{
					$totalLeaveBalance1 = $monthsDifference;
					$totalLeaveBalance1=$totalLeaveBalance1+1;
					$color='background-color:green;';
					$plus='+';	
				}	
			}	
		} 	

		if($cutoff>0)
		{
			$color='background-color:green;';
			$totalLeaveBalance1 = ($totalLeaveBalance1 + $cutoff);

			if($totalLeaveBalance1==0)
			{
				$color='background-color:#ed9911;';
				$plus='';
				$plus1='';
			}	
			
			if($totalLeaveBalance1<0)
			{
				$color='background-color:#f75f54;';
				$plus='';
				$plus1='';
			}

			if($totalLeaveBalance1>0)
			{
				$color='background-color:green;';
				$plus='+';
				$plus1='+';
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


		$trholiday='';
		$trholiday1='';
		$trholiday2='';
		$holiday_day='';
		$holiday='';
		$leaveemp=0;
		$trholi='';
		$titleholiday='';
		$totalleaveemp1='';
		$totalleaveemp2='';
		$totalleaveemp='';
		$leaveempname='';
		$compoleave='';
		$compodate='';
		$totalcompodate1='';
		$totalcompodate2='';
		$totalcompodate='';
		$formattedcompodate='';
		$reason='N/A';
		$h=1;
		$i=1;
		$j=1;


		$sql8 = " select count(empid) as compoleave from compo where empid='$empid'";
		$res8 = sqlsrv_query($conn,$sql8);
		while($row8 = sqlsrv_fetch_array( $res8, SQLSRV_FETCH_ASSOC))
		{
			$compoleave = $row8['compoleave'];	
		}
		
		$sql9 = " select compodate from compo where empid='$empid' order by compodate";
		$res9 = sqlsrv_query($conn,$sql9);
		while($row9 = sqlsrv_fetch_array( $res9, SQLSRV_FETCH_ASSOC))
		{
			if($row9['compodate']==NULL)
			{
				$row9['compodate']='';
			}
			$compodate = trim($row9['compodate']);
			
		$date3 = DateTime::createFromFormat('Y-m-d', $compodate);
		$formattedcompodate = $date3->format('d-m-Y');
			
			if($h<$compoleave)
			{			
				$totalcompodate1 .=	$formattedcompodate.',&nbsp;';
				$h++;
			}
		}		

		$totalcompodate1 .= $formattedcompodate;

		$totalcompodate = $totalcompodate1 . $totalcompodate2;
		

		$sql = "  SELECT CAST(COUNT(holiday) AS VARCHAR(50)) AS result FROM holiday  
                  WHERE date >= GETDATE() AND date <= EOMONTH(GETDATE())";
		$res = sqlsrv_query($conn,$sql);
		while($row = sqlsrv_fetch_array( $res, SQLSRV_FETCH_ASSOC))
		{

			$result = $row['result'];	

		}
		
		$sql1 = "SELECT holiday, DAY(date) AS holiday_day FROM holiday
		         WHERE date >= CONVERT(DATE, GETDATE()) AND date <= EOMONTH(GETDATE())";
		$res1 = sqlsrv_query($conn,$sql1);
		while($row1 = sqlsrv_fetch_array( $res1, SQLSRV_FETCH_ASSOC))
		{

			$holiday_day = $row1['holiday_day'];
			
			if($row1['holiday']==NULL)
			{
				$row1['holiday']='';
			}
			$holiday = trim($row1['holiday']); 	
			
				if($i<$result)
				{
					$trholiday1 .= '&nbsp;<b>'.$holiday .'</b>&nbsp;on <b>'.$holiday_day.'th</b>,&nbsp;';
					$i++;
				}
				$titleholiday .= '&nbsp;'.$holiday .'&nbsp;on '.$holiday_day.'th,';			
		
		}
		
		

	$trholiday2 .= '&nbsp;<b>'.$holiday .'</b>&nbsp;on <b>'.$holiday_day.'th</b>';

	$trholi = $trholiday1 . $trholiday2;

	if(!$holiday)
	{
		$trholi='<b>NO PUBLIC HOLIDAYS THIS MONTH</b>';
	}	


		$sql3 = "SELECT COUNT(empid) AS leaveemp FROM leave WHERE CAST(leavedate AS DATE) = CAST(GETDATE() AS DATE)
		         AND empid NOT IN ('1118', '1119');";
		$res3 = sqlsrv_query($conn,$sql3);
		while($row3 = sqlsrv_fetch_array( $res3, SQLSRV_FETCH_ASSOC))
		{
			$leaveemp = $row3['leaveemp'];		
		}	


		$sql4 = "SELECT e.name AS leaveempname,reason FROM leave l JOIN emp e ON l.empid = e.empid 
				 WHERE CAST(l.leavedate AS DATE) = CAST(GETDATE() AS DATE) AND l.empid NOT IN ('1118', '1119');";
		$res4 = sqlsrv_query($conn,$sql4);
		while($row4 = sqlsrv_fetch_array( $res4, SQLSRV_FETCH_ASSOC))
		{
			
			$leaveempname = $row4['leaveempname'];
			$reason = $row4['reason'];	
			
			if($j<$leaveemp)
			{	
				if($reason=='')
				{
					$reason='N/A';
				}
				
				$totalleaveemp1 .= '<b>'.$leaveempname.'</b>'.'['.$reason.']'.',&nbsp;&nbsp;';	
				$j++;
			
			}	
		
		} 
		
		if($reason=='')
		{
			$reason='N/A';
		}	

		$totalleaveemp2 .= '<b>'.$leaveempname.'</b>'.'['.$reason.']';

		$totalleaveemp = $totalleaveemp1 . $totalleaveemp2;	


		if($leaveemp==0)
		{
			$totalleaveemp='<b>EVERYONE IS PRESENT TODAY</b>';
		}



	if($emptype=='EMPLOYEE' || $emptype=='TEST' || $power == 'Y')
	{
		$sql12 = "select count(empid) as empcount from emp with (nolock) where flag='Y'";
		$res12 = sqlsrv_query($conn,$sql12);
		while($row12 = sqlsrv_fetch_array( $res12, SQLSRV_FETCH_ASSOC))
		{
			$empcount=$row12['empcount'];
		}	
	?>

	<style>
#holidays 
{
	display:none;
}
#leave
{
	display:none;
}
#leavebalance 
{
	display:none;
}
	button:hover
	{
		transform: scale(1.05);	
	}
#congrats-message 
{ 
	font-size: 50px; font-weight: bold; color: #ff6347; opacity: 0; transform: scale(0.5); transition: opacity 0.5s ease, transform 0.5s ease; 
} 
#congrats-message.show 
{ 
	opacity: 1; 
	transform: scale(1); 
}


@media screen and (max-width: 768px) 
{
    .button
	{
        width: 100px;
    }

    #content 
	{
        width: calc(100% - 60px);
        left: 200px;
    }

    #content nav .nav-link 
	{
        display: none;
    }
	
}
	
	</style>


	<body class="report" bottommargin="0" topmargin="0" leftmargin="0" rightmargin="0">

	<br><br><br><br>
	<div>
	<br><br>
	<button id="experience-btn" style="background-color:#162913;color:white;font-size:16px;width:220px;height:150px;border-radius:10px;margin-left:160px;" title="<?php echo 'You have '.strtolower($time).' of experience.';?>">

	<div style="justify-content: space-around;
		display: flex;
		align-items: center;
		height: 71px;">
	<?php echo "<b><p style='font-size:25px;font-family:poppins;'>$time</p></b>"; ?>
	</div>

	<div>
	<span style="font-family:poppins;">
	<p>Experience</p>
	</span>
	</div>

	</button>


	<button style="background-color:#162913;color:white;font-size:16px;width:220px;height:150px;border-radius:10px;margin-left:70px;" id="leaveemp"
	title="<?php if($leaveemp==0){echo 'Everyone is present today';}elseif($leaveemp==1){echo '1 Employee is on leave today';}else{echo $leaveemp.' Employees are on leave today';}?>">
		<div style="justify-content: space-around; display: flex; align-items: center; height: 71px;">
			<?php echo "<b><p style='font-size:40px;font-family:poppins;'>$leaveemp</p></b>"; ?>
			<img src="../leave/images/grpicon.png" style="width:60px;height:60px;">
		</div>
		<div>
			<span style="font-family:poppins;">
				<p>Leave Today</p>
			</span>
		</div>
	</button>

	<button style="background-color:#162913;color:white;font-size:16px;width:220px;height:150px;border-radius:10px;margin-left:70px;" id='showHoliday'
	title="<?php echo $titleholiday ;?>">
		<div style="justify-content: space-around; display: flex; align-items: center; height: 71px;">
			<?php echo "<b><p style='font-size:40px;font-family:poppins;'>$result</p></b>"; ?>
			<img src="../leave/images/holiday.png" style="width:60px;height:60px;">
		</div>
		<div>
			<span style="font-family:poppins;">
				<p>Holidays</p>
			</span>
		</div>
	</button>



	<button style="background-color:#162913;color:white;font-size:16px;width:220px;height:150px;border-radius:10px;margin-left:70px;"
		title="<?php 
		if($totalLeaveBalance1==1)
		{echo 'Your leave balance is running low. Please plan accordingly!' ;}
		elseif($totalLeaveBalance1<2)
		{echo 'Your leave balance is running low. You currently have no casual leaves left. Please plan accordingly!' ;}
		else
		{echo 'Your casual leave balance is running strong.';}
		if($compoleave)
		{
			echo ' Your Compo leave balance is '.$compoleave.' Compensatory Dates - '.$totalcompodate;
		}
		else
		{
			echo ' You have no Compo leaves left';
		}
		?>" id='currentleavebalance'>

		<div style="justify-content: space-around;
			display: flex;
			align-items: center;
			height: 71px;">
			<?php echo "<b><p style='font-size:40px;font-family:poppins;'>$plus$totalLeaveBalance1</p></b>"; ?>
			<img src="../leave/images/balance.png" style="width:60px;height:60px;">
		</div>

		<div>
			<span style="font-family:poppins;">
				<p>Current Leave Balance</p>
			</span>
		</div>

	</button>

<!--<div id="congrats-message">Congratulations!</div>-->

	<?php
	}
		
	
	
	?>



	</div>

	<br>
	<p id='leave' style="margin-left:160px;font-size:18px;font-family:poppins;">Leave Today - <i><?php echo $totalleaveemp; ?></i></p>
	<p id='holidays' style="margin-left:160px;font-size:18px;font-family:poppins;">Public Holidays this month - <i><?php echo $trholi; ?></i></p>
	<p id='leavebalance' style="margin-left:160px;font-size:18px;font-family:poppins;">
	<i>
	<?php 
	if($totalLeaveBalance1==1)
		{echo 'Your leave balance is running low. Please plan accordingly!' ;} 
	elseif($totalLeaveBalance1<1)
		{echo 'Your <b>leave balance is running low.</b><br>You currently have <b>no casual leaves left</b>. Your current leave balance is <b>'.$totalLeaveBalance1.'</b>.  Please plan accordingly!' ;}
	else
		{echo 'Your casual leave balance is running strong. Your current leave balance is <b>+'.$totalLeaveBalance1.'</b>';}
	if($compoleave)
	{
		echo '<br>Your Compo leave balance is <b>'.$compoleave.'</b><br>Compensatory Dates - <b>'.$totalcompodate;
	}
	else
	{
		echo '<br>You have no Compo leaves left';
	}	
	?>
	</i>
	</p>

	<br><br>
	<div>
		<div>
			<span style="font-family:poppins;">
				<h2 style="margin-left:165px;font-size:25px;font-family:poppins;">
				<b>
					<?php echo $name.",&nbsp;".$age."<br>"; ?>
					<i>
					<span style="font-size:18px;"><?php echo $designation."<br>"; ?></span>
					</i>	
					<span style="font-size:18px;display:inline-block;width:85%;border-bottom:2px solid black;margin-top:10px;margin-bottom:10px;"></span>
				</b>
				</h2>
			</span>
		</div>
	</div>


<style>
#notification 
{
    display: none;
    position: fixed;
    top: 100px;
    right: 10px;
    background-color: #ffeb3b;
    color: #333;
    padding: 10px;
    border-radius: 5px;
}
</style>


			<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>


			<script>
						
			function goBack() 
			{
				history.go(-1);
			}



let birthdayTriggered = false;


document.addEventListener('mousemove', function () 
{
    if (birthdayTriggered) return;

    const currentDate = new Date();
    const currentMonthDay = (currentDate.getMonth() + 1).toString().padStart(2, '0') + '-' + currentDate.getDate().toString().padStart(2, '0');
    const dob = "<?php echo date('m-d', strtotime($dob)); ?>"; 

    if (currentMonthDay === dob) {
        const currentDay = currentDate.getDay();

        if (currentDay === 6 || currentDay === 0) 
		{
            const daysUntilMonday = (1 - currentDay + 7) % 7; 
            const monday = new Date(currentDate);
            monday.setDate(currentDate.getDate() + daysUntilMonday);

            setTimeout(function () 
			{
                showBirthdayMessage();
            }, monday.getTime() - Date.now());
        } 
		else 
		{
            showBirthdayMessage(); // Show wish immediately
        }

        birthdayTriggered = true;
        localStorage.setItem('birthdayWishGiven', 'true'); // Mark birthday wish as shown
    }
});



function showBirthdayMessage() 
{
    const birthdayMessage = document.createElement('div');
    birthdayMessage.innerHTML = "🎉 Happy Birthday! 🎉<br>Wishing you many happy returns of the day!";
    birthdayMessage.style.position = 'fixed';
    birthdayMessage.style.top = '50%';
    birthdayMessage.style.left = '50%';
    birthdayMessage.style.transform = 'translate(-50%, -50%)';
    birthdayMessage.style.padding = '20px';
    birthdayMessage.style.backgroundColor = '#f9f9f9';
    birthdayMessage.style.borderRadius = '10px';
    birthdayMessage.style.boxShadow = '0 0 15px rgba(0, 0, 0, 0.1)';
    birthdayMessage.style.textAlign = 'center';
    birthdayMessage.style.fontSize = '24px';
    birthdayMessage.style.fontFamily = 'Arial, sans-serif';
    birthdayMessage.style.color = '#28373E';
    birthdayMessage.style.zIndex = '9999';
    birthdayMessage.style.opacity = '0';
    birthdayMessage.style.transition = 'opacity 1.5s ease-in-out';

    document.body.appendChild(birthdayMessage);

    setTimeout(function () 
	{
        birthdayMessage.style.opacity = '1';
    }, 100);

    const duration = 6 * 1000; // 6 seconds for confetti celebration
    const animationEnd = Date.now() + duration;
    const defaults = 
	{
        startVelocity: 30,
        spread: 360,
        ticks: 60,
        zIndex: 0
    };

    function randomInRange(min, max) {
        return Math.random() * (max - min) + min;
    }

    const interval = setInterval(function () 
	{
        const timeLeft = animationEnd - Date.now();
        if (timeLeft <= 0) 
		{
            clearInterval(interval);
            birthdayMessage.style.opacity = '0';
            setTimeout(function () 
			{
                birthdayMessage.remove();
            }, 500);
            return;
        }

        const particleCount = 50 * (timeLeft / duration);

        confetti(Object.assign({}, defaults, 
		{
            particleCount,
            origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 }
        }));

        confetti(Object.assign({}, defaults, 
		{
            particleCount,
            origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 }
        }));
    }, 250);
}


document.addEventListener('DOMContentLoaded', function () 
{
    const storedBirthday = localStorage.getItem('birthdayWishGiven');
    if (!storedBirthday) 
	{
        const currentDate = new Date();
        const currentMonthDay = (currentDate.getMonth() + 1).toString().padStart(2, '0') + '-' + currentDate.getDate().toString().padStart(2, '0');
        const dob = "<?php echo date('m-d', strtotime($dob)); ?>"; 


        if (currentMonthDay === dob) 
		{
            showBirthdayMessage();
            localStorage.setItem('birthdayWishGiven', 'true');
        } 
		else 
		{

            const previousDate = new Date(currentDate);
            previousDate.setDate(currentDate.getDate() - 1);
            const previousMonthDay = (previousDate.getMonth() + 1).toString().padStart(2, '0') + '-' + previousDate.getDate().toString().padStart(2, '0');


            if (previousMonthDay === dob) 
			{
                const previousDay = previousDate.getDay();
                if (previousDay === 6 || previousDay === 0) 
				{

                    const daysUntilMonday = (1 - previousDay + 7) % 7;
                    previousDate.setDate(previousDate.getDate() + daysUntilMonday);
                    setTimeout(function () 
					{
                        showBirthdayMessage();
                    }, previousDate.getTime() - Date.now());
                } 
				else 
				{
                    showBirthdayMessage(); 
                }
            }
        }
    }
});


// Check if the employee has missed the birthday wish (on the next day login)
document.addEventListener('DOMContentLoaded', function() 
{
    const storedBirthday = localStorage.getItem('birthdayWishGiven');
    if (!storedBirthday)
	{
        const currentDate = new Date();
        const currentMonthDay = (currentDate.getMonth() + 1).toString().padStart(2, '0') + '-' + currentDate.getDate().toString().padStart(2, '0');
        const dob = "<?php echo date('m-d', strtotime($dob)); ?>"; 

        if (currentMonthDay === dob) 
		{
            showBirthdayMessage();
            localStorage.setItem('birthdayWishGiven', 'true');
        } 
		else 
		{
            const previousDate = new Date(currentDate);
            previousDate.setDate(currentDate.getDate() - 1);
            const previousMonthDay = (previousDate.getMonth() + 1).toString().padStart(2, '0') + '-' + previousDate.getDate().toString().padStart(2, '0');

            if (previousMonthDay === dob) 
			{

                const previousDay = previousDate.getDay();
                if (previousDay === 6 || previousDay === 0) 
				{

                    const daysUntilMonday = (1 - previousDay + 7) % 7;
                    previousDate.setDate(previousDate.getDate() + daysUntilMonday);
                    setTimeout(function() 
					{
                        showBirthdayMessage();
                    }, previousDate.getTime() - Date.now());
                } 
				else 
				{
                    showBirthdayMessage();
                }
            }
        }
    }
});


<?php

if ($time != '1 YEAR') 
{
    $time1 = substr($time, 0, 1); 
} 
else 
{
    $time1 = '1'; 
}


		$sql16 = "select * from emp with (nolock) where flag='Y' and empid='$empid'";
		$res16 = sqlsrv_query($conn,$sql16);
		while($row16 = sqlsrv_fetch_array( $res16, SQLSRV_FETCH_ASSOC))
		{
			if($row16['doj']==NULL)
			{
				$row16['doj']='';
			}
			$doj = trim($row16['doj']); 
		}	


$today = date('Y-m-d'); // Current date
$anniversaryFlag = (date('m-d', strtotime($doj)) === date('m-d')) ? 'true' : 'false';
$time1 = date('Y') - date('Y', strtotime($doj)); // Years of service


?>




let congratulateTriggered = false;
const time1 = "<?php echo $time1; ?>"; // Years of service
const anniversaryFlag = "<?php echo $anniversaryFlag; ?>"; // Check if today is the anniversary
const doj = new Date("<?php echo $doj; ?>"); // Employee's date of joining
const today = new Date(); // Today's date


function isAnniversary(doj, today) 
{
    return doj.getDate() === today.getDate() && doj.getMonth() === today.getMonth();
}

document.addEventListener('mousemove', function () 
{
    if (congratulateTriggered) return;

    if (anniversaryFlag === 'true' && isAnniversary(doj, today)) 
	{
        congratulateTriggered = true;
        const congratsMessage = document.createElement('div');
        congratsMessage.innerHTML = `🎉 Congratulations on your ${time1 === '1' ? "one-year" : `${time1}-year`} Anniversary! 🎉<br>Well done on achieving this milestone!`;

        congratsMessage.style.position = 'fixed';
        congratsMessage.style.top = '50%';
        congratsMessage.style.left = '50%';
        congratsMessage.style.transform = 'translate(-50%, -50%)';
        congratsMessage.style.padding = '20px';
        congratsMessage.style.backgroundColor = '#f9f9f9';
        congratsMessage.style.borderRadius = '10px';
        congratsMessage.style.boxShadow = '0 0 15px rgba(0, 0, 0, 0.1)';
        congratsMessage.style.textAlign = 'center';
        congratsMessage.style.fontSize = '24px';
        congratsMessage.style.fontFamily = 'Arial, sans-serif';
        congratsMessage.style.color = '#28373E';
        congratsMessage.style.zIndex = '9999';
        congratsMessage.style.opacity = '0';
        congratsMessage.style.transition = 'opacity 1.5s ease-in-out';

        document.body.appendChild(congratsMessage);

        setTimeout(function () {
            congratsMessage.style.opacity = '1';
        }, 100);

        // Confetti animation logic
        const duration = 6 * 1000;
        const animationEnd = Date.now() + duration;
        const defaults = {
            startVelocity: 30,
            spread: 360,
            ticks: 60,
            zIndex: 0,
        };

        function randomInRange(min, max) {
            return Math.random() * (max - min) + min;
        }

        const interval = setInterval(function () {
            const timeLeft = animationEnd - Date.now();
            if (timeLeft <= 0) {
                clearInterval(interval);
                congratsMessage.style.opacity = '0';
                setTimeout(function () {
                    congratsMessage.remove();
                }, 500);
                return;
            }

            const particleCount = 50 * (timeLeft / duration);

            confetti(Object.assign({}, defaults, {
                particleCount,
                origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 },
            }));

            confetti(Object.assign({}, defaults, {
                particleCount,
                origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 },
            }));
        }, 250);
    }
});








const congratulate = <?php echo json_encode($congratulate); ?>;


document.getElementById('experience-btn').addEventListener('click', function() 
{
    var time = "<?php echo strtolower($time); ?>";
    if (congratulate) 
	{
        const congratsMessage = document.createElement('div');
        if (time1 == '1') 
		{
            congratsMessage.innerHTML = "🎉 Congratulations on your one year! 🎉<br>Well done on achieving this milestone!";
        } 
		else if (time1 == '2') 
		{
            congratsMessage.innerHTML = "🎉 Congratulations on your two year Anniversary! 🎉<br>Well done on achieving this milestone!";
        } 
		else if (time1 == '3') 
		{
            congratsMessage.innerHTML = "🎉 Congratulations on your three year Anniversary! 🎉<br>Well done on achieving this milestone!";
        } 
		else if (time1 == '4') 
		{
            congratsMessage.innerHTML = "🎉 Congratulations on your four year Anniversary! 🎉<br>Well done on achieving this milestone!";
        } 
		else if (time1 == '5') 
		{
            congratsMessage.innerHTML = "🎉 Congratulations on your five year Anniversary! 🎉<br>Well done on achieving this milestone!";
        } 
		else if (time1 == '6') 
		{
            congratsMessage.innerHTML = "🎉 Congratulations on your six year Anniversary! 🎉<br>Well done on achieving this milestone!";
        } 
		else if (time1 == '7') 
		{
            congratsMessage.innerHTML = "🎉 Congratulations on your seven year Anniversary! 🎉<br>Well done on achieving this milestone!";
        } 
		else if (time1 == '8') 
		{
            congratsMessage.innerHTML = "🎉 Congratulations on your eight year Anniversary! 🎉<br>Well done on achieving this milestone!";
        }
		else if (time1 == '9') 
		{
            congratsMessage.innerHTML = "🎉 Congratulations on your nine year Anniversary! 🎉<br>Well done on achieving this milestone!";
        } 
		else if (time1 == '10') 
		{
            congratsMessage.innerHTML = "🎉 Congratulations on your ten year Anniversary! 🎉<br>Well done on achieving this milestone!";
        } 
		
        congratsMessage.style.position = 'fixed';
        congratsMessage.style.top = '50%';
        congratsMessage.style.left = '50%';
        congratsMessage.style.transform = 'translate(-50%, -50%)';
        congratsMessage.style.padding = '20px';
        congratsMessage.style.backgroundColor = '#f9f9f9';
        congratsMessage.style.borderRadius = '10px';
        congratsMessage.style.boxShadow = '0 0 15px rgba(0, 0, 0, 0.1)';
        congratsMessage.style.textAlign = 'center';
        congratsMessage.style.fontSize = '24px';
        congratsMessage.style.fontFamily = 'Arial, sans-serif';
        congratsMessage.style.color = '#28373E';
        congratsMessage.style.zIndex = '9999';
        congratsMessage.style.opacity = '0';
        congratsMessage.style.transition = 'opacity 1.5s ease-in-out';
        
        document.body.appendChild(congratsMessage);

        setTimeout(function() 
		{
            congratsMessage.style.opacity = '1';
        }, 100);

        const duration = 7 * 1000;
        const animationEnd = Date.now() + duration;
        const defaults = 
		{
            startVelocity: 30,
            spread: 360,
            ticks: 60,
            zIndex: 0
        };

        function randomInRange(min, max) 
		{
            return Math.random() * (max - min) + min;
        }

        const interval = setInterval(function() 
		{
            const timeLeft = animationEnd - Date.now();
            if (timeLeft <= 0) 
			{
                clearInterval(interval);
                congratsMessage.style.opacity = '0'; 
                setTimeout(function() 
				{
                    congratsMessage.remove();
                }, 500);
                return;
            }

            const particleCount = 50 * (timeLeft / duration); 

            confetti(Object.assign({}, defaults,			
			{
                particleCount,
                origin: 
				{
                    x: randomInRange(0.1, 0.3), 
                    y: Math.random() - 0.2 
                }
            }));

            confetti(Object.assign({}, defaults, 
			{
                particleCount,
                origin: 
				{
                    x: randomInRange(0.7, 0.9), 
                    y: Math.random() - 0.2 
                }
            }));
        }, 250); 
    } 
	else 
	{
        //alert(`You have ${time} of experience!`); 
    }
});




			document.getElementById('leaveemp').addEventListener('click', function() 
			{
				var holidayParagraph = document.getElementById('leave');
				if (holidayParagraph.style.display === 'none' || holidayParagraph.style.display === '') 
				{
					holidayParagraph.style.display = 'block'; 
				} 
				else 
				{
					holidayParagraph.style.display = 'none'; 
				}
			});
			
			document.getElementById('showHoliday').addEventListener('click', function() 
			{
				var holidayParagraph = document.getElementById('holidays');
				if (holidayParagraph.style.display === 'none' || holidayParagraph.style.display === '') 
				{
					holidayParagraph.style.display = 'block'; 
				} 
				else 
				{
					holidayParagraph.style.display = 'none'; 
				}
			});
			
			
			document.getElementById('currentleavebalance').addEventListener('click', function() 
			{
				var holidayParagraph = document.getElementById('leavebalance');
				if (holidayParagraph.style.display === 'none' || holidayParagraph.style.display === '') 
				{
					holidayParagraph.style.display = 'block';
				} 
				else 
				{
					holidayParagraph.style.display = 'none';
				}
			});
			</script>	

<?php
		}		
	
	}	
	
	
}


else
{
///////////////////////////////////////////////////////////	
}	
?>


</body>
</html>
