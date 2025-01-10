<html>
<style>

#leavebalance 
{
    display:none;
}

.bal:hover 
{
	transform: scale(1.10);
}

tbody tr:nth-child(odd)
{
	background-color:#e6eaf0;	
}

tbody tr:nth-child(even)
{
	background-color:#f2f3f5;	
}

.file-input 
{
  display: none;
}

.file-label 
{
  display: inline-block;
  padding: 10px 20px;
  font-size: 16px;
  font-weight: bold;
  color: #fff;
  background-color:#162913;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.file-label:hover
{
  background-color: #3e543a;
}

.sub-label 
{
  display: inline-block;
  padding: 10px 20px;
  font-size: 16px;
  font-weight: bold;
  color: #fff;
  background-color: #162913;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.sub-label:hover
{
  background-color: #3e543a;
}

</style>
<body>

<?php	
session_start();
include "connect.php";

$emptype = ['ADMIN'];
$emptype[] = 'EMPLOYEE';

// Display both values
foreach ($emptype as $etype) 
{
if (isset($_POST['emptype'])) 
{
    $etype = $_POST['emptype'];
}
}

$empid='';
if(isset($_SESSION['empid']))
{
	$empid=$_SESSION['empid'];
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


if($empid==$_SESSION['empid'])
{	
 
global $mm,$time,$yy,$empid;	

$totalLeaveBalance1 = getLeavesTakenPerMonth($empid);	

	
$sql3 = "select * from emp with (nolock) where empid='$empid'";
$res3 = sqlsrv_query($conn,$sql3);
$row3 = sqlsrv_fetch_array( $res3, SQLSRV_FETCH_ASSOC);


if($row3['name']==NULL)
{
	$row3['name']='';
} 
$name = trim($row3['name']); 

if($row3['dob']==NULL)
{
	$row3['dob']='';
}
$dob = trim($row3['dob']); 

if($row3['doj']==NULL)
{
	$row3['doj']='';
}
$doj = trim($row3['doj']); 

if($row3['cutoff']==NULL)
{
	$row3['cutoff']='';
}
$cutoff = trim($row3['cutoff']);

if($row3['address']==NULL)
{
	$row3['address']='';
}
$address = trim($row3['address']); 

if($row3['gender']==NULL)
{
	$row3['gender']='';
}
$gender = trim($row3['gender']); 

if($row3['email']==NULL)
{
	$row3['email']='';
}
$email = trim($row3['email']); 

if($row3['mob']==NULL)
{
	$row3['mob']='';
}
$mob = trim($row3['mob']); 

if($row3['designation']==NULL)
{
	$row3['designation']='';
}
$designation = trim($row3['designation']); 

if($row3['dojtype']==NULL)
{
	$row3['dojtype']='';
}
$dojtype = trim($row3['dojtype']);

if($row3['emptype']==NULL)
{
	$row3['emptype']='';
}
$emptype  =  trim($row3['emptype']); 


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
		
?>
<center>

<?php
include "header2.html";
?>

<br>

<?php

$profileImages = 
[
    '1044' => 'jinoy.png',
    '1068' => 'anandhu.png',
    '1001' => 'anurag.png',
    '1071' => 'vyshnav.png',
    '1003' => 'rithik.png',
    '1004' => 'sarath.png',
    '1000' => 'sanoop.png',
    '1091' => 'akhil.png',
    '1002' => 'anna.png',
    '1070' => 'manju.png',
    '1118' => 'test1.png',
    '1119' => 'test2.png',
    '999' => 'arun.png',
    '1108' => 'rajagopal.png',
];

$defaultImage = ($gender == 'MALE') ? 'male1.png' : 'female1.png';
$imageFile = array_key_exists($empid, $profileImages) ? $profileImages[$empid] : $defaultImage;
$imagePath = "../leave/dp/$imageFile"; 
$_SESSION['profile_image'] = $imagePath;
?>

<img src="<?php echo $_SESSION['profile_image'] . '?' . time(); ?>" style="width:100px;height:100px;border-radius:50px;"><br>

<b><?php echo $name; ?></b>

<form action="uploadpicture.php" method="post" enctype="multipart/form-data" onsubmit="refreshMainPage();">
    
	<input type="hidden" name="empid" value="<?php echo $empid; ?>">
	
    <input type="file" name="profile_image" accept="image/*" required id="profileImageInput" class="file-input">
    <label for="profileImageInput" class="file-label">Choose New Profile Picture</label><br><br>

    <button type="submit" name="upload" id="uploadButton" class="sub-label" style="display:none;" onclick="refreshMainPage();">Upload Picture</button>
	
</form>

<script>
function refreshMainPage() 
{
	window.open('main.php', '_self');
}
</script>

</center>

<center>
<h2><b style='font-size:18px;'>EMPLOYEE DETAILS</b></h2>
<table class="report" style="text-align:center;color=black;border=2;">
<tr>
<td style='background-color:#DBD9D7;font-size:16px;padding:6px;'>EMPLOYEE ID</td>
<td style='font-size:15px;padding:6px;'><?php echo $empid;?></td>
</tr>
<tr>
<td style='background-color:#DBD9D7;font-size:16px;padding:6px;'>EMPLOYEE NAME</td>
<td style='font-size:15px;padding:6px;'><?php echo $name;?></td>
</tr>
<tr>
<td style='background-color:#DBD9D7;font-size:16px;padding:6px;'>DATE OF BIRTH</td>
<td style='font-size:15px;padding:6px;'><?php echo $formattedDob;?></td>
</tr>
<tr>
<td style='background-color:#DBD9D7;font-size:16px;padding:6px;'>DATE OF JOINING</td>
<td style='font-size:15px;padding:6px;'><?php echo $formattedDoj;?></td>
</tr>
<tr>
<td style='background-color:#DBD9D7;font-size:16px;padding:6px;'>EXPERIENCE</td>
<td style='font-size:15px;padding:6px;'><?php echo $time;?></td>
</tr>
<!--<tr>
<td style='background-color:#DBD9D7;font-size:16px;padding:6px;'>ADDRESS</td>
<td style='font-size:15px;padding:6px;'><?php //echo $address;?></td>
</tr>-->
<tr>
<td style='background-color:#DBD9D7;font-size:16px;padding:6px;'>EMAIL ID</td>
<td style='font-size:15px;padding:6px;'><u style='color:blue;'><?php echo $email;?></u></td>
</tr>
<tr>
<td style='background-color:#DBD9D7;font-size:16px;padding:6px;'>MOBILE</td>
<td style='font-size:15px;padding:6px;'><?php echo $mob;?></td>
</tr>
<tr>
<td style='background-color:#DBD9D7;font-size:16px;padding:6px;'>DESIGNATION</td>
<td style='font-size:15px;padding:6px;'><?php echo $designation;?></td>
</tr>
<tr>
<td style='background-color:#DBD9D7;font-size:16px;padding:6px;'>LEAVE BALANCE</td>




<?php

$totalLeaveBalance1 = $totalLeaveBalance1 + 1;


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
					$totalLeaveBalance2 = 1;
					$totalLeaveBalance1 = $totalLeaveBalance2;
				}	
		    } 



if($dojtype=='Y')
{
	if($totalLeaveBalance1>0)
	{
		?>
		<td style="background-color:#49b020;color:white;text-align:center;" class='bal' id='currentleavebalance' title="<?php 
	if($totalLeaveBalance1==1)
	{echo 'Hi there! Just a heads up, your leave balance is running low. Please plan accordingly!' ;}
	elseif($totalLeaveBalance1<1)
	{echo 'Hi there! Just a heads up, your leave balance is running low. You currently have no casual leaves left. Please plan accordingly!' ;}
	else
	{echo 'Hi there! Just a heads up, your casual leave balance is running strong.';}?>"><?php echo "+".$totalLeaveBalance1;?></td>
		<?php
	}
	elseif($totalLeaveBalance1==0)
	{
		?>
		<td style="background-color:orange;color:white;text-align:center;" class='bal' id='currentleavebalance' title="<?php 
	if($totalLeaveBalance1==1)
	{echo 'Hi there! Just a heads up, your leave balance is running low. Please plan accordingly!' ;}
	elseif($totalLeaveBalance1<1)
	{echo 'Hi there! Just a heads up, your leave balance is running low. You currently have no casual leaves left. Please plan accordingly!' ;}
	else
	{echo 'Hi there! Just a heads up, your casual leave balance is running strong.';}?>"><?php echo $totalLeaveBalance1;?></td>
		<?php
	}
	else
	{
		?>
		<td style="background-color:#f75f54;color:white;text-align:center;" class='bal' id='currentleavebalance' title="<?php 
	if($totalLeaveBalance1==1)
	{echo 'Hi there! Just a heads up, your leave balance is running low. Please plan accordingly!' ;}
	elseif($totalLeaveBalance1<1)
	{echo 'Hi there! Just a heads up, your leave balance is running low. You currently have no casual leaves left. Please plan accordingly!' ;}
	else
	{echo 'Hi there! Just a heads up, your casual leave balance is running strong.';}?>"><?php echo $totalLeaveBalance1;?></td>	
		<?php
	}
}


else
{
	$totalLeaveBalance1=$totalLeaveBalance1-1;
	if($totalLeaveBalance1>0)
	{
		?>
		<td style="background-color:#49b020;color:white;text-align:center;" class='bal' id='currentleavebalance' title="<?php 
	if($totalLeaveBalance1==1)
	{echo 'Hi there! Just a heads up, your leave balance is running low. Please plan accordingly!' ;}
	elseif($totalLeaveBalance1<1)
	{echo 'Hi there! Just a heads up, your leave balance is running low. You currently have no casual leaves left. Please plan accordingly!' ;}
	else
	{echo 'Hi there! Just a heads up, your casual leave balance is running strong.';}?>"><?php echo "+".$totalLeaveBalance1;?></td>
		<?php
    }
	elseif($totalLeaveBalance1==0)
	{	
		?>
		<td style="background-color:#ed9911;color:white;text-align:center;" id='currentleavebalance' class='bal' title="<?php 
	if($totalLeaveBalance1==1)
	{echo 'Hi there! Just a heads up, your leave balance is running low. Please plan accordingly!' ;}
	elseif($totalLeaveBalance1<2)
	{echo 'Hi there! Just a heads up, your leave balance is running low. You currently have no casual leaves left. Please plan accordingly!' ;}
	else
	{echo 'Hi there! Just a heads up, your casual leave balance is running strong.';}?>"><?php echo $totalLeaveBalance1;?></td>
		<?php
	}
	else
	{
		?>
		<td style="background-color:#f75f54;color:white;text-align:center;" class='bal' id='currentleavebalance' title="<?php 
	if($totalLeaveBalance1==1)
	{echo 'Hi there! Just a heads up, your leave balance is running low. Please plan accordingly!' ;}
	elseif($totalLeaveBalance1<1)
	{echo 'Hi there! Just a heads up, your leave balance is running low. You currently have no casual leaves left. Please plan accordingly!' ;}
	else
	{echo 'Hi there! Just a heads up, your casual leave balance is running strong.';}?>"><?php echo $totalLeaveBalance1;?></td>	
		<?php
	}
}	
?>


</tr>


</table>

</center>


</body>
<script>


	const profileImageInput = document.getElementById('profileImageInput');
	const uploadButton = document.getElementById('uploadButton');
	profileImageInput.addEventListener('change', function() 
	{
		uploadButton.style.display = profileImageInput.files.length > 0 ? 'inline-block' : 'none';
	});
		
</script>
</html>

<?php
}
else
{
	
}	
?>