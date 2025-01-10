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
	background-color:#28373E;
	text-align:center;
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
.edit-icon 
{
    width: 20px;
    height: 20px;
    display: block;
    margin: auto;
    cursor: pointer;
}
.edit-icon:hover 
{
    transform: scale(1.2);
}
.dis
{
 border:1px solid black;	
}
tr.dis:hover 
{
	background-color:#cbcdd1;
}
tbody tr:nth-child(odd)
{
	background-color:#e6eaf0;	
}

tbody tr:nth-child(even)
{
	background-color:#f2f3f5;	
}



        .popup-background 
		{
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        /* Popup Content */
        .popup-content
		{
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .popup-content p 
		{
            font-size: 16px;
            margin-bottom: 20px;
        }

        /* Buttons */
        .popup-buttons button 
		{
            font-size: 14px;
            padding: 10px 20px;
            margin: 0 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .popup-buttons .approve-btn 
		{
            background-color: #4CAF50;
            color: white;
        }

        .popup-buttons .reject-btn 
		{
            background-color: #f44336;
            color: white;
        }

        /* Close button for popup */
        .popup-buttons .close-btn 
		{
            background-color: #ccc;
            color: black;
        }
		
		/* Close Button as X */
		.close-btn 
		{
			position: absolute;
			top: 0px;
			right: 15px;
			font-size: 30px;
			font-weight: bold;
			color: #333;
			cursor: pointer;
			background: none;
			border: none;
			outline: none;
		}
		
		.close-btn:hover 
		{
			color: #f44336; /* Red on hover */
		}

		
.profile-pic1 
{
    opacity: 100%;
    z-index: 999;
    border-radius: 50px;
    width: 30px; /* Set the width of the image */
    height: 30px; /* Set the height of the image */
}		


		
</style>
</head>
<body style="caret-color:transparent;" bottommargin="0" topmargin="0" leftmargin="0" rightmargin="0">	



<?php
session_start();
include "connect.php";
include "header2.html";
$var1="YOU HAVE MARKED";
$var2="AS LEAVE ON";




if(isset($_POST['empid']))
{
    $empid = $_POST['empid'];
}

$reason='';
if(isset($_POST['reason']))
{
    $reason = $_POST['reason'];
}

$compodate='';
if(isset($_POST['compodate']))
{
    $compodate = $_POST['compodate'];
}


if(isset($_POST['leavedate']))
{
    $leavedate = $_POST['leavedate'];
	$_SESSION['selectedLeavedate'] = $leavedate;
}



if(isset($_POST['leavetype']))
{
    $leavetype = $_POST['leavetype'];
	$_SESSION['selectedLeavetype'] = $leavetype;
}





    $options = [];////////////////////
		
	$sql3 = "SELECT name,empid FROM emp WITH (NOLOCK) WHERE flag='Y' ORDER BY name";
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
  
   $sql2 = "  INSERT INTO [MISGlobal].[dbo].[leave] (empid, leavedate, leavetype, compodate, reason) SELECT $empid , '$leavedate', '$leavetype', '$compodate', '$reason' WHERE NOT EXISTS ( SELECT 1 FROM [MISGlobal].[dbo].[leave] WHERE empid = $empid AND leavedate ='$leavedate')";
   $res2=sqlsrv_query($conn, $sql2,);
   

	$sql4 = "SELECT * FROM [MISGlobal].[dbo].[leave] WHERE empid='$empid'";
	
    $res4 = sqlsrv_query($conn, $sql4);
    while($res4 && $row4 = sqlsrv_fetch_array($res4, SQLSRV_FETCH_ASSOC))
    {
		$leavedate = isset($row4['leavedate']) ? trim($row4['leavedate']) : '';
		$leavetype = isset($row4['leavetype']) ? trim($row4['leavetype']) : '';
		$empid = isset($row4['empid']) ? trim($row4['empid']) : '';
		$reason = isset($row4['reason']) ? trim($row4['reason']) : '';
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
            $reason = isset($row5['reason']) ? trim($row5['reason']) : '';
        }
    }
	
	$date = DateTime::createFromFormat('Y-m-d', $leavedate);
	$ld = $date->format('d-m-Y');
    $rowsAffected = sqlsrv_rows_affected($res2);
        
		if ($rowsAffected > 0) 
		{
            $_SESSION['message'] = "<p style='color:black;caret-color:transparent;font-family:poppins;margin-left:430;'>You have marked <strong>$name</strong> as $leavetype leave on <strong>$ld</strong></p>";
        } 
		else 
		{
            $_SESSION['message'] = "<b><p style='color:red;caret-color:transparent;font-family:poppins;margin-left:440;'>Leave has already been marked for $name on $ld</p></b>";
        }
	  
	
	  
	header("Location: markleave.php");
    exit();
}
$selectedLeavedate = isset($_SESSION['selectedLeavedate']) ? $_SESSION['selectedLeavedate'] : '';
$selectedLeavetype = isset($_SESSION['selectedLeavetype']) ? $_SESSION['selectedLeavetype'] : '';
$trdata = '';
$compodate = '';
$formattedCompodate = '';

		date_default_timezone_set("Asia/Kolkata");
        $time = date('Y-m-d H:i:s');
		


$profilePic='';


    $sql2 = "select DISTINCT empid, leavedate, compodate, reason, leavetype, time from leaverequest order by time desc";
    $res2 = sqlsrv_query($conn, $sql2);
    while ($row2 = sqlsrv_fetch_array($res2, SQLSRV_FETCH_ASSOC)) 
	{

        if ($row2['empid'] == NULL) 
		{
            $row2['empid'] = '';
        }
        $empid = trim($row2['empid']);
		
        if ($row2['leavedate'] == NULL) 
		{
            $row2['leavedate'] = '';
        }
        $leavedate = trim($row2['leavedate']);
		
		$date1 = DateTime::createFromFormat('Y-m-d', $leavedate);

        $formattedleavedate = $date1->format('d-m-Y');		
		
        if ($row2['leavetype'] == NULL) 
		{
            $row2['leavetype'] = '';
        }
        $leavetype = trim($row2['leavetype']);	
		
        if ($row2['reason'] == NULL) 
		{
            $row2['reason'] = '';
        }
        $reason = trim($row2['reason']);
		
        if ($row2['time'] == NULL) 
		{
            $row2['time'] = '';
        }
        $time = trim($row2['time']);
		
		$timeObj = new DateTime($time);
		$formattedTime = $timeObj->format('jS F g:ia');	
		
        if ($row2['compodate'] == NULL) 
		{
            $row2['compodate'] = '';
        }
        $compodate = trim($row2['compodate']);
		
		$compodate1 = $compodate;
		
			//$currentDate = new DateTime();
			if($compodate)
			{
				$compodateObj = new DateTime($compodate);
				$formattedCompodate = $compodateObj->format('d-m-Y');				
			}
			if(!$compodate)
			{
				$formattedCompodate = '-';
			}	


		$sql10 = "SELECT name,gender FROM emp WITH (NOLOCK) WHERE empid = '$empid'";	   
		$res10 = sqlsrv_query($conn, $sql10);
		if ($res10) 
		{
			while ($row10 = sqlsrv_fetch_array($res10, SQLSRV_FETCH_ASSOC)) 
			{
				$name = isset($row10['name']) ? trim($row10['name']) : '';
				$gender = isset($row10['gender']) ? trim($row10['gender']) : '';
			}
        }			
	
	
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


	
		$color = '';
		
        if($leavetype=='COMPO')
		{
			$color = "background-color:#b8f0ad;";
		}
	

 
        $trdata .= "
        <tr class='dis' style='height:20px;'>
		
        <td align=center style='width:260;font-family:Poppins;padding:12px;'>
		<div style = 'margin-top:-23px;'>
		<img src='$profilePic' style='padding:11px;margin-bottom:-40px;margin-left:-210px;width:45px;height:45px;border-radius:50%;'>
		<div style='margin-right:-60px;margin-top:-8px;text-align:center;padding:1px;'>$name</div>
		</div>
		</td>
        
		<td align=center style='font-family:Poppins;padding:12px;'>$formattedleavedate</td>
        <td align=center style='$color font-family:Poppins;padding:12px;'>$leavetype</td>
        <td align=center style='font-family:Poppins;padding:12px;'>$formattedCompodate</td>
        <td align=center style='font-family:Poppins;padding:12px;'>$reason</td>
        <td align=center style='font-family:Poppins;'>$formattedTime</td>
        <td style='font-family:Poppins;'>
			<img src='../leave/images/editicon.png' class='edit-icon' alt='Edit Icon'  
            onclick='openPopup(\"$empid\", \"$leavedate\", \"$compodate1\", \"$reason\", \"$leavetype\")'>
        </td>
        </tr>";
		
    }




?>

<script>

	function openPopup(empid, leavedate, compodate, reason, leavetype) 
	{

		document.getElementById('empid').value = empid;
		document.getElementById('leavedate').value = leavedate;
		document.getElementById('compodate').value = compodate;
		document.getElementById('reason').value = reason;
		document.getElementById('leavetype').value = leavetype;
		
		document.getElementById('popup').style.display = 'block';
		
	}


    function closePopup() 
	{
        document.getElementById('popup').style.display = 'none';
    }



function rejectRequest() 
{
    const empid = document.getElementById('empid').value;
    const leavedate = document.getElementById('leavedate').value;

    const requestData = 
	{
        empid: empid,
        leavedate: leavedate
    };

    fetch('reject.php', 
	{
        method: 'POST',
        headers: 
		{
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(requestData)
    })
    .then(response => response.json())
    .then(data => 
	{
        if (data.success) 
		{
            alert("Leave Request Rejected!"); 
            closePopup(); 
            window.location.reload(); 
        } 
		else 
		{
            alert("Error: " + data.message); 
        }
    })
    .catch(error => 
	{
        console.error("Error occurred:", error);
        alert("There was an error processing the request.");
    });
}

	
	
	
function approveRequest() 
{
	
    const empid = document.getElementById('empid').value;
    const leavedate = document.getElementById('leavedate').value;
    const compodate = document.getElementById('compodate').value;
    const reason = document.getElementById('reason').value;
    const leavetype = document.getElementById('leavetype').value;
	
    // Log data for debugging
    console.log("empid:", empid);
    console.log("leavedate:", leavedate);
    console.log("compodate:", compodate);
    console.log("reason:", reason);
    console.log("leavetype:", leavetype);	

    const requestData = 
	{
        empid: empid,
        compodate: compodate,
        leavedate: leavedate,
        reason: reason,
        leavetype: leavetype
    };

    fetch('approve.php', 
	{
        method: 'POST',
        headers: 
		{
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(requestData) 
    })
    .then(response => response.json())
    .then(data => 
	{
        if (data.success) 
		{
            alert("Leave Request Approved!"); 
            closePopup(); 
			window.location.reload();  // Refresh the page after approval
        } 
		else 
		{
            alert("Error: " + data.message); 
        }
    })
    .catch(error => 
	{
        console.error("Error occurred:", error);
        alert("There was an error processing the request."); 
    });
}

	
</script>


<br><br>

<center>
<?php	
$currentDate = new DateTime(); 
$currentDate = date('d-m-Y');
echo "<b style='font-size:25px;font-family:Poppins;font-weight:bold;margin-left:-50;'>Leave Request Queue</b><br>";
if(!$trdata)
{
?>
	<br>
	<div style='background-color:#FFFFFF;border-radius:10px;width:30%;height:20%;margin-left:-50;box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);'>

<?php	
	echo "<br><br><b style='font-size:20px;font-family:Poppins;font-weight:bold;'>No New Leave Requests</b>";	
}
?>
</center>

<br>

<?php	
if($trdata)
{
?>	
<div style="max-height: 500px; overflow-y: auto; margin-left: 60px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); width: 1300px; border-radius: 5px;">
    <table class="markleave" style="width:100%;">
        <tr style="position:sticky;top:0; z-index:1;">
            <td align=center style='background-color:#665763;color:white;font-family:Poppins;'>EMPLOYEE NAME</td>
            <td align=center style='background-color:#665763;color:white;font-family:Poppins;'>LEAVE DATE</td>
            <td align=center style='background-color:#665763;color:white;font-family:Poppins;'>LEAVE TYPE</td>
            <td align=center style='background-color:#665763;color:white;font-family:Poppins;'>COMPO DATE</td>
            <td align=center style='background-color:#665763;color:white;font-family:Poppins;'>REASON FOR LEAVE</td>
            <td align=center style='background-color:#665763;color:white;font-family:Poppins;'>SENT TIME</td>
            <td align=center style='background-color:#665763;color:white;font-family:Poppins;'>ACTION</td>
        </tr>

        <?php 
        }
        echo $trdata; 
        ?>
    </table>
</div>



<div id="popup" class="popup-background">
    <div class="popup-content">
        <!-- Close Button as X -->
        <span class="close-btn" onclick="closePopup()">×</span>
        <p>Are you sure you want to approve this request?</p>
        <div class="popup-buttons">
            <button class="approve-btn" style="font-family:Poppins;" onclick="approveRequest()">APPROVE</button>
            <button class="reject-btn" style="font-family:Poppins;" onclick="rejectRequest()">REJECT</button>
        </div>
		<!-- Hidden Inputs -->
        <input type="hidden" id="empid">
        <input type="hidden" id="leavedate">
        <input type="hidden" id="compodate">
        <input type="hidden" id="reason">
        <input type="hidden" id="leavetype">
    </div>
</div>


	
	
	
	
	

	
	
	

<script>
    function submitForm() 
	{
        const form = document.getElementById('markleave');
        form.submit();
    }
</script>
	
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
