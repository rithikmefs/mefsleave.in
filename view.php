<?php
session_start();
include "connect.php";
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

$empid='';
if(isset($_POST['empid']))
{
	$empid=$_POST['empid'];
}


$emptype = ['EMPLOYEE'];
$emptype[] = 'ADMIN';

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



$name='';
$leave='';
$interval='';
$yearsWorked='';
$leavesTaken1='';
$totalLeaveBalance='';
$currentDate = new DateTime(); 



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
	$yearsWorked = $interval->y + ($interval->m > 0 ? 1 : 0);
    
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
			$totalLeaveBalance -=$leavesTaken1;
			$totalLeaveBalance1 = $totalLeaveBalance ;			

	}
	
	
}

return (int)$totalLeaveBalance1;

}


$trdata1 = "";
$trdata2 = "";
$leavedate = "";
$totalLeaveBalance1 = 0;
if($totalLeaveBalance1>=0)
{	
	$trdata1 = "";
}	
else
{
	$trdata2 = "";
}
?>
<html>
<head>
<link rel="icon" href="MEFS.png">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap">
<style>
td
{
	text-align:center;
}

.sub
{
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.3s ease;	
}	
.sub:hover 
{
	transform: scale(1.10);
}

.bal:hover 
{
	transform: scale(1.10);
}

.eye:hover 
{
	transform: scale(1.20);
}

.report
{
	caret-color:transparent;
}

td
{
	font-size:15px;
	padding:3px;
}

tbody tr:nth-child(odd)
{
	background-color:#e6eaf0;	
}

tbody tr:nth-child(even)
{
	background-color:#f2f3f5;	
}


tr.dis:hover 
{
	background-color:yellow;
}

.profile-pic2 
{
    display: none; /* Hide the image initially */
    position: absolute; /* Position it absolutely */
    margin-left:100px; /* Adjust the position if necessary */
	opacity:100%;
	z-index:999;
    top: -15; /* Adjust the vertical position */
    left: 10; /* Adjust the horizontal position */
	bottom:0;
	right:0;	
	border-radius:50px;
}

        /* Container to hold the text and image */
.name-container2 
{
    position: relative; /* Position relative to allow absolute positioning of the image */
    display: inline-block; /* Inline block to adjust as needed */
}

        /* Show the image on hover */
.name-container2:hover .profile-pic2
{
    display: block; /* Show the image on hover */
}


.profile-pic1 
{
    display: none; /* Hide the image initially */
    position: absolute; /* Position it absolutely relative to .name-container1 */
    top: -5; /* Adjust the vertical position */
    left: 0; /* Adjust the horizontal position */
	bottom:0;
	right:0;
    opacity: 100%;
    z-index: 999;
    border-radius: 50px;
    width: 30px; /* Set the width of the image */
    height: 30px; /* Set the height of the image */
}

.name-container1 
{
    position: relative; /* Position relative to allow absolute positioning of the image */
    display: inline-block; /* Inline block to adjust as needed */
}

.name-container1:hover .profile-pic1 
{
    display: block; /* Show the image on hover */
}

.name-container1:hover .empid 
{
    visibility: hidden; /* Hide the employee ID on hover */
}



</style>
</head>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<body class="report" bottommargin="0" topmargin="0" leftmargin="0" rightmargin="0" padding="2">
<?php
/* $var="";
if($empid!='999')
{
	$var="and empid=$empid";
} */
if($etype=='ADMIN')
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
				
?>		
<div>
	<form name="main" method="get" action="viewleavedateadmin.php">
    <input type="hidden" name="empid" id="empid" value="<?php echo $empid; ?>">
    <input type="hidden" name="name" id="name" value="<?php echo $name; ?>">
    <input type="hidden" name="totalLeaveBalance1" id="totalLeaveBalance1" value="<?php echo $totalLeaveBalance1; ?>">
    <input type="hidden" name="leavedate" id="leavedate" value="<?php echo $leavedate; ?>">
    <input type="hidden" name="leavetype" id="leavetype" value="<?php echo $leavetype; ?>">
	</form>
</div>

	

<?php


	}
	
	$sql2 = "select * from emp with (nolock) where flag='Y' AND empid!='1118'  AND empid!='1119' order by dob";
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
	//echo "MONTHS - ".$monthsDifference;
	$yearsWorked = $interval->y + ($interval->m > 0 ? 1 : 0);
    
	$yy=0;
	$mm=0;
	$time=0;
	$mm = $monthsDifference % 12;
	$yy = $monthsDifference / 12;
	
	
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

		elseif((int)$yy == 1 && $mm == 0 )
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


	$totalLeaveBalance1 = getLeavesTakenPerMonth($empid);
		
	$color='background-color:green;';
	$plus='+';
	$plus1='%2B';
	
	
	global $leave;
	//$totalLeaveBalance1 = $monthsDifference;
	
		
	$totalLeaveBalance2 = $totalLeaveBalance1;

	
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
			$plus1='%2B';
		}
	}	

	$compoleave = 0;

	$sql8 = " select count(empid) as compoleave from compo where empid='$empid'";
	$res8 = sqlsrv_query($conn,$sql8);
	while($row8 = sqlsrv_fetch_array( $res8, SQLSRV_FETCH_ASSOC))
	{
		$compoleave = $row8['compoleave'];	
	}
	


$profilePic='';
if($gender=='MALE')
{
	if($empid=='1044')
	{
		$profilePic='../leave/dp/jinoy.png';
	}
	elseif($empid=='1068')
	{
		$profilePic='../leave/dp/anandhu.png';		
	}	
	elseif($empid=='1001')
	{
		$profilePic='../leave/dp/anurag.png';
	}
	elseif($empid=='1071')
	{
		$profilePic='../leave/dp/vyshnav.png';
	}
 	elseif($empid=='1003')
	{
		$profilePic='../leave/dp/rithik.png';
	} 	
 	elseif($empid=='1004')
	{
		$profilePic='../leave/dp/sarath.png';
	} 	
 	elseif($empid=='1000')
	{
		$profilePic='../leave/dp/sanoop.png';
	} 
 	elseif($empid=='1091')
	{
		$profilePic='../leave/dp/akhil.png';
	} 	
 	elseif($empid=='1118')
	{
		$profilePic='../leave/dp/test1.png';
	} 	 	
	elseif($empid=='999')
	{
		$profilePic='../leave/dp/arun.png';
	}  	
	elseif($empid=='1108')
	{
		$profilePic='../leave/dp/rajagopal.png';
	} 
	
	else
	{
		$profilePic='../leave/dp/male.png';
	}
	
}	

elseif($empid=='1002')
{
	$profilePic='../leave/dp/anna.png';
}
elseif($empid=='1070')
{
	$profilePic='../leave/dp/manju.png';
}
elseif($empid=='1119')
{
	$profilePic='../leave/dp/test2.png';
} 

else
{
	$profilePic='../leave/dp/female.png';
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
	
$currentDate = date('d-m-Y');
	
		$trdata1 .= "
		<tr class='dis'>
		<td style='font-size:14px;'>
		<div class='name-container1'>
		<span class='empid'>$empid</span>
		<img class='profile-pic1' src='$profilePic' alt='Profile Picture' width='50' height='50'>
	    </div>
		</td>
		<td style='font-size:14px;'>
		<div class='name-container2'>
		$name
		<img class='profile-pic2' src='$profilePic' alt='Profile Picture' width='50' height='50'>
		</div>
		</td>
		<td style='font-size:14px;'>$formattedDob</td>
		<td style='font-size:14px;'>$formattedDoj</td>
		<td style='font-size:14px;'>$time</td>  
		<td><a href='https://mail.google.com/mail/?view=cm&fs=1&to=$email&su=LEAVE%20INFO&body=Hello%20$name,%0AThis%20mail%20is%20from%20Middle%20East%20Financial%20Software%20Solutions.%0AYour%20current%20leave%20balance%20as%20per%20$currentDate%20is%20$plus1$totalLeaveBalance1.%0AYour%20compo%20leave%20balance%20is%20$compoleave.%0A%0A%0A%0A%0A%0A%0A%0A%0AKindly%20check%20the%20pdf%20below%20for%20further%20clarifications.%0A--%0AThanks%20and%20regards,%0AHead%20of%20Operations%0AMiddle%20East%20Financial%20Software%20Solutions,%0APurakkad%20Square,%20Vyttila,%20Kochi%0A0484%204855329/info@mefs.in' target='_blank'>$email</a></td>
		<td style='font-size:14px;'>$mob</td>
		<td style='font-size:14px;'>$designation</td>
		
		<td style='$color color:white;font-size:18px;' class='bal' title='Click to view the leave report'><a href='viewleavedateadmin.php?empid=$empid&name=$name&totalLeaveBalance1=$totalLeaveBalance1&cutoff=$cutoff' style='color:white;font-size:18px;text-decoration:none;'><div>$plus$totalLeaveBalance1</div></a></td>
		
		</tr>";
	
	

}

?>

<center>
<?php
include "header2.html";
?>


</center>
<br><br>

<div id='content'>
<center>

<?php
echo "<b style='font-size:22px;font-family:Poppins;'>Leave Report as per : " . $currentDate . "</b><br>";
?>
</center>

<br>
<center>
<table class="report" padding="5px">
<tr style='background-color:#665763;color:white;font-family:Poppins;'>
<td style='width:100px;font-size:14px;'>EMPLOYEE ID</td>
<td style='width:220px;font-size:14px;'>EMPLOYEE NAME</td>
<td style='width:130px;font-size:14px;'>DATE OF BIRTH</td>
<td style='width:150px;font-size:14px;'>DATE OF JOINING</td>
<td style='width:220px;font-size:14px;'>EXPERIENCE</td>
<td style='width:80px;font-size:14px;'>EMAIL ID</td>
<td style='width:80px;font-size:14px;'>MOBILE</td>
<td style='width:180px;font-size:14px;'>DESIGNATION</td>
<td style='width:130px;font-size:14px;'>LEAVE BALANCE</td>
</tr>



<?php 
echo $trdata1;
?>

</table>
</center>
<br>

<center>

<!--<pre>
Note : The leave balance may vary after the calculations of salary cutoffs are added!
</pre>-->

</div>
</center>

<center>

<img src="../leave/images/print.png" id="download" class="sub" style="width:25px;height:25px;" title="click to download the pdf">

</center>

    <script>
        document.getElementById('download').addEventListener('click', function () 
        {
            var element = document.getElementById('content');
            var opt = {
                margin:       0.5,
                filename:     'Employee Leave Report.pdf',
                image:        { type: 'jpeg', quality: 0.99 },
                html2canvas:  { scale: 4 }, 
                jsPDF:        { unit: 'in', format: [32, 16] }
            };

            html2pdf().set(opt).from(element).save();
        });
    </script>


<?php
}






else //INDUVIDUAL 
{

}
?>

