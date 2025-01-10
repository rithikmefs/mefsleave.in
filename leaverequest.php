<html>
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="icon" href="../leave/images/MEFS.png">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap">
<style>

.sub
{
	width:30%;
	border-radius:8px;
	background-color:#28373E;
	text-align:center;
	font-family:poppins;
	font-size:17px;
	transition:background-color 0.3s;
	height:12%;
	color:white;
    margin: 0;
    cursor: pointer;
    transition: background-color 0.2s ease, transform 0.2s ease;	
}	
.sub:hover 
{
	transform: scale(1.05);
}

.sub1
{
	width:17%;
	border-radius:8px;
	background-color:#28373E;
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

.report
{
	caret-color:transparent;
}
a
{
	text-decoration:none;
}

</style>
<body class="report" rightmargin="0" leftmargin="0" bottommargin="0" topmargin="0">



<?php
session_start();
include "connect.php";
include "header2.html";
$leavetype = isset($_SESSION['leavetype']) ? $_SESSION['leavetype'] : '';


$empid = '';
if (isset($_SESSION['empid'])) 
{
    $empid = $_SESSION['empid'];
}

$username = '';
if (isset($_POST['username'])) 
{
    $username = $_POST['username'];
}

$password = '';
if (isset($_POST['password'])) 
{
    $password = $_POST['password'];
}

$name = '';
if (isset($_POST['name'])) 
{
    $name = $_POST['name'];
}

$designation='';
if(isset($_POST['designation']))
{
	$designation=$_POST['designation'];	
}

$mob='';
if(isset($_POST['mob']))
{
	$mob=$_POST['mob'];	
}

$email='';
if(isset($_POST['email']))
{
	$email=$_POST['email'];	
}

$flag='';
if(isset($_POST['flag']))
{
	$flag=$_POST['flag'];	
}

$selectedLeaveDate1 = isset($_POST['leavedate1']) ? $_POST['leavedate1'] : '';
$selectedLeaveDate2 = isset($_POST['leavedate2']) ? $_POST['leavedate2'] : '';
$selectedLeaveDate3 = isset($_POST['leavedate3']) ? $_POST['leavedate3'] : '';

$selectedLeaveType = isset($_POST['leavetype']) ? $_POST['leavetype'] : '';


$var = '';
$var1 = "**Please mention the date and reason for leave**";
$var2 = "**Verify the leave date and leave type mentioned above is correct**";
$color = "color:red;";


$selectedLeaveDate01 = new DateTime($selectedLeaveDate1);
$emaildate = $selectedLeaveDate01->format('jS F Y');  // Outputs like '19th October 2024'
$currentDay = $selectedLeaveDate01->format('l');  // 'l' represents the full name of the day

$selectedLeaveDate02 = new DateTime($selectedLeaveDate2);
$secondDayemaildate = $selectedLeaveDate02->format('jS F Y');  // Outputs like '19th October 2024'
$secondDaycurrentDay = $selectedLeaveDate02->format('l');  // 'l' represents the full name of the day

$selectedLeaveDate03 = new DateTime($selectedLeaveDate3);
$thirdDayemaildate = $selectedLeaveDate03->format('jS F Y');  // Outputs like '19th October 2024'
$thirdDaycurrentDay = $selectedLeaveDate03->format('l');  // 'l' represents the full name of the day



$day = 'day';
$and = '';
$selectedDays = isset($_POST['days']) ? $_POST['days'] : '';

if($selectedDays=='1')
{
	$secondDayemaildate = '';
	$secondDaycurrentDay = '';
	$thirdDayemaildate = '';
	$thirdDaycurrentDay = '';
	$coma = '';	
}

if($selectedDays=='2')
{
	$thirdDayemaildate = '';
	$thirdDaycurrentDay = '';
	$coma = 'and';
	$day = 'days';
}

if($selectedDays=='3')
{
	$coma = ',';	
	$and = 'and';	
	$day = 'days';	
}

$formReason = '';

$selectedReason = isset($_POST['reason']) ? $_POST['reason'] : '';

if($selectedReason == 'am unwell and need time to recover from my illness') //SICK
{
	$selectedReason = "am unwell and need time to recover from my illness";
	$formReason = "SICK";
}

if($selectedReason == 'have an important examination scheduled for this day') //EXAMINATON
{
	$selectedReason = "have an important examination scheduled for this day";
	$formReason = "EXAMINATON";
}

if($selectedReason == 'have a medical consultation scheduled for this day') //MEDICAL CONSULTATION
{
	$selectedReason = "have a medical consultation scheduled for this day";
	$formReason = "MEDICAL CONSULTATION";
}

if($selectedReason == 'need to address a personal matter that demands my immediate attention') //PERSONAL MATTER
{
	$selectedReason = "need to address a personal matter that demands my immediate attention";
	$formReason = "PERSONAL MATTER";
}

if($selectedReason == 'have a family emergency that requires my immediate attention') //FAMILY EMERGENCY
{
	$selectedReason = "have a family emergency that requires my immediate attention";
	$formReason = "FAMILY EMERGENCY";
}

if($selectedReason == 'have to attend the funeral of someone dear to me') //FUNERAL
{
	$selectedReason = "have to attend the funeral of someone dear to me";
	$formReason = "FUNERAL";
}

if($selectedReason == 'have to attend a family ceremony which holds great significance and importance for my family and me') //FAMILY CEREMONY
{
	$selectedReason = "have to attend a family ceremony which holds great significance and importance for my family and me";
	$formReason = "FAMILY CEREMONY";
}

if($selectedReason == 'have to attend a death anniversary which holds great significance and importance for me') //DEATH ANNIVERSARY
{
	$selectedReason = "have to attend a death anniversary which holds great significance and importance for me";
	$formReason = "DEATH ANNIVERSARY";
}

if($selectedReason == 'need to attend a festival event that carries deep cultural and familial importance') //FESTIVAL EVENT
{
	$selectedReason = "need to attend a festival event that carries deep cultural and familial importance";
	$formReason = "FESTIVAL EVENT";
}

if($selectedReason == 'have planned to spend this time on vacation with my family') //VACATION
{
	$selectedReason = "have planned to spend this time on vacation with my family";
	$formReason = "VACATION";
}

if($selectedReason == '***REASON HERE***') //NONE OF THE ABOVE
{
	$selectedReason = "***REASON HERE***";
	$formReason = "N/A";
}


if(!empty($empid))
{
	$sql = "select * from emp with (nolock) where empid='$empid' ";
	$res = sqlsrv_query($conn,$sql);
	while($row = sqlsrv_fetch_array( $res, SQLSRV_FETCH_ASSOC))
	{		
		if($row['mob']==NULL)
		{
			$row['mob']='';
		}
		$mob = trim($row['mob']); 

		if($row['designation']==NULL)
		{
			$row['designation']='';
		}	
		$designation = trim($row['designation']);
		
		if($row['email']==NULL)
		{
			$row['email']='';
		}	
		$email = trim($row['email']);
		
		if($row['name']==NULL)
		{
			$row['name']='';
		}
		$name  =  trim($row['name']);
		
		if($row['emptype']==NULL)
		{
			$row['emptype']='';
		}
		$emptype  =  trim($row['emptype']);		

	}		

}


$compodate = '';	


if($flag!='N')
{
$compocount = '';	
?>
<br>
<button type="button" class="sub1" style="margin-left:1100px;" onclick="showPendingRequests()">PENDING LEAVE REQUESTS</button>

<div id="pendingRequestsModal" style="display: none; position: fixed; top: 20%; left: 19%; width: 55%; background-color: white; border: 1px solid #ccc; box-shadow: 0 4px 8px rgba(0,0,0,0.2); padding: 20px; z-index: 1000;border-radius:10px;">
    <h3 style="text-align: center;">Pending Leave Requests</h3>
    <div id="pendingRequestsContent" style="max-height: 300px; overflow-y: auto;">
        <!-- Table will be populated here -->
    </div>
    <button style="margin-top:10px;display: block;margin-left:auto; margin-right:auto;width:10px;	border-radius:8px;background-color:#28373E;width:10%;text-align:center;font-family:poppins;font-size:16px;transition:background-color 0.3s;height:8%;color:white;transition: background-color 0.2s ease, transform 0.2s ease;" onclick="closePendingRequests()">Close</button>
</div>
<div id="overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 999;"></div>

<script>

function showPendingRequests() 
{

    document.getElementById("pendingRequestsModal").style.display = "block";
    document.getElementById("overlay").style.display = "block";

    fetch('pendingleaves.php')
        .then(response => response.text())
        .then(data => 
		{
            document.getElementById("pendingRequestsContent").innerHTML = data;
        })
        .catch(error => 
		{
            console.error('Error fetching pending leaves:', error);
            document.getElementById("pendingRequestsContent").innerHTML = '<p style="color: red;">Failed to load data.</p>';
        });
}

function closePendingRequests() 
{
    document.getElementById("pendingRequestsModal").style.display = "none";
    document.getElementById("overlay").style.display = "none";
}


</script>


<center>

<div style='background-color:#FFFFFF;border-radius:10px;width:50%;min-height:560px;max-height:750px;margin-left:-60;box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);'>
<br><br>


<form name='leavetypeForm' id='leavetypeForm' action='' method='post'>
         <label style="caret-color:transparent;margin-left:0;" for="leavetype"><b style="margin-left:0px;">Select Leavetype - </b></label>	
		 &nbsp;
		 <select style="border-color:black;width:195px;height:35px;border-radius:10px;font-family:poppins;font-size:14px;" name="leavetype" id="leavetype"  onchange="submitForm()" required>
		 <option value="" disabled <?= $selectedLeaveType == '' ? 'selected' : '' ?>>Select an Option</option>
         <option value="CASUAL" <?= $selectedLeaveType == 'CASUAL' ? 'selected' : '' ?>>CASUAL LEAVE</option>
		 <?php
		 $sql = "select compodate from compo with (nolock) where empid='$empid' ";
		 $res = sqlsrv_query($conn,$sql);
		 while($row = sqlsrv_fetch_array( $res, SQLSRV_FETCH_ASSOC))
		 {		
			if($row['compodate']==NULL)
			{
				$row['compodate']='';
			}
			$comdate = trim($row['compodate']);
		 }
		 if($comdate)	
		 {	  
			 ?>
			 <option value="COMPO" <?= $selectedLeaveType == 'COMPO' ? 'selected' : '' ?>>COMPO LEAVE</option>
			 <?php
		 }
		 ?>
         </select>
		 <br>
		 <br>	
		 
		 
         <label style="caret-color:transparent;margin-left:0;" for="days"><b style="margin-left:11px;">Number of Days - </b></label>	
		 &nbsp;
		 <select style="border-color:black;width:195px;height:35px;border-radius:10px;font-family:poppins;font-size:14px;" name="days" id="days" onchange="submitForm()" required>
		 <option value="" disabled <?= $selectedDays == '' ? 'selected' : '' ?>>Select an Option</option>
		 <?php
		 if($selectedLeaveType == 'CASUAL')
		 {
			 ?>		
			 <option value="1" <?= $selectedDays == '1' ? 'selected' : '' ?>>1</option>
			 <option value="2" <?= $selectedDays == '2' ? 'selected' : '' ?>>2</option>
			 <option value="3" <?= $selectedDays == '3' ? 'selected' : '' ?>>3</option>
			 <?php
		 }
		 else
		 {	 
			 ?>		
			 <option value="1" <?= $selectedDays == '1' ? 'selected' : '' ?>>1</option>
			 <?php
			 $sql = "select count(compodate) as compocount from compo with (nolock) where empid='$empid' ";
			 $res = sqlsrv_query($conn,$sql);
			 while($row = sqlsrv_fetch_array( $res, SQLSRV_FETCH_ASSOC))
			 {		
				 $compocount = $row['compocount'];
			 }
			 if($compocount == 2)
			 {	 
				 ?>
				 <option value="2" <?= $selectedDays == '2' ? 'selected' : '' ?>>2</option>
				 <?php
			 }
			 elseif($compocount > 2)
			 {	 
				 ?>
				 <option value="2" <?= $selectedDays == '2' ? 'selected' : '' ?>>2</option>
				 <option value="3" <?= $selectedDays == '3' ? 'selected' : '' ?>>3</option>
				 <?php
			 }


			 else
			 {
				///////////////////
			 }
         }		 
		 ?>
		 </select>
		 <br><br>	


         <label style="caret-color:transparent;margin-left:0;" for="reason"><b style="margin-left:5px;">Reason for Leave - </b></label>	
		 &nbsp;
		 
		 <select style="border-color:black;width:195px;height:35px;border-radius:10px;font-family:poppins;font-size:14px;" name="reason" id="reason" onchange="submitForm()" required>
		 
		 <option value="" disabled <?= $selectedReason == '' ? 'selected' : '' ?>>Select an Option</option>
		 
         <option value="am unwell and need time to recover from my illness" <?= $selectedReason == "am unwell and need time to recover from my illness" ? 'selected' : '' ?>>SICK</option>
		 
		 <option value="have an important examination scheduled for this day" <?= $selectedReason == "have an important examination scheduled for this day" ? 'selected' : '' ?>>EXAMINATION</option>
		 
         <option value="have a medical consultation scheduled for this day" <?= $selectedReason == "have a medical consultation scheduled for this day" ? 'selected' : '' ?>>MEDICAL CONSULTATION</option>
		 
         <option value="need to address a personal matter that demands my immediate attention" <?= $selectedReason == "need to address a personal matter that demands my immediate attention" ? 'selected' : '' ?>>PERSONAL MATTER</option>
		 
		 <option value="have a family emergency that requires my immediate attention" <?= $selectedReason == "have a family emergency that requires my immediate attention" ? 'selected' : '' ?>>FAMILY EMERGENCY</option>
		 
         <option value="have to attend the funeral of someone dear to me" <?= $selectedReason == "have to attend the funeral of someone dear to me" ? 'selected' : '' ?>>FUNERAL</option>
		 
         <option value="have to attend a family ceremony which holds great significance and importance for my family and me" <?= $selectedReason == "have to attend a family ceremony which holds great significance and importance for my family and me" ? 'selected' : '' ?>>FAMILY CEREMONY</option>
		 
         <option value="have to attend a death anniversary which holds great significance and importance for me" <?= $selectedReason == "have to attend a death anniversary which holds great significance and importance for me" ? 'selected' : '' ?>>DEATH ANNIVERSARY</option>
		 
         <option value="need to attend a festival event that carries deep cultural and familial importance" <?= $selectedReason == "need to attend a festival event that carries deep cultural and familial importance" ? 'selected' : '' ?>>FESTIVAL EVENT</option>
		 
         <option value="have planned to spend this time on vacation with my family" <?= $selectedReason == "have planned to spend this time on vacation with my family" ? 'selected' : '' ?>>VACATION</option>
		 
         <option value="***REASON HERE***" <?= $selectedReason == "***REASON HERE***" ? 'selected' : '' ?>>NONE OF THE ABOVE</option>
         </select>
		 <br><br>	
		 


         <label style="caret-color:transparent;margin-left:0;" for="leavedate"><b style="margin-left:5px;">Select Leavedate - </b></label>	
		 &nbsp;
		 <!-- First Date Input -->
		 <input type="date" style="border-color:black;width:195px;height:35px;border-radius:6px;font-family:poppins;font-size:14px;" name="leavedate1" id="leavedate1" onchange="submitForm()" value="<?php echo $selectedLeaveDate1 ?? ''; ?>" required><br>
		 <!-- Second Date Input -->
		 <input type="date" 
           style="border-color:black;width:195px;height:35px;border-radius:6px;font-family:poppins;font-size:14px;margin-left:170px;" 
           name="leavedate2" 
           id="leavedate2" 
           onchange="submitForm()" 
           value="<?php echo $selectedLeaveDate2 ?? ''; ?>"  
		   style="display: <?php echo ($selectedDays >= 2) ? 'inline-block' : 'none'; ?>;"
           required><br>
    
		 <!-- Third Date Input -->
		 <input type="date" 
           style="border-color:black;width:195px;height:35px;border-radius:6px;font-family:poppins;font-size:14px;margin-left:170px;" 
           name="leavedate3" 
           id="leavedate3" 
           onchange="submitForm()" 
           value="<?php echo $selectedLeaveDate3 ?? ''; ?>" 
		   style="display: <?php echo ($selectedDays == 3) ? 'inline-block' : 'none'; ?>;"
           required>
		   <br><br>
		   
		<?php if ($selectedLeaveType === 'COMPO' && $selectedDays): ?>
			<div>
		  <label style="caret-color:transparent;margin-left:-105;" for="compodate"><b style="margin-left:0;">Compensatory Date - </b></label>	
		  &nbsp;
		
			<?php
			if ($_SERVER['REQUEST_METHOD'] === 'POST') 
			{
				$selectedCompodate = isset($_POST['compodate']) ? $_POST['compodate'] : [];
		    } 
			else 
			{
				$selectedCompodate = [];
		    }
	

			$sql7 = "SELECT compodate FROM compo WHERE empid='$empid' ORDER BY compodate";
			$res7 = sqlsrv_query($conn, $sql7);

			if ($res7) 
			{
				$counter = 1;
				while ($row7 = sqlsrv_fetch_array($res7, SQLSRV_FETCH_ASSOC)) 
				{
					$compodate = trim($row7['compodate'] ?? '');
					$date = DateTime::createFromFormat('Y-m-d', $compodate);
					$cd = $date->format('d-m-Y');
					
						$checked = in_array($compodate, $selectedCompodate) ? 'checked' : '';
						echo '<input type="checkbox" name="compodate[]" value="' . htmlspecialchars($compodate) . '" id="compodate' . $counter . '" class="compodate-checkbox" ' . $checked . ' onchange="submitForm()"> ' . htmlspecialchars($cd) . '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
						$counter++;
						
				}
			} 
			else 
			{
				echo 'No compensatory dates available.';
			}
			
			
			
				foreach ($selectedCompodate as $selectedCompodates) 
				{
					//echo $selectedCompodates;
				}
				
				
				
			?>
		  
			</div>
			
			
			<script>
			var selectedDays = <?php echo $selectedDays; ?>;
			var checkboxes = document.querySelectorAll('.compodate-checkbox');

			checkboxes.forEach(function(checkbox) 
			{
				checkbox.addEventListener('change', function() 
				{
					var checkedCount = document.querySelectorAll('.compodate-checkbox:checked').length;
					
					if (checkedCount > selectedDays) 
					{
						checkbox.checked = false;
						checkbox.disabled = true; 
						alert('You can only select ' + selectedDays + ' Compensatory date.');
					} 
					else 
					{
						checkboxes.forEach(function(checkbox) 
						{
							checkbox.disabled = false;
						});
					}
				});
			});
			</script>

	
			
		<?php endif; ?>		  

	
		 &nbsp;
         <label style="caret-color:transparent;margin-left:10;font-size:14px;">
         <input type="checkbox" id="confirmationCheckbox" name="confirmation" style="margin-right: 8px;" value="1" 
         <?php echo isset($_POST['confirmation']) && $_POST['confirmation'] == '1' ? 'checked' : ''; ?>   onchange="submitcheckbox()" required>
         I hereby confirm that the information provided in this leave request form is accurate and complete to the best of my knowledge.
         </label>		 
	 
		 
</form>


<?php

$confirmation = isset($_POST['confirmation']) ? $_POST['confirmation'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $empid = $_SESSION['empid'];
    $selectedDays = isset($_POST['days']) ? $_POST['days'] : 0;
    $selectedLeaveDate1 = $_POST['leavedate1'];
    $selectedCompodates = isset($_POST['compodate']) ? $_POST['compodate'] : [];
    $selectedLeaveType = isset($_POST['leavetype']) ? $_POST['leavetype'] : '';
    $confirmation = isset($_POST['confirmation']) ? $_POST['confirmation'] : false;
	
	date_default_timezone_set("Asia/Kolkata");
    $currentDateTime = date('Y-m-d H:i:s');	

    if ($selectedDays == 2) 
	{
        $selectedLeaveDate2 = $_POST['leavedate2'];
    } 
	elseif ($selectedDays == 3) 
	{
        $selectedLeaveDate2 = $_POST['leavedate2'];
        $selectedLeaveDate3 = $_POST['leavedate3'];
    }

    switch ($selectedReason) 
	{
        case 'am unwell and need time to recover from my illness':
            $formReason = "SICK";
            break;
        case 'have an important examination scheduled for this day':
            $formReason = "EXAMINATION";
            break;
        case 'have a medical consultation scheduled for this day':
            $formReason = "MEDICAL CONSULTATION";
            break;
        case 'need to address a personal matter that demands my immediate attention':
            $formReason = "PERSONAL MATTER";
            break;						
        case 'have a family emergency that requires my immediate attention':
            $formReason = "FAMILY EMERGENCY";
            break;
        case 'have to attend the funeral of someone dear to me':
            $formReason = "FUNERAL";
            break;
        case 'have to attend a family ceremony which holds great significance and importance for my family and me':
            $formReason = "FAMILY CEREMONY";
            break;
        case 'have to attend a death anniversary which holds great significance and importance for me':
            $formReason = "DEATH ANNIVERSARY";
            break;			
        case 'need to attend a festival event that carries deep cultural and familial importance':
            $formReason = "FESTIVAL EVENT";
            break;			
        case 'have planned to spend this time on vacation with my family':
            $formReason = "VACATION";
            break;
        default:
            $formReason = "N/A";
            break;
    }

    if ($confirmation) 
	{


        // Continue processing the leave request after deletion
        $leaveDatesToCheck = [$selectedLeaveDate1];
        if ($selectedDays >= 2) $leaveDatesToCheck[] = $selectedLeaveDate2;
        if ($selectedDays == 3) $leaveDatesToCheck[] = $selectedLeaveDate3;

		$leaveExists = false;
		foreach ($leaveDatesToCheck as $leaveDate) 
		{

			$sqlCheckLeave = "SELECT * FROM leave WHERE empid = '$empid' AND leavedate = '$leaveDate'";
			$resCheckLeave = sqlsrv_query($conn, $sqlCheckLeave);

			$sqlCheckLeaveRequest = "SELECT * FROM leaverequest WHERE empid = '$empid' AND leavedate = '$leaveDate'";
			$resCheckLeaveRequest = sqlsrv_query($conn, $sqlCheckLeaveRequest);

			if (($resCheckLeave && sqlsrv_fetch_array($resCheckLeave, SQLSRV_FETCH_ASSOC)) 
				|| ($resCheckLeaveRequest && sqlsrv_fetch_array($resCheckLeaveRequest, SQLSRV_FETCH_ASSOC))) 
			{
				$leaveExists = true;
				break;
			}
			
		}

		if ($leaveExists) 
		{
			echo '<b style="color: red;">Leave has already been marked on this date.</b>';
		}
 
		else 
		{
            date_default_timezone_set("Asia/Kolkata");
            $currentDateTime = date('Y-m-d H:i:s');	

            $compodatesStr = $selectedCompodates;
			
			if(!$selectedCompodates)
			{
				$compodatesStr[0] = null;
				$compodatesStr[1] = null;
				$compodatesStr[2] = null;				
			}			
			
			
/* 	        if (isset($_POST['compodate']) && is_array($_POST['compodate'])) 
			{
				foreach ($_POST['compodate'] as $compodate) 
				{
					$sqlDelete = "DELETE FROM compo WHERE empid = ? AND compodate = ?";
					$params = array($empid, $compodate);
					$stmt = sqlsrv_query($conn, $sqlDelete, $params);

					if ($stmt) 
					{
						//echo "Compensatory date $compodate has been deleted.<br>";
					} 
					else 
					{
						//echo "Error deleting compensatory date $compodate.<br>";
					}
				}
			} */		

            if ($selectedDays == 1) // 1DAY
			{
				
				$compodatesStr[1] = null;
				$compodatesStr[2] = null;	

				if($selectedLeaveType=='COMPO')
				{
					
					if ($selectedDays !='' && $selectedLeaveType!='' && $selectedLeaveDate1!='' && isset($compodatesStr[0]) && $selectedReason!='')//1 DATE for COMPO
					{ 
					
						$sql1 = "INSERT INTO leaverequest (empid, leavedate, compodate, reason, leavetype, time)
								 VALUES ('$empid', '$selectedLeaveDate1', '$compodatesStr[0]', '$formReason', '$selectedLeaveType', '$currentDateTime')";
						$res1 = sqlsrv_query($conn, $sql1);

						if ($res1) 
						{
							echo "<b style='color:green;'>Data for Leave request is fetched successfully!<br>Click the Button to send mail<br>";
						} 
						else 
						{
							//echo "Error submitting leave request.";
						}
						

							foreach ($_POST['compodate'] as $compodate) 
							{
								$sqlDelete = "DELETE FROM compo WHERE empid = ? AND compodate = ?";
								$params = array($empid, $compodate);
								$stmt = sqlsrv_query($conn, $sqlDelete, $params);

								if ($stmt) 
								{
									//echo "Compensatory date $compodate has been deleted.<br>";
								} 
								else 
								{
									//echo "Error deleting compensatory date $compodate.<br>";
								}
							}								

					}
					elseif($selectedLeaveType == '')
					{
						echo "<b style='color:red;'>Please select a Leave Type<br>";
					}
					elseif($selectedDays == '')
					{
						echo "<b style='color:red;'>Please select number of Days<br>";
					}
					elseif($selectedLeaveDate1 == '')
					{
						echo "<b style='color:red;'>Please select a Leave Date<br>";
					}
					elseif(!isset($compodatesStr[0]))
					{
						echo "<b style='color:red;'>Please select a Compo Date<br>";
					}					
					elseif($selectedReason == '')
					{
						echo "<b style='color:red;'>Please select a reason for leave<br>";
					}
					else
					{
						echo "<b style='color:red;'>Please select all required fields!";
					}
					
				}
				else //CASUAL
				{
					if ($selectedDays !='' && $selectedLeaveType!='' &&  $selectedLeaveDate1!='' && $selectedReason!='')//1 DATE for CASUAL
					{ 					
					
						$sql1 = "INSERT INTO leaverequest (empid, leavedate, compodate, reason, leavetype, time)
								 VALUES ('$empid', '$selectedLeaveDate1', '$compodatesStr[0]', '$formReason', '$selectedLeaveType', '$currentDateTime')";
						$res1 = sqlsrv_query($conn, $sql1);

						if ($res1) 
						{
							echo "<b style='color:green;'>Data for Leave request is fetched successfully!<br>Click the Button to send mail<br>";
						} 
						else 
						{
							//echo "Error submitting leave request.";
						}
					}
					elseif($selectedLeaveType == '')
					{
						echo "<b style='color:red;'>Please select a Leave Type<br>";
					}
					elseif($selectedDays == '')
					{
						echo "<b style='color:red;'>Please select number of Days<br>";

					}
					elseif($selectedLeaveDate1 == '')
					{
						echo "<b style='color:red;'>Please select a Leave Date<br>";
					}					
					elseif($selectedReason == '')
					{
						echo "<b style='color:red;'>Please select a reason for leave<br>";
					}
					else
					{
						echo "<b style='color:red;'>Please select all required fields!";
					}	
					
				}	
				
				
				
            } 
			elseif ($selectedDays == 2) //2 DAYS
			{
				
				$compodatesStr[2] = null;				
				
				if($selectedLeaveType=='COMPO')
				{
					
					if ($selectedDays !='' && $selectedLeaveType!='' && $selectedLeaveDate1!='' && $selectedLeaveDate2!='' && isset($compodatesStr[0]) && isset($compodatesStr[1]) && $selectedReason!='')//2 DATES for COMPO
					{ 
					
						$sql1 = "INSERT INTO leaverequest (empid, leavedate, compodate, reason, leavetype, time)
								 VALUES ('$empid', '$selectedLeaveDate1', '$compodatesStr[0]', '$formReason', '$selectedLeaveType', '$currentDateTime')";
						$sql2 = "INSERT INTO leaverequest (empid, leavedate, compodate, reason, leavetype, time)
								 VALUES ('$empid', '$selectedLeaveDate2', '$compodatesStr[1]', '$formReason', '$selectedLeaveType', '$currentDateTime')";
						$res1 = sqlsrv_query($conn, $sql1);
						$res2 = sqlsrv_query($conn, $sql2);

						if ($res1 && $res2) 
						{
							echo "<b style='color:green;'>Data for Leave request is fetched successfully!<br>Click the Button to send mail<br>";
						} 
						else 
						{
							//echo "Error submitting leave request.";
						}
						
						
							foreach ($_POST['compodate'] as $compodate) 
							{
								$sqlDelete = "DELETE FROM compo WHERE empid = ? AND compodate = ?";
								$params = array($empid, $compodate);
								$stmt = sqlsrv_query($conn, $sqlDelete, $params);

								if ($stmt) 
								{
									//echo "Compensatory date $compodate has been deleted.<br>";
								} 
								else 
								{
									//echo "Error deleting compensatory date $compodate.<br>";
								}
							}					
						
					
					}
					elseif($selectedLeaveType == '')
					{
						echo "<b style='color:red;'>Please select a Leave Type<br>";
					}
					elseif($selectedDays == '')
					{
						echo "<b style='color:red;'>Please select number of Days<br>";
					}
					elseif($selectedLeaveDate1 == '' && $selectedLeaveDate2 == '')
					{
						echo "<b style='color:red;'>Please select 2 Leave Dates<br>";
					}
					elseif(!isset($compodatesStr[0]) && !isset($compodatesStr[1]))
					{
						echo "<b style='color:red;'>Please select 2 Compo Dates<br>";
					}					
					elseif($selectedReason == '')
					{
						echo "<b style='color:red;'>Please select a reason for leave<br>";
					}
					else
					{
						echo "<b style='color:red;'>Please select all required fields!";
					}
					
				}
				else //CASUAL
				{

					if ($selectedDays !='' && $selectedLeaveType!='' && $selectedLeaveDate1!='' && $selectedLeaveDate2!='' && $selectedReason!='')//2 DATES for CASUAL
					{ 
						$sql1 = "INSERT INTO leaverequest (empid, leavedate, compodate, reason, leavetype, time)
								 VALUES ('$empid', '$selectedLeaveDate1', '$compodatesStr[0]', '$formReason', '$selectedLeaveType', '$currentDateTime')";
						$sql2 = "INSERT INTO leaverequest (empid, leavedate, compodate, reason, leavetype, time)
								 VALUES ('$empid', '$selectedLeaveDate2', '$compodatesStr[1]', '$formReason', '$selectedLeaveType', '$currentDateTime')";
						$res1 = sqlsrv_query($conn, $sql1);
						$res2 = sqlsrv_query($conn, $sql2);

						if ($res1 && $res2) 
						{
							echo "<b style='color:green;'>Data for Leave request is fetched successfully!<br>Click the Button to send mail<br>";
						} 
						else 
						{
							//echo "Error submitting leave request.";
						}
					}
					elseif($selectedLeaveType == '')
					{
						echo "<b style='color:red;'>Please select a Leave Type<br>";
					}
					elseif($selectedDays == '')
					{
						echo "<b style='color:red;'>Please select number of Days<br>";

					}
					elseif($selectedLeaveDate1 == '' && $selectedLeaveDate2 == '')
					{
						echo "<b style='color:red;'>Please select 2 Leave Dates<br>";
					}					
					elseif($selectedReason == '')
					{
						echo "<b style='color:red;'>Please select a reason for leave<br>";
					}
					else
					{
						echo "<b style='color:red;'>Please select all required fields!";
					}		
					
				}
				
				
            } 
			elseif ($selectedDays == 3) //3 DAYS
			{             
				if($selectedLeaveType=='COMPO')
				{
					if ($selectedDays !='' && $selectedLeaveType!=''&& $selectedLeaveDate1!='' && $selectedLeaveDate2!='' && $selectedLeaveDate3!='' && isset($compodatesStr[0]) && isset($compodatesStr[1]) && isset($compodatesStr[2]) && $selectedReason!='')//3 DATES for COMPO
					{ 
						
						$sql1 = "INSERT INTO leaverequest (empid, leavedate, compodate, reason, leavetype, time)
								 VALUES ('$empid', '$selectedLeaveDate1', '$compodatesStr[0]', '$formReason', '$selectedLeaveType', '$currentDateTime')";
						$sql2 = "INSERT INTO leaverequest (empid, leavedate, compodate, reason, leavetype, time)
								 VALUES ('$empid', '$selectedLeaveDate2', '$compodatesStr[1]', '$formReason', '$selectedLeaveType', '$currentDateTime')";
						$sql3 = "INSERT INTO leaverequest (empid, leavedate, compodate, reason, leavetype, time)
								 VALUES ('$empid', '$selectedLeaveDate3', '$compodatesStr[2]', '$formReason', '$selectedLeaveType', '$currentDateTime')";

						$res1 = sqlsrv_query($conn, $sql1);
						$res2 = sqlsrv_query($conn, $sql2);
						$res3 = sqlsrv_query($conn, $sql3);

						if ($res1 && $res2 && $res3) 
						{
							echo "<b style='color:green;'>Data for Leave request is fetched successfully!<br>Click the Button to send mail<br>";
						} 
						else 
						{
							//echo "Error submitting leave request.";
						}
	
							foreach ($_POST['compodate'] as $compodate) 
							{
								$sqlDelete = "DELETE FROM compo WHERE empid = ? AND compodate = ?";
								$params = array($empid, $compodate);
								$stmt = sqlsrv_query($conn, $sqlDelete, $params);

								if ($stmt) 
								{
									//echo "Compensatory date $compodate has been deleted.<br>";
								} 
								else 
								{
									//echo "Error deleting compensatory date $compodate.<br>";
								}
							}					
					
					}
					elseif($selectedLeaveType == '')
					{
						echo "<b style='color:red;'>Please select a Leave Type<br>";
					}
					elseif($selectedDays == '')
					{
						echo "<b style='color:red;'>Please select number of Days<br>";
					}
					elseif($selectedLeaveDate1 == '' && $selectedLeaveDate2 == '' && $selectedLeaveDate3 == '')
					{
						echo "<b style='color:red;'>Please select 3 Leave Dates<br>";
					}
					elseif(!isset($compodatesStr[0]) && !isset($compodatesStr[1]) && !isset($compodatesStr[2]))
					{
						echo "<b style='color:red;'>Please select 3 Compo Dates<br>";
					}					
					elseif($selectedReason == '')
					{
						echo "<b style='color:red;'>Please select a reason for leave<br>";
					}
					else
					{
						echo "<b style='color:red;'>Please select all required fields!";
					}					
					
				}
				else //CASUAL
				{
					if ($selectedDays !='' && $selectedLeaveType!='' && $selectedLeaveDate1!='' && $selectedLeaveDate2!='' && $selectedLeaveDate3!='' && $selectedReason!='')//3 DATES for CASUAL
					{ 					
					
						$sql1 = "INSERT INTO leaverequest (empid, leavedate, compodate, reason, leavetype, time)
								 VALUES ('$empid', '$selectedLeaveDate1', '$compodatesStr[0]', '$formReason', '$selectedLeaveType', '$currentDateTime')";
						$sql2 = "INSERT INTO leaverequest (empid, leavedate, compodate, reason, leavetype, time)
								 VALUES ('$empid', '$selectedLeaveDate2', '$compodatesStr[1]', '$formReason', '$selectedLeaveType', '$currentDateTime')";
						$sql3 = "INSERT INTO leaverequest (empid, leavedate, compodate, reason, leavetype, time)
								 VALUES ('$empid', '$selectedLeaveDate3', '$compodatesStr[2]', '$formReason', '$selectedLeaveType', '$currentDateTime')";

						$res1 = sqlsrv_query($conn, $sql1);
						$res2 = sqlsrv_query($conn, $sql2);
						$res3 = sqlsrv_query($conn, $sql3);

						if ($res1 && $res2 && $res3) 
						{
							echo "<b style='color:green;'>Data for Leave request is fetched successfully!<br>Click the Button to send mail<br>";
						} 
						else 
						{
							//echo "Error submitting leave request.";
						}						
				    }
					elseif($selectedLeaveType == '')
					{
						echo "<b style='color:red;'>Please select a Leave Type<br>";
					}
					elseif($selectedDays == '')
					{
						echo "<b style='color:red;'>Please select number of Days<br>";
					}
					elseif($selectedLeaveDate1 == '' && $selectedLeaveDate2 == '' && $selectedLeaveDate3 == '')
					{
						echo "<b style='color:red;'>Please select 3 Leave Dates<br>";
					}
					elseif($selectedReason == '')
					{
						echo "<b style='color:red;'>Please select a reason for leave<br>";
					}
					else
					{
						echo "<b style='color:red;'>Please select all required fields!";
					}					
				}

            }
			else
			{
				$daysmessage = "<b style='color:red;'>Please select number of Days<br>";
				echo $daysmessage;
			}	
        }
    }
} 
?>




<script>
    function updateDateInputs(selectedDays) 
	{
        document.getElementById('leavedate1').style.display = 'inline-block';
        document.getElementById('leavedate2').style.display = selectedDays >= 2 ? 'inline-block' : 'none';
        document.getElementById('leavedate3').style.display = selectedDays == 3 ? 'inline-block' : 'none';
    }
    updateDateInputs(<?php echo $selectedDays; ?>);
</script>



<script>


    function submitForm() 
	{
		const form = document.getElementById('leavetypeForm');
        form.submit();
    }
	
	
function submitcheckbox() 
{
 
    const form = document.getElementById('leavetypeForm');

    const leavedate1 = document.getElementById('leavedate1').value.trim();
    const leavedate2 = document.getElementById('leavedate2').value.trim();
    const leavedate3 = document.getElementById('leavedate3').value.trim();
    const reason = document.getElementById('reason').value.trim();
    const days = document.getElementById('days').value.trim();
    const leavetype = document.getElementById('leavetype').value.trim();
    const compodate = document.getElementById('compodate') ? document.getElementById('compodate').value.trim() : ""; 
	
	const checkbox = document.getElementById('confirmationCheckbox');
    const submitButton = document.getElementById('submitButton');

	if (leavedate1)
	{    
		if (leavedate1 ==  leavedate2) 
		{
			alert("Same Leave Dates cannot be choosed");
			return;		
		}
		
		if (leavedate1 ==  leavedate3) 
		{
			alert("Same Leave Dates cannot be choosed");
			return;		
		}
		
	}	

	if (leavedate2)
	{
		if (leavedate2 ==  leavedate3) 
		{
			alert("Same Leave Dates cannot be choosed");
			return;		
		} 

	}
	 
	if (leavedate3)
	{ 
		if (leavedate1 ==  leavedate2 ==  leavedate2) 
		{
			alert("Same Leave Dates cannot be choosed");
			return;		
		}	

	}

	
    if (leavetype === "CASUAL") 
	{
        if(days == 1)
		{	
			if (leavedate1 === "" || reason === "" || days === "" || leavetype === "") 
			{
				alert("Please fill out all required fields for Casual leave before submitting.");
				return; 
			}
		}
        if(days == 2)
		{	
			if (leavedate1 === "" || leavedate2 === "" || reason === "" || days === "" || leavetype === "") 
			{
				alert("Please fill out all required fields for Casual leave before submitting.");
				return; 
			}
		}
        if(days == 3)
		{	
			if (leavedate1 === "" || leavedate2 === "" || leavedate3 === "" || reason === "" || days === "" || leavetype === "") 
			{
				alert("Please fill out all required fields for Casual leave before submitting.");
				return; 
			}
		}		
    } 
	else if (leavetype === "COMPO") 
	{   
        if(days == 1)
		{
			if (leavedate1 === "" || reason === "" || days === "" || leavetype === "") 
			{
				alert("Please fill out all required fields for Compo leave before submitting.");
				return; 
			}
		}
        if(days == 2)
		{	
			if (leavedate1 === "" || leavedate2 === "" || reason === "" || days === "" || leavetype === "") 
			{
				alert("Please fill out all required fields for Compo leave before submitting.");
				return; 
			}
		}
        if(days == 3)
		{	
			if (leavedate1 === "" || leavedate2 === "" || leavedate3 === "" || reason === "" || days === "" || leavetype === "") 
			{
				alert("Please fill out all required fields for Compo leave before submitting.");
				return; 
			}
		}				
    }
	else if (!leavedate1 || !leavedate2 || !leavedate3)
	{
        alert("Select a Leave date");
        return;		
	}	
	else 
	{
        alert("Select a Leavetype");
        return;
    }
	
    // Enable or disable the submit button based on checkbox status
	if(!checkbox.checked)
	{
		alert("Confirm Checkbox");
	}
    submitButton.disabled = !checkbox.checked;
	

    form.submit();
	
}






/* function submitcheckbox() 
{
	 
    const form = document.getElementById('leavetypeForm');

    const leavedate1 = document.getElementById('leavedate1').value.trim();
    const leavedate2 = document.getElementById('leavedate2').value.trim();
    const leavedate3 = document.getElementById('leavedate3').value.trim();
    const reason = document.getElementById('reason').value.trim();
    const days = document.getElementById('days').value.trim();
    const leavetype = document.getElementById('leavetype').value.trim();
    const compodate = document.getElementById('compodate') ? document.getElementById('compodate').value.trim() : "";

    const checkbox = document.getElementById('confirmationCheckbox');
    const submitButton = document.getElementById('submitButton');

    // Initially disable checkbox and submit button
    checkbox.checked = false;
    submitButton.disabled = true;


    let isValid = true;


    if (leavetype === "") 
	{
        alert("Select a Leave type");
        isValid = false;
    }


    if (leavetype === "CASUAL") 
	{
        if (!days) 
		{
            alert("Please fill out all required fields for Casual leave before submitting.");
            isValid = false;
        }        
		
		if ((days == 1 && (leavedate1 === "" || reason === "")) ||
            (days == 2 && (leavedate1 === "" || leavedate2 === "" || reason === "")) ||
            (days == 3 && (leavedate1 === "" || leavedate2 === "" || leavedate3 === "" || reason === ""))) 
		{
            alert("Please fill out all required fields for Casual leave before submitting.");
            isValid = false;
        }
    }


    if (leavetype === "COMPO") 
	{
        if (!days) 
		{
            alert("Please fill out all required fields for Casual leave before submitting.");
            isValid = false;
        }  

        if ((days == 1 && (leavedate1 === "" || reason === "")) ||
            (days == 2 && (leavedate1 === "" || leavedate2 === "" || reason === "")) ||
            (days == 3 && (leavedate1 === "" || leavedate2 === "" || leavedate3 === "" || reason === ""))) 
		{
            alert("Please fill out all required fields for Compo leave before submitting.");
            isValid = false;
        }
    }
	

    if ((leavedate1 && leavedate1 === leavedate2) || 
        (leavedate1 && leavedate1 === leavedate3) || 
        (leavedate2 && leavedate2 === leavedate3)) 
	{
        alert("Same Leave Dates cannot be chosen");
        isValid = false;
    }




    // Enable checkbox only if all conditions are satisfied
    if (isValid == true) 
	{
        checkbox.checked = true;
        submitButton.disabled = false;
        form.submit(); // Submit the form if valid
    } 
	else 
	{
        checkbox.checked = false;
        submitButton.disabled = true;
    }
	
	
}  */




	
</script>

<?php

?>

		 
<form name="main" method="post" 
<?php if($empid=='1004') //YAHOO USERS 
{

$compodatesStr[1] = '';
$compodatesStr[2] = '';
$selectedCompodates = isset($_POST['compodate']) ? $_POST['compodate'] : [];

            $compodatesStr = $selectedCompodates;
			
			if(!$selectedCompodates)
			{
				$compodatesStr[0] = '';
				$compodatesStr[1] = '';
				$compodatesStr[2] = '';				
			}			


			if (isset($compodatesStr[0]) && !isset($compodatesStr[1]) && !isset($compodatesStr[2]))// 1 DATE 
			{ 
				$compo3 = '';
				$compo2 = '';
				$compodatesStr[1] = '';
				$compodatesStr[2] = '';
				$compodatesString[0] = new DateTime($compodatesStr[0]);
				$compo1 = $compodatesString[0]->format('jS F Y');  
			}

			if (isset($compodatesStr[0]) && isset($compodatesStr[1]) && !isset($compodatesStr[2]))// 2 DATES 
			{ 
				$compo3 = '';
				$compodatesStr[2] = '';
				$compodatesString[0] = new DateTime($compodatesStr[0]);
				$compo1 = $compodatesString[0]->format('jS F Y');  
				$compodatesString[1] = new DateTime($compodatesStr[1]);
				$compo2 = $compodatesString[1]->format('jS F Y');  
			}

			if (isset($compodatesStr[0]) && isset($compodatesStr[1]) && isset($compodatesStr[2]))// 3 DATES 
			{ 
				$compodatesString[0] = new DateTime($compodatesStr[0]);
				$compo1 = $compodatesString[0]->format('jS F Y');  
				$compodatesString[1] = new DateTime($compodatesStr[1]);
				$compo2 = $compodatesString[1]->format('jS F Y');  
				$compodatesString[2] = new DateTime($compodatesStr[2]);
				$compo3 = $compodatesString[2]->format('jS F Y');  
			}


	$name = ucwords(strtolower($name));
	$designation = ucwords(strtolower($designation));
	$des = explode(' ',$designation);
	$d1 = $des[0];
	$d2 = $des[1];

	if($selectedLeaveType=='COMPO')
	{	

		if($selectedDays)
		{
			?> action="https://compose.mail.yahoo.com/?to=info@mefs.in&subject=Request%20for%20Compensatory%20Leave%20on%20<?php echo $emaildate; ?>%20<?php if($secondDayemaildate!=''){ echo $coma; ?>%20<?php echo $secondDayemaildate; } ?>%20<?php if($thirdDayemaildate!=''){ echo $and; ?>%20<?php echo $thirdDayemaildate; } ?>&body=%0D%0ARespected%20sir,%0D%0A%0D%0AI%20hope%20this%20message%20finds%20you%20well.%20I%20am%20writing%20to%20kindly%20request%20leave%20for%20<?php echo $selectedDays; ?>%20<?php echo $day; ?>%20on%20<?php echo $currentDay; ?>%20<?php echo $emaildate; ?>%20<?php if($secondDayemaildate!=''){ echo $coma; ?>%20<?php echo $secondDaycurrentDay; ?>%20<?php echo $secondDayemaildate; } ?>%20<?php if($thirdDayemaildate!=''){ echo $and; ?>%20<?php echo $thirdDaycurrentDay; ?>%20<?php echo $thirdDayemaildate; } ?>%20as%20I%20<?php echo $selectedReason; ?>.%0A%0APlease%20consider%20this%20as%20compensatory%20leave%20as%20I%20have%20worked%20on%20<?php echo $compo1; ?>%20<?php if($secondDayemaildate!=''){ echo $coma; ?>%20<?php echo $compo2; }?>%20<?php if($thirdDayemaildate!=''){ echo $and; ?>%20<?php echo $compo3; }?>.%0A%0AI%20assure%20you%20that%20I%20will%20complete%20any%20pending%20tasks%20before%20my%20leave%20and%20ensure%20a%20smooth%20workflow%20during%20my%20absence.%0A%0AThank%20you%20for%20your%20understanding%20and%20consideration.%0A%0A%0A%0A--%0AThanks%20and%20regards,%0A<?php echo $name;?>%0A<?php echo $d1; ?>%20Software%20<?php echo $d2; ?>%0AMiddle%20East%20Financial%20Software%20Solutions,%0APurakkad%20Square,%20Vyttila,%20Kochi%0A<?php echo $mob;?>/<?php echo $email;?>"  target="_blank" onsubmit="return confirmSubmission();" <?php	 			
		}
		else
		{	
			?> action="https://compose.mail.yahoo.com/?to=info@mefs.in&subject=Request%20for%20Compensatory%20Leave%20on%20<?php echo $emaildate; ?>%20<?php if($secondDayemaildate!=''){ echo $coma; ?>%20<?php echo $secondDayemaildate; } ?>%20<?php if($thirdDayemaildate!=''){ echo $and; ?>%20<?php echo $thirdDayemaildate; } ?>&body=%0D%0ARespected%20sir,%0D%0A%0D%0AI%20hope%20this%20message%20finds%20you%20well.%20I%20am%20writing%20to%20kindly%20request%20leave%20for%20<?php echo $selectedDays; ?>%20<?php echo $day; ?>%20on%20<?php echo $currentDay; ?>%20<?php echo $emaildate; ?>%20<?php if($secondDayemaildate!=''){ echo $coma; ?>%20<?php echo $secondDaycurrentDay; ?>%20<?php echo $secondDayemaildate; } ?>%20<?php if($thirdDayemaildate!=''){ echo $and; ?>%20<?php echo $thirdDaycurrentDay; ?>%20<?php echo $thirdDayemaildate; } ?>%20as%20I%20<?php echo $selectedReason; ?>.%0A%0APlease%20consider%20this%20as%20compensatory%20leave%20as%20I%20have%20worked%20on%20<?php echo $compo1; ?>%20<?php if($secondDayemaildate!=''){ echo $coma; ?>%20<?php echo $compo2; }?>%20<?php if($thirdDayemaildate!=''){ echo $and; ?>%20<?php echo $compo3; }?>.%0A%0AI%20assure%20you%20that%20I%20will%20complete%20any%20pending%20tasks%20before%20my%20leave%20and%20ensure%20a%20smooth%20workflow%20during%20my%20absence.%0A%0AThank%20you%20for%20your%20understanding%20and%20consideration.%0A%0A%0A%0A--%0AThanks%20and%20regards,%0A<?php echo $name;?>%0A<?php echo $d1; ?>%20Software%20<?php echo $d2; ?>%0AMiddle%20East%20Financial%20Software%20Solutions,%0APurakkad%20Square,%20Vyttila,%20Kochi%0A<?php echo $mob;?>/<?php echo $email;?>"  target="_blank" onsubmit="return confirmSubmission();" <?php	
		
	   }
    }	   
	else
	{

		if($selectedDays)
		{
			?> action="https://compose.mail.yahoo.com/?to=info@mefs.in&subject=Request%20for%20Leave%20on%20<?php echo $emaildate; ?>%20<?php if($secondDayemaildate!=''){ echo $coma; ?>%20<?php echo $secondDayemaildate; } ?>%20<?php if($thirdDayemaildate!=''){ echo $and; ?>%20<?php echo $thirdDayemaildate; } ?>&body=%0D%0ARespected%20sir,%0D%0A%0D%0AI%20hope%20this%20message%20finds%20you%20well.%20I%20am%20writing%20to%20kindly%20request%20leave%20for%20<?php echo $selectedDays; ?>%20<?php echo $day; ?>%20on%20<?php echo $currentDay; ?>%20<?php echo $emaildate; ?>%20<?php if($secondDayemaildate!=''){ echo $coma; ?>%20<?php echo $secondDaycurrentDay; ?>%20<?php echo $secondDayemaildate; } ?>%20<?php if($thirdDayemaildate!=''){ echo $and; ?>%20<?php echo $thirdDaycurrentDay;} ?>%20<?php echo $thirdDayemaildate; ?>%20as%20I%20<?php echo $selectedReason; ?>.%0D%0A%0D%0AI%20assure%20you%20that%20I%20will%20complete%20any%20pending%20tasks%20before%20my%20leave%20and%20ensure%20a%20smooth%20workflow%20during%20my%20absence.%0D%0A%0D%0AThank%20you%20for%20your%20understanding%20and%20consideration.%0D%0A%0D%0A--%0D%0AThanks%20and%20regards,%0D%0A<?php echo $name; ?>%0D%0A<?php echo $d1; ?>%20Software%20<?php echo $d2; ?>%0AMiddle%20East%20Financial%20Software%20Solutions,%0APurakkad%20Square,%20Vyttila,%20Kochi%0A<?php echo $mob; ?>/<?php echo $email; ?>" target="_blank" onsubmit="return confirmSubmission();"	<?php
		}
		else
		{
			?> action="https://compose.mail.yahoo.com/?to=info@mefs.in&subject=Request%20for%20Leave%20on%20<?php echo $emaildate; ?>%20&body=%0D%0ARespected%20sir,%0D%0A%0D%0AI%20hope%20this%20message%20finds%20you%20well.%20I%20am%20writing%20to%20kindly%20request%20leave%20for<?php echo $selectedDays; ?>%20days%20on%20<?php echo $currentDay; ?>,%20<?php echo $emaildate; ?>,%20as%20I%20<?php echo $selectedReason; ?>.%0D%0A%0D%0AI%20assure%20you%20that%20I%20will%20complete%20any%20pending%20tasks%20before%20my%20leave%20and%20ensure%20a%20smooth%20workflow%20during%20my%20absence.%0D%0A%0D%0AThank%20you%20for%20your%20understanding%20and%20consideration.%0D%0A%0D%0A--%0D%0AThanks%20and%20regards,%0D%0A<?php echo $name; ?>%0D%0A<?php echo $d1; ?>%20Software%20<?php echo $d2; ?>%0AMiddle%20East%20Financial%20Software%20Solutions,%0APurakkad%20Square,%20Vyttila,%20Kochi%0A<?php echo $mob; ?>/<?php echo $email; ?>" target="_blank" onsubmit="return confirmSubmission();"	<?php			
		}	
	}
}
if($empid!='1004') //GMAIL USERS
{ 
$compodatesStr[1] = '';
$compodatesStr[2] = '';
$selectedCompodates = isset($_POST['compodate']) ? $_POST['compodate'] : [];

            $compodatesStr = $selectedCompodates;
			
			if(!$selectedCompodates)
			{
				$compodatesStr[0] = '';
				$compodatesStr[1] = '';
				$compodatesStr[2] = '';				
			}			


			if (isset($compodatesStr[0]) && !isset($compodatesStr[1]) && !isset($compodatesStr[2]))// 1 DATE 
			{ 
				$compo3 = '';
				$compo2 = '';
				$compodatesStr[1] = '';
				$compodatesStr[2] = '';
				$compodatesString[0] = new DateTime($compodatesStr[0]);
				$compo1 = $compodatesString[0]->format('jS F Y');  
			}

			if (isset($compodatesStr[0]) && isset($compodatesStr[1]) && !isset($compodatesStr[2]))// 2 DATES 
			{ 
				$compo3 = '';
				$compodatesStr[2] = '';
				$compodatesString[0] = new DateTime($compodatesStr[0]);
				$compo1 = $compodatesString[0]->format('jS F Y');  
				$compodatesString[1] = new DateTime($compodatesStr[1]);
				$compo2 = $compodatesString[1]->format('jS F Y');  
			}

			if (isset($compodatesStr[0]) && isset($compodatesStr[1]) && isset($compodatesStr[2]))// 3 DATES 
			{ 
				$compodatesString[0] = new DateTime($compodatesStr[0]);
				$compo1 = $compodatesString[0]->format('jS F Y');  
				$compodatesString[1] = new DateTime($compodatesStr[1]);
				$compo2 = $compodatesString[1]->format('jS F Y');  
				$compodatesString[2] = new DateTime($compodatesStr[2]);
				$compo3 = $compodatesString[2]->format('jS F Y');  
			}



	$name = ucwords(strtolower($name));
	$designation = ucwords(strtolower($designation));
	$des = explode(' ',$designation);
	$d1 = $des[0];
	$d2 = $des[1];
	if($d1=='Software'){$d1='';}
	
	if($selectedLeaveType=='CASUAL')
	{	
		if($selectedDays)
		{
			?> action="https://mail.google.com/mail/?view=cm&fs=1&to=info@mefs.in&su=Request%20for%20Leave%20on%20<?php echo $emaildate;?>%20<?php if($secondDayemaildate!=''){ echo $coma; ?>%20<?php echo $secondDayemaildate; } ?>%20<?php if($thirdDayemaildate!=''){ echo $and; ?>%20<?php echo $thirdDayemaildate; } ?>&body=%0ARespected%20sir,%0A%0AI%20hope%20this%20message%20finds%20you%20well.%20I%20am%20writing%20to%20kindly%20request%20leave%20for%20<?php echo $selectedDays; ?>%20<?php echo $day; ?>%20on%20<?php echo $currentDay; ?>%20<?php echo $emaildate; ?>%20<?php if($secondDayemaildate!=''){ echo $coma; ?>%20<?php echo $secondDaycurrentDay; ?>%20<?php echo $secondDayemaildate; } ?>%20<?php if($thirdDayemaildate!=''){ echo $and; ?>%20<?php echo $thirdDaycurrentDay; ?>%20<?php echo $thirdDayemaildate; } ?>%20as%20I%20<?php echo $selectedReason; ?>.%0A%0AI%20assure%20you%20that%20I%20will%20complete%20any%20pending%20tasks%20before%20my%20leave%20and%20ensure%20a%20smooth%20workflow%20during%20my%20absence.%0A%0AThank%20you%20for%20your%20understanding%20and%20consideration.%0A%0A%0A%0A--%0AThanks%20and%20regards,%0A<?php echo $name;?>%0A<?php echo $d1; ?>%20Software%20<?php echo $d2; ?>%0AMiddle%20East%20Financial%20Software%20Solutions,%0APurakkad%20Square,%20Vyttila,%20Kochi%0A<?php echo $mob;?>/<?php echo $email;?>"  target="_blank" onsubmit="return confirmSubmission();" <?php 
		} 
		else
		{
			?> action="https://mail.google.com/mail/?view=cm&fs=1&to=info@mefs.in&su=Request%20for%20Leave%20on%20<?php echo $emaildate;?>&body=%0ARespected%20sir,%0A%0AI%20hope%20this%20message%20finds%20you%20well.%20I%20am%20writing%20to%20kindly%20request%20leave%20for%20one%20day%20on%20<?php echo $currentDay;?>,%20<?php echo $emaildate;?>,%20as%20I%20<?php echo $selectedReason; ?>.%0A%0AI%20assure%20you%20that%20I%20will%20complete%20any%20pending%20tasks%20before%20my%20leave%20and%20ensure%20a%20smooth%20workflow%20during%20my%20absence.%0A%0AThank%20you%20for%20your%20understanding%20and%20consideration.%0A%0A%0A%0A--%0AThanks%20and%20regards,%0A<?php echo $name;?>%0A<?php echo $d1; ?>%20Software%20<?php echo $d2; ?>%0AMiddle%20East%20Financial%20Software%20Solutions,%0APurakkad%20Square,%20Vyttila,%20Kochi%0A<?php echo $mob;?>/<?php echo $email;?>"  target="_blank" onsubmit="return confirmSubmission();" <?php 			
		}		
	}
	else
	{ 
		if($selectedDays)
		{
			?> action="https://mail.google.com/mail/?view=cm&fs=1&to=info@mefs.in&su=Request%20for%20Compensatory%20Leave%20on%20<?php echo $emaildate; ?>%20<?php if($secondDayemaildate!=''){ echo $coma; ?>%20<?php echo $secondDayemaildate; } ?>%20<?php if($thirdDayemaildate!=''){ echo $and; ?>%20<?php echo $thirdDayemaildate; } ?>&body=%0D%0ARespected%20sir,%0D%0A%0D%0AI%20hope%20this%20message%20finds%20you%20well.%20I%20am%20writing%20to%20kindly%20request%20leave%20for%20<?php echo $selectedDays; ?>%20<?php echo $day; ?>%20on%20<?php echo $currentDay; ?>%20<?php echo $emaildate; ?>%20<?php if($secondDayemaildate!=''){ echo $coma; ?>%20<?php echo $secondDaycurrentDay; ?>%20<?php echo $secondDayemaildate; } ?>%20<?php if($thirdDayemaildate!=''){ echo $and; ?>%20<?php echo $thirdDaycurrentDay; ?>%20<?php echo $thirdDayemaildate; } ?>%20as%20I%20<?php echo $selectedReason; ?>.%0A%0APlease%20consider%20this%20as%20compensatory%20leave%20as%20I%20have%20worked%20on%20<?php echo $compo1; ?>%20<?php if($secondDayemaildate!=''){ echo $coma; ?>%20<?php echo $compo2; }?>%20<?php if($thirdDayemaildate!=''){ echo $and; ?>%20<?php echo $compo3; }?>.%0A%0AI%20assure%20you%20that%20I%20will%20complete%20any%20pending%20tasks%20before%20my%20leave%20and%20ensure%20a%20smooth%20workflow%20during%20my%20absence.%0A%0AThank%20you%20for%20your%20understanding%20and%20consideration.%0A%0A%0A%0A--%0AThanks%20and%20regards,%0A<?php echo $name;?>%0A<?php echo $d1; ?>%20Software%20<?php echo $d2; ?>%0AMiddle%20East%20Financial%20Software%20Solutions,%0APurakkad%20Square,%20Vyttila,%20Kochi%0A<?php echo $mob;?>/<?php echo $email;?>"  target="_blank" onsubmit="return confirmSubmission();" <?php			
		}
		else
		{
			?> action="https://mail.google.com/mail/?view=cm&fs=1&to=info@mefs.in&su=Request%20for%20Compensatory%20Leave%20on%20<?php echo $emaildate; ?>%20<?php if($secondDayemaildate!=''){ echo $coma; ?>%20<?php echo $secondDayemaildate; } ?>%20<?php if($thirdDayemaildate!=''){ echo $and; ?>%20<?php echo $thirdDayemaildate; } ?>&body=%0D%0ARespected%20sir,%0D%0A%0D%0AI%20hope%20this%20message%20finds%20you%20well.%20I%20am%20writing%20to%20kindly%20request%20leave%20for%20<?php echo $selectedDays; ?>%20<?php echo $day; ?>%20on%20<?php echo $currentDay; ?>%20<?php echo $emaildate; ?>%20<?php if($secondDayemaildate!=''){ echo $coma; ?>%20<?php echo $secondDaycurrentDay; ?>%20<?php echo $secondDayemaildate; } ?>%20<?php if($thirdDayemaildate!=''){ echo $and; ?>%20<?php echo $thirdDaycurrentDay; ?>%20<?php echo $thirdDayemaildate; } ?>%20as%20I%20<?php echo $selectedReason; ?>.%0A%0APlease%20consider%20this%20as%20compensatory%20leave%20as%20I%20have%20worked%20on%20<?php echo $compo1; ?>%20<?php if($secondDayemaildate!=''){ echo $coma; ?>%20<?php echo $compo2; }?>%20<?php if($thirdDayemaildate!=''){ echo $and; ?>%20<?php echo $compo3; }?>.%0A%0AI%20assure%20you%20that%20I%20will%20complete%20any%20pending%20tasks%20before%20my%20leave%20and%20ensure%20a%20smooth%20workflow%20during%20my%20absence.%0A%0AThank%20you%20for%20your%20understanding%20and%20consideration.%0A%0A%0A%0A--%0AThanks%20and%20regards,%0A<?php echo $name;?>%0A<?php echo $d1; ?>%20Software%20<?php echo $d2; ?>%0AMiddle%20East%20Financial%20Software%20Solutions,%0APurakkad%20Square,%20Vyttila,%20Kochi%0A<?php echo $mob;?>/<?php echo $email;?>"  target="_blank" onsubmit="return confirmSubmission();" <?php				
		}
		
    }
	
}
else{}?> 
>

		 
		 
		<input type="hidden" name="empid" id="empid" value="<?php echo $empid; ?>">
		<input type="hidden" name="username" id="username" value="<?php echo $username; ?>">
		<input type="hidden" name="name" id="name" value="<?php echo $name; ?>">
		<input type="hidden" name="designation" id="designation" value="<?php echo $designation; ?>">
		<input type="hidden" name="email" id="email" value="<?php echo $email; ?>">
		<input type="hidden" name="mob" id="mob" value="<?php echo $mob; ?>">
		<input type="hidden" name="emptype" id="emptype" value="<?php echo $emptype; ?>">
		<input type="hidden" name="password" id="password" value="<?php echo $password; ?>">
		<br>

		<input type="submit" name="submit" value="SEND LEAVE REQUEST" class="sub" title="click to send Leave Request"  id="submitButton">
		<br><br><br>	
		
		
		
		<script>
		
		
		let selectedLeaveType = ''; 
		
		function updateLeaveType() 
		{ 
			const leaveTypeSelect = document.getElementById('leavetype'); 
			const selectedLeaveType = leaveTypeSelect.value; 

			var xhr = new XMLHttpRequest();
			xhr.open("POST", "getleavetype.php", true);
			xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr.send("leavetype=" + selectedLeaveType);

			xhr.onreadystatechange = function() 
			{
				if (xhr.readyState == 4 && xhr.status == 200) 
				{
					console.log("Server response:", xhr.responseText);
				}
			};	
			
		}
	
		
		function confirmSubmission() 
		{
			const leaveTypeSelect = document.getElementById('leavetype'); 
			selectedLeaveType = leaveTypeSelect.value; 			

			const leavedate1 = document.getElementById('leavedate1').value.trim();
			const reason = document.getElementById('reason').value.trim();
			const days = document.getElementById('days').value.trim();
			const leavetype = document.getElementById('leavetype').value.trim();			
			const checkbox = document.getElementById('confirmationCheckbox');

			if (!leavedate1 || !reason || !days || !leavetype) 
			{
				alert("Please select all required fields before sending mail.");
				return false; // Prevent form submission
			}
    
			if (!checkbox.checked) 
			{
				alert("Please confirm the checkbox to proceed.");
				return false; // Prevent form submission
			}
			return confirm("Are you sure you want to request for leave?");
		}
		
		</script>		

</form>


<!--<pre>
Note
Before submitting your leave request, please ensure that:
<b>You have entered the correct leave dates.</b>
<b>Your request complies with company leave policies.</b>
<b>You have notified your supervisor or team lead.</b>

If you have any queries, please contact Head of Operations.
</pre>-->

</center>
</div>

<?php
}
else
{
?>
<center>
<br><br><br><br><br>
<div style='background-color:#FFFFFF;border-radius:10px;width:35%;margin-right:140;height:180px;box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);'>
	<b><br><br><br>
	<?php
	echo 'ACCESS DENIED';
	?>
</div>
</center>
</b>
	
<?php	
}	


?>

</body>
</html>