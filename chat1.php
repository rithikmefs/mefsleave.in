<?php
session_start();
include "connect.php";

$receiverid = isset($_GET['empid']) ? $_GET['empid'] : '';
if ($receiverid  === 'group') 
{
	$receiverid  = '';
}
	
$senderid = isset($_GET['eid']) ? $_GET['eid'] : ''; 
$you=$senderid;
$n = isset($_GET['n']) ? $_GET['n'] : '';
$markAsRead = isset($_GET['markAsRead']) ? $_GET['markAsRead'] : '';


$receiverid1 = isset($_GET['receiverid']) ? $_GET['receiverid'] : '';
$senderid1 = isset($_GET['senderid']) ? $_GET['senderid'] : '';
$timestamp = isset($_GET['timestamp']) ? $_GET['timestamp'] : '';

$name='';
$name1='';
$dp='';
$gender='';

$emparr=[];


	$sql7 = "SELECT empid,name FROM emp WITH (NOLOCK)";
    $res7 = sqlsrv_query($conn, $sql7);
    while ($row7 = sqlsrv_fetch_array($res7, SQLSRV_FETCH_ASSOC)) 
	{
		$empid = trim($row7['empid']);
		$name = trim($row7['name']);
		$emparr[$empid]=$name;
	}



    $sql6 = "SELECT name,gender FROM emp WITH (NOLOCK) WHERE empid='$receiverid'";
    $res6 = sqlsrv_query($conn, $sql6);
    while ($row6 = sqlsrv_fetch_array($res6, SQLSRV_FETCH_ASSOC)) 
	{
        if ($row6['name'] == NULL) 
		{
            $row6['name'] = '';
        }
        $name = trim($row6['name']);
		
		if($row6['gender']==NULL)
		{
			$row6['gender']='';
		}
		$gender  =  trim($row6['gender']);	
	}

	
	$n1 = explode(' ',$name);
	$name2 = ucfirst(strtolower($n1[0]));

/* $rowCount=0;

$sql5 = "SELECT COUNT(*) as RowCount FROM msg";
$res5 = sqlsrv_query($conn, $sql5);

if ($res5 === false) 
{
    die(print_r(sqlsrv_errors(), true));
}

// Fetch the row count
while($row5 = sqlsrv_fetch_array($res5, SQLSRV_FETCH_ASSOC))
{	
$rowCount = $row5['RowCount'];

if ($rowCount >= 300) 
{
    // SQL query to delete the top 5 rows
    $deleteQuery = "
    WITH CTE AS (
        SELECT TOP 5 *
        FROM msg
        ORDER BY msg_id
    )
    DELETE FROM CTE";

    // Execute the delete query
    $deleteResult = sqlsrv_query($conn, $deleteQuery);

    if ($deleteResult === false) 
	{
        die(print_r(sqlsrv_errors(), true));
    } 
	else 
	{
        echo "Top 5 rows deleted successfully.";
    }
} 
else 
{
    echo "Row count is less than 300. No rows deleted.";
}

// Close the connection
sqlsrv_close($conn);
}	 */

$stmt1='';
$targetFile='';

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{

    $message = $_POST['message'];
    $receiverid = $_POST['receiverid'];
    $senderid = $_POST['senderid'];
    $name1 = $_POST['name'];
    $timestamp = $_POST['timestamp'];
    $status = $_POST['status'];
	
    date_default_timezone_set("Asia/Kolkata");
    $timestamp = date('Y-m-d H:i:s');	
	

    $tsql = "INSERT INTO msg (senderid, receiverid, message, timestamp, status) VALUES (?, ?, ?, ?, 'UNREAD')";
    $params = array($senderid, $receiverid, $message, $timestamp, $status);
    $stmt = sqlsrv_query($conn, $tsql, $params);

    if ($stmt) 
    {
        $_SESSION['receiverid'] = $receiverid;
		//$_SESSION['status'] = $status;
        sqlsrv_free_stmt($stmt);
        header("Location: main.php");
        exit;
    } 
    else 
    {
        die("Failed to send message: " . print_r(sqlsrv_errors(), true));
    }

}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


if ($markAsRead == 'UNREAD') //True
{
        $receiverid = $_GET['empid'];
		if($receiverid == 'group')
		{
			$receiverid = '';
		}	
        $senderid = $_GET['eid'];
        $update_sql = "UPDATE msg SET status = 'READ' WHERE receiverid = '$senderid' AND senderid = '$receiverid'";
        $update_stmt = sqlsrv_query($conn, $update_sql);
		$_SESSION['status'] = 'READ';

        if ($update_stmt === false) 
		{
            die(print_r(sqlsrv_errors(), true));  
        } 
		
}



$c="receiverid=''";
if($receiverid1!='')
{
	$c="(senderid = ? AND receiverid = ?) OR (senderid = ? AND receiverid = ?)";
} 	 



if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['fetch'])) 
{     

    $senderid = $_GET['senderid'];
    $receiverid = $_GET['receiverid'];
	
    $tsql = "SELECT * FROM msg WHERE $c ORDER BY timestamp ASC"; 
    $params = array($senderid1, $receiverid1, $receiverid1, $senderid1);
    $stmt = sqlsrv_query($conn, $tsql, $params);		
		
    if ($stmt === false) 
	{
        die(print_r(sqlsrv_errors(), true));
    }

    $messages = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) 
	{
        $messages[] = $row;
    }

    
    header('Content-Type: application/json'); // Ensure correct content type
    echo json_encode($messages);
    sqlsrv_free_stmt($stmt);
    exit;
	
}	




	$tsq2 = "SELECT timestamp FROM msg WHERE receiverid='' ORDER BY timestamp ASC";
	$res2 = sqlsrv_query($conn,$tsq2);
	while($row2 = sqlsrv_fetch_array( $res2, SQLSRV_FETCH_ASSOC))
	{
		if($row2['timestamp']==NULL)
		{
			$row2['timestamp']='';
		}
		$timestamp  =  trim($row2['timestamp']);
	}
	
	
////////////////////////////////////////////////

?>



<!DOCTYPE html>
<html>
<head>
<link rel="icon" href="../leave/images/MEFS.png">
    <title>Mefs - Leave Portal</title>
    <style>
	
        #chat-box 
		{
			
            width: 500px;
            height: 430px;
			margin-left:25px;
            border: 1px solid #ccc;
            overflow-y: scroll;
            padding: 10px;
            //background: #f9f9f9;
			background-image: url('../leave/images/msgbg.jpg');
        }
		
        .message 
		{
            margin: 10px 0;
            padding: 5px;
            border-radius: 5px;
        }
	
	
		.sender1-wrapper 
		{
			display: flex; 
			justify-content: flex-end; 
			margin-left: 225px; 
		}

	
		.sender1 
		{

			margin-right:auto;
			justify-content: flex-end; 
			margin-left: 225px;
			border-radius: 8px 0px 20px 20px;
			padding: 5px 15px;
			background: linear-gradient(135deg, #c6f2f7, #baf0f7 50%, #d4f6fa);
			max-width: 230px;
			display: flex;
			//display: inline-block;
			font-size: 15px;
			overflow-wrap: break-word; /* Ensures long words break */
			word-break: break-word; /* Ensures words break on overflow */
			white-space: normal; /* Allows text wrapping */
			text-align: left;
			//float: right;
			//clear: both;
			
		}

				
        .sender2 
		{
			font-size:12px;
			color:#F9F9F9;
			margin-left:35px;
			height:12px;
			width:225px;
            text-align: left;
        }      

        .sender5 
		{
			margin-left:0px;
			margin-top:-4px;
			height:0px;
			width:5px;
            text-align: left;
        } 

        .sender10 
		{
			color:#F9F9F9;
			font-size:10px;
			margin-left:40px;
			margin-top: -8px;
			height: 30px;
            text-align:left;
        }  

        .sender11
		{
			color:#F9F9F9;
			font-size:10px;
		    margin-left: 42px;
            margin-top: -20px;
			height:30px;
            text-align:left;
        }  	

        .sender12 
		{
			color:#F9F9F9;
			font-size:10px;
			margin-left:183px;
			width:292px;
			margin-top: -8px;
			height: 18px;
            text-align:right;
        }  		
		
        .sender13 
		{
			color:#F9F9F9;
			font-size:10px;
			margin-right: 9px;
			margin-top: -20px;
			height: 8px;
            text-align:right;
        }  			
	
		.sender4 
		{			
			font-size:15px;
			border-radius: 0px 20px 40px 20px;
			padding: 5px 10px 5px 10px;
            background-color: #ffccbc;
			min-content: 30px;
			max-width: 230px;
			margin-left:36px;
			margin-top:7px;
			overflow-wrap:break-word;
			white-space: pre-wrap;
			display: inline-block;
            text-align: left;
			margin-right: auto;							
        }
		
		.sender7
		{
			
			border-radius: 0px 20px 40px 20px;
			padding: 5px 10px 5px 10px;
			margin-left:25px;
			margin-top:0px;
			font-size:35px;
			width:225px;
			overflow-wrap:break-word;
            text-align: left;
        }

		.sender9
		{
			
			border-radius: 0px 20px 40px 20px;
			padding: 5px 10px 5px 10px;
			margin-left:-10px;
			font-size:35px;
			margin-top:-5px;
			width:225px;
			overflow-wrap:break-word;
            text-align: left;
        }		

		.sender8
		{
			
			border-radius:  20px 0px 20px 40px;
			padding: 5px 10px 5px 10px;
			margin-right:-6px;
			margin-top:-4px;
			font-size:35px;
			overflow-wrap:break-word;
            text-align:right;
        }	
		
		.sender6 
		{
			
			border-radius: 0px 20px 25px 20px;
			padding: 5px 10px 5px 10px;
            background-color: #ffccbc;
			min-content: 30px;
			max-width: 230px;
			margin-left:0px;
			font-size:15px;
			display: inline-block;
			overflow-wrap:break-word;
			white-space: pre-wrap; 
            text-align: left;
			margin-right: auto;		
        }		
		
        .sender14 
		{
			color:#F9F9F9;
			font-size:10px;
			margin-left:6px;
			margin-top: -8px;
			height: 18px;
            text-align:left;
        }  

        .sender15
		{
			color:#F9F9F9;
			font-size:10px;
		    margin-left: 8px;
            margin-top: -22px;
			height:20px;
            text-align:left;
        }  	

		.sender16
		{
			
			border-radius:  20px 0px 20px 40px;
			padding: 5px 10px 5px 10px;
			width:50px;
			height:50px;
			margin-right:-6px;
			font-size:35px;
			overflow-wrap:break-word;
            text-align:right;
        }

		.sender17
		{
			
			border-radius: 0px 20px 40px 20px;
			padding: 5px 10px 5px 10px;
			width:50px;
			height:50px;			
			margin-left:-10px;
			font-size:35px;
			width:225px;
			overflow-wrap:break-word;
            text-align: left;
        }		
		
        #message-input 
		{
            width: 350px;
			margin-left:10px;
			margin-top:-5px;
            padding: 6px;
			outline: 0;
			border:none;
        }
		
		#ebutton
		{		
			float:left;
			margin-top:0px;
		}
		
        #emojiButton
		{
            //border-radius: 10px;
			//margin-left:1px;
			width: 25px;
			height:25px;
			 
		}	
		
		#imageupload
		{		
			float:left;
			margin-top:0px;
		}		
		
		#sbutton
		{
			
			float:right;
			margin-top:-2px;
		}

        #sendbutton
		{
			margin-top:0px;
			width: 35px;
			height:30px;
			 
		}	
		
		#pauseButton
		{
			margin-left:26px;
		}		
		
	
		
		
		.report
		{
			caret-color:transparent;
		}	
		
		.emoji-picker 
		{
		  display: none;
		  position: absolute;
		  bottom: 60px;
		  left: 10px;
		  width: 250px;
		  max-height: 150px;
		  overflow-y: scroll;
		  background: #f9f9f9;
		  border: 1px solid #ddd;
		  padding: 10px;
		  border-radius: 5px;
		  z-index: 10;
		}

		.emoji 
		{
		  cursor: pointer;
		  font-size: 18px;
		  margin: 5px;
		  display: inline-block;
		}
		
		#sendmessage
		{
			
	      height:25px;
	      border:1px solid black;	
	      //position:absolute;
          //bottom:0;
	      //right:0px;
	      //width:290px;
	      background:#fff;
	      //padding-bottm:50px;
          width: 500px;
		  margin-left:26px;
		  caret-color:black;
		  padding: 10px;
	
        }
	
	
#sendmessage:focus-within 
{
    border: 2px solid black;
}


//////////////////////////////////////////////////////////////////////////////////

#togglebody 
{
  display: grid;
  place-content: center;
  height: 100vh;
  background-color: #fefefe;
    margin-top:20px;
}

label 
{
  pointer-events: none;

  .input 
  {
    display: none;

    &:checked + .toggle-wrapper 
	{
      box-shadow: 0 8px 14px 0 rgba(darken(#3957ee, 12%), 0.12);
    }

    &:checked + .toggle-wrapper > .selector 
	{
      left: calc(100% - 30px);
      background-color: red;
    }

    &:checked ~ .notification > .selected:before 
	{
      content: 'You are now Offline';
    }
  }

  .toggle-wrapper 
  {
    position: relative;
    width:70px;
    height: 30px;
    background-color: #d1cfcf;
    border-radius: 999px;
	margin-top:15px;
    margin-left:25px;
    cursor: pointer;
    pointer-events: all;
    box-shadow: 0 8px 14px 0 rgba(darken(#ee5f39, 12%), 0.12);

    .selector 
	{
      width: 20px;
      height: 20px;
      position: absolute;
      top: 50%;
      left: 10px;
      transform: translateY(-50%);
      background-color: green;
      transition: left 0.25s ease;
      border-radius: 50%;
    }
  }

  .toggle-wrapper:hover
  {
	  
  }
  .notification 
  {
    font-size: 15px;
    width: 100%;

    .selected:before 
	{
      content: 'You are now Online';
      font-size: 15px;
	  margin-top:0px;
	  margin-left:28px;
      border-bottom: 2px solid;
    }
  }
}



.notification1 
{
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: #333;
    color: #fff;
    padding: 10px;
    border-radius: 5px;
    z-index: 1000;
    opacity: 0.9;
}

/////////////////////////////////////////////////////////////////////////////////



			
    </style>
	<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
	<script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@1.0.1/emoji-picker.min.js"></script>

</head>
<body class="report" bottommargin="0" topmargin="0" leftmargin="0" rightmargin="0" style="background:linear-gradient(135deg, #f7d9ad, #f7e7d0 50%, #f7d9ad);">
<!-- style="background:linear-gradient(135deg, #28373E, #2e3c42 50%, #445259);" -->
<!-- style="background:linear-gradient(135deg, #574052, #665162 50%, #806c7c);" -->
<?php
include "header3.html";
?>

<br><br>
<?php



if($gender=='MALE')
{
		
	if($receiverid=='1044')
	{
		?>
		<img src='../leave/dp/jinoy.png' style="width:60px;height:60px;border-radius:50px;margin-left:220px;"><b><?php echo $name2; ?></b></img>
		<?php
	}
	elseif($receiverid=='1068')
	{
		?>
		<img src='../leave/dp/anandhu.png' style="width:60px;height:60px;border-radius:50px;margin-left:220px;"><b><?php echo $name2; ?></b></img>
		<?php		
	}
	elseif($receiverid=='1001')
	{
		?>
		<img src='../leave/dp/anurag.png' style="width:60px;height:60px;border-radius:50px;margin-left:220px;"><b><?php echo $name2; ?></b></img>
		<?php	
	}
	elseif($receiverid=='1071')
	{
		?>
		<img src='../leave/dp/vyshnav.png' style="width:60px;height:60px;border-radius:50px;margin-left:220px;"><b><?php echo $name2; ?></b></img>
		<?php		
	}
 	elseif($receiverid=='1003')
	{
		?>
		<img src='../leave/dp/rithik.png' style="width:60px;height:60px;border-radius:50px;margin-left:220px;"><b><?php echo $name2; ?></b></img>
		<?php	
	} 
 	elseif($receiverid=='1004')
	{
		?>
		<img src='../leave/dp/sarath.png' style="width:60px;height:60px;border-radius:50px;margin-left:220px;"><b><?php echo $name2; ?></b></img>
		<?php	
	} 	
 	elseif($receiverid=='1000')
	{
		?>
		<img src='../leave/dp/sanoop.png' style="width:60px;height:60px;border-radius:50px;margin-left:220px;"><b><?php echo $name2; ?></b></img>
		<?php	
	}
 	elseif($receiverid=='1091')
	{
		?>
		<img src='../leave/dp/akhil.png' style="width:60px;height:60px;border-radius:50px;margin-left:220px;"><b><?php echo $name2; ?></b></img>
		<?php	
	}	
 	elseif($receiverid=='999')//ARUN
	{
		?>
		<img src='../leave/dp/mefs.png' style="width:60px;height:60px;border-radius:50px;margin-left:220px;"><b><?php echo 'MEFS'; ?></b></img>
		<?php	
	}
 	elseif($receiverid=='1108')//RAJAGOPAL
	{
		?>
		<img src='../leave/dp/mefs.png' style="width:60px;height:60px;border-radius:50px;margin-left:220px;"><b><?php echo 'MEFS'; ?></b></img>
		<?php	
	}	
	else
	{
		?>
		<img src='../leave/dp/male1.png' style="width:60px;height:60px;margin-left:220px;"><b><?php echo $name2; ?></b></img>
		<?php
	}

}

elseif($receiverid=='1002')
{
	?>
	<img src='../leave/dp/anna.png' style="width:60px;height:60px;border-radius:50px;margin-left:220px;"><b><?php echo $name2; ?></b></img>
	<?php
}
elseif($receiverid=='1070')
{
	?>
	<img src='../leave/dp/manju.png' style="width:60px;height:60px;border-radius:50px;margin-left:220px;"><b><?php echo $name2; ?></b></img>
	<?php
}

elseif($gender=='FEMALE')
{
	?>
	<img src='../leave/dp/female1.png' style="width:60px;height:60px;margin-left:220px;"><b><?php echo $name2; ?></b></img>
	<?php
}

else
{
	?>
	<img src='../leave/dp/group.png' style="width:60px;height:60px;margin-left:220px;"><b><?php echo 'Group Chat'; ?></b></img>
	<?php
}	



$dp = [
    1000 => "../leave/dp/sanoop.png",
    1001 => "../leave/dp/anurag.png",
    1003 => "../leave/dp/rithik.png",
    1004 => "../leave/dp/sarath.png",
    1044 => "../leave/dp/jinoy.png",
    1068 => "../leave/dp/anandhu.png",
    1071 => "../leave/dp/vyshnav.png",
    1002 => "../leave/dp/anna.png",
    1070 => "../leave/dp/manju.png",
    1091 => "../leave/dp/akhil.png",
    999 => "../leave/dp/mefs.png",
    1108 => "../leave/dp/mefs.png"
];


?>

<script>
const dp = <?php echo json_encode($dp); ?>;
</script>
<script src="https://unpkg.com/browser-image-compression@1.0.15/dist/browser-image-compression.js"></script>



<br>

<style>
.image-message 
{
    margin: 5px 0;
    border-radius: 5px;
}
#message-input 
{
    height: 20px; /* Adjust height as needed */
    overflow: hidden; /* Prevent scrollbars */
} 
</style>


<div id="chat-box">
</div>
<div id="sendmessage">
  <div id="ebutton">
	<img id="emojiButton" src="../leave/images/star.png">&nbsp;&nbsp;
  </div>
  
  <div id="emojiPicker" class="emoji-picker">
  </div>
  
  <!--<div id="imageupload">
	  <input type="file" id="image-upload" accept="image/*" style="display:none;" name="image" onchange="handleImageUpload(event)">
	  <img id="uploadIcon" style="width:25px;height:25px;" src="../leave/images/uploadicon.png" onclick="document.getElementById('image-upload').click();" alt="Upload Image" style="cursor: pointer;">
  </div>-->

  <textarea id="message-input" rows="3" name="message" placeholder="Type a message" autocomplete="off" style="resize:none;"></textarea>

  <div id="sbutton">
	<img onclick="sendMessage()" id="sendbutton" src="../leave/images/sendicon.png">
  </div>
  
  
  
<!--  <form id="chat-form" method="POST" action="chat1.php" enctype="multipart/form-data">
    <input type="text" id="message-input" name="message" placeholder="Type a message" autocomplete="off">
    <div id="imageupload">   
    <input type="file" id="image-upload" accept="image/*" style="display: none;" name="image" onchange="handleImageUpload(event)">
    <img id="uploadIcon" style="width:25px;height:25px;" src="../leave/images/uploadicon.png" onclick="document.getElementById('image-upload').click();" alt="Upload Image" style="cursor: pointer;">
    </div>	
    <div id="sbutton">
    <img onclick="sendMessage()" id="sendbutton" src="../leave/images/sendicon.png">
    </div>
</form> -->
 
  
</div>


<div id="togglebody">
<label for="toggle">
  <input type="checkbox" id="toggle" class="input" >
  <div class="toggle-wrapper" title="<?php echo 'Indicates whether you are online or offline. Turn on the Offline mode if you are trying to copy text. '; ?>">
    <span class="selector"></span>
  </div>
</label>
</div>


<?php
//echo $timestamp;
?>


<script>
const senderid = "<?php echo $senderid; ?>";
const receiverid = "<?php echo $receiverid; ?>";
const name = "<?php echo $name1; ?>";
const timestamp = "<?php echo $timestamp; ?>";
//const imagefile = "<?php echo $targetFile; ?>";

//////////////////////////////////////////////////////////////////////////////////  EMOJI  ///////////////////////////

const emojiButton = document.getElementById('emojiButton');
const emojiPicker = document.getElementById('emojiPicker');
const messageinput = document.getElementById('message-input');
const emojis = [];

// Add emojis from multiple ranges

const ranges = 
[
    // Priority emojis
    [0x1F44D, 0x1F44D], // 👍 (U+1F44D)
    [0x1F44E, 0x1F44E], // 👎 (U+1F44E)
	[0x1F44F, 0x1F44F], // 👏 (U+1F44F)
    [0x1F91E, 0x1F91E], // 👏 (U+1F91E)
    [0x1F447, 0x1F447], // 👆 (U+1F447)
    [0x1F448, 0x1F448], // 👈 (U+1F448)
    [0x1F449, 0x1F449], // 👉 (U+1F449)
    [0x1F44C, 0x1F44C], // 👌 (U+1F44C)
    [0x1F91D, 0x1F91D], // 👊 (U+1F91D)
    [0x1F91F, 0x1F91F], // 👐 (U+1F91F)
	
    [0x1F600, 0x1F64F], // Emoticons

    [0x1F300, 0x1F5FF], // Miscellaneous Symbols and Pictographs
    [0x1F680, 0x1F6FF], // Transport and Map Symbols
    [0x1F700, 0x1F77F], // Alchemical Symbols
    [0x1F780, 0x1F7FF], // Geometric Shapes Extended
    [0x1F800, 0x1F8FF], // Supplemental Arrows-C
    [0x1F900, 0x1F9FF], // Supplemental Symbols and Pictographs
    [0x1FA00, 0x1FAFF], // Chess Symbols and other miscellaneous symbols
    [0x2600, 0x26FF],   // Miscellaneous Symbols
    [0x2700, 0x27BF]    // Dingbats
];


// Loop through each range and push emojis to the array
for (const [start, end] of ranges) 
{
    for (let i = start; i <= end; i++) 
	{
        emojis.push(String.fromCodePoint(i));
    }
}


// Example of adding emojis to your picker
function populateEmojiPicker() 
{
  emojis.forEach(emoji => 
  {
    const emojiElement = document.createElement('span');
    emojiElement.textContent = emoji;
    emojiElement.classList.add('emoji');
    
    emojiElement.addEventListener('click', () => 
    {
      messageinput.value += emoji; // Append emoji to the input field
      messageinput.setAttribute('data-is-emoji', 'true'); // Mark message as emoji
      
      emojiPicker.style.display = 'none'; // Hide picker after selecting an emoji
      messageinput.focus();
    });
    
    emojiPicker.appendChild(emojiElement);
  });
}

emojiButton.addEventListener('click', (event) => 
{
  event.stopPropagation(); // Prevent click from closing the picker
  emojiPicker.style.display = emojiPicker.style.display === 'none' || !emojiPicker.style.display ? 'block' : 'none';
});


document.addEventListener('click', (event) => 
{
  if (!emojiPicker.contains(event.target) && event.target !== emojiButton) 
  {
    emojiPicker.style.display = 'none';
  }
});


////////////////////////////////////////////////////////////////////////////////// EMOJI  ///////////////////////////


 // Variable to store the selected image

let selectedImage = ''; // Move this outside to maintain the selected image globally

function handleImageUpload(event) 
{
    const file = event.target.files[0]; // Get the selected file
    if (file) 
	{
        const reader = new FileReader();
        reader.onload = function(e) 
		{
            selectedImage = e.target.result; // Store base64 image string globally
            const imgPreview = document.createElement('img');
            imgPreview.src = selectedImage;
            imgPreview.style.maxWidth = '40px';
            imgPreview.style.maxHeight = '40px';
            document.getElementById('imageupload').appendChild(imgPreview); // Preview the image
        };
        reader.readAsDataURL(file); // Read the image as base64
    }
}




/* function sendMessage() 
{
    var messageInput = document.getElementById('message-input');
    var message = messageInput.value.trim();
    var imageUpload = document.getElementById('image-upload');
    var selectedImage = imageUpload.files[0]; // Get the selected file, if any
    
    if (message === '' && !selectedImage) 
	{
        alert('Please type a message or select an image to send.');
        return;
    }

    // Create FormData object for handling image files and text message
    var formData = new FormData(document.getElementById('chat-form'));

    // If an image is selected, append it to formData (it will be appended automatically if the input is in the form)
    if (selectedImage) {
        formData.append('img', selectedImage); // optional since image upload is handled by form
    }

    formData.append('senderid', senderid); // Assuming these values are defined elsewhere
    formData.append('receiverid', receiverid);

    // Sending the request using multipart/form-data for both image and message
    axios.post('chat1.php', formData, 
	{
        headers: { 'Content-Type': 'multipart/form-data' }
    })
    .then(response => 
	{
        messageInput.value = '';  // Clear message input after sending
        imageUpload.value = '';   // Clear file input after sending
        fetchMessages();          // Fetch messages after sending
    })
    .catch(error => {
        console.error('Error sending message or image:', error);
    });
} */



function sendMessage() 
{
    var messageInput = document.getElementById('message-input');
    var message = messageInput.value.trim();
    //var imageUpload = document.getElementById('image-upload');
    //var selectedImage = imageUpload.files[0]; // Get the selected file, if any
    
    if (message === '') 
	{
        alert('Please type a message.');
        return;
    }

    // Create FormData object for handling image files
    var formData = new FormData();

    // If an image is selected, append it to formData
    if (selectedImage) 
	{
        formData.append('img', selectedImage);
    }

    // If there's a message, append it to formData
    if (message) 
	{
        formData.append('message', message);
    }

    formData.append('senderid', senderid);
    formData.append('receiverid', receiverid);

    // If the selected file exists, use multipart/form-data; otherwise, use URL-encoded data for text messages
    if (selectedImage) 
	{
        axios.post('chat1.php', formData, 
		{
            headers: { 'Content-Type': 'multipart/form-data' }
        })
        .then(response => 
		{
            messageInput.value = '';
            imageUpload.value = ''; // Clear the file input after sending
            fetchMessages(); // Fetch messages after sending
        })
        .catch(error => 
		{
            console.error('Error sending image:', error);
        });
    } 
	else 
	{
        axios.post('chat1.php', new URLSearchParams(
		{
            'message': message,
            'senderid': senderid,
            'receiverid': receiverid
        }).toString(), 
		{
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
        .then(response => 
		{
            messageInput.value = '';
            fetchMessages(); // Fetch messages after sending
        })
        .catch(error => 
		{
            console.error('Error sending message:', error);
        });
    }
} 



    let isPaused = false;
    
	document.getElementById('toggle').addEventListener('change', function () 
	{			
		isPaused = this.checked; 
	});



<?php

if($receiverid) //Induvidual
{
?>

/////////////////////////////////////////////////////////////////////////////////////////////////////////////

function fetchMessages() //Induvidual
{
    if (!isPaused) 
    {
        axios.get('chat1.php', 
        {
            params: 
            {
                fetch: 1,
                senderid: senderid,
                receiverid: receiverid,
				timestamp: timestamp
            }
        })
        .then(response => 
        {
            const data = response.data;
            console.log(data);

            const chatBox = document.getElementById('chat-box');
            chatBox.innerHTML = ''; // Clear previous messages
			let previousDate = null;

            data.forEach(msg => 
            {
				const msgDate = new Date(msg.timestamp.split(' ')[0]); // Get the date part of the timestamp
				const currentDate = new Date();
				const isToday = msgDate.toDateString() === currentDate.toDateString();
				const yesterday = new Date(currentDate);
				yesterday.setDate(currentDate.getDate() - 1); // Set yesterday's date
				const isYesterday = msgDate.toDateString() === yesterday.toDateString();

				// Insert "Today", "Yesterday", or a date divider if the day has changed
				if (previousDate === null || msgDate.toDateString() !== previousDate.toDateString()) 
				{
					const dayDiv = document.createElement('div');
					dayDiv.classList.add('day-divider');
					dayDiv.textContent = isToday ? 'Today' : msgDate.toDateString(); // "Today" for current day

					if (isToday)
					{
						dayDiv.style.marginLeft = '50px'; // Adjust the margin as needed
					}
					else if (isYesterday) 
					{
						dayDiv.textContent = 'Yesterday';
					} 
					else 
					{
						dayDiv.textContent = msgDate.toDateString();
					}	
						
					dayDiv.style.fontWeight = 'bold';
					//dayDiv.style.backgroundColor = '#2B5F5D'; // Green background
					dayDiv.style.padding = '5px 10px'; // Add padding for better spacing
					dayDiv.style.borderRadius = '5px'; // Optional: rounded corners
					dayDiv.style.fontSize = '13px'; // Set font size
					dayDiv.style.textAlign = 'center'; // Center-align the text
					dayDiv.style.color = 'white'; // Center-align the text

					if (isToday) 
					{
						dayDiv.style.width = '350px'; // Width for "Today"
					} 
					else if (isYesterday) 
					{
						dayDiv.style.marginLeft = '30px'; // Margin for "Yesterday"
						dayDiv.style.width = '400px'; // Width for "Yesterday"
						dayDiv.style.textAlign = 'center';
						dayDiv.style.display = 'inline-block';						
					} 
					else 
					{
						dayDiv.style.marginLeft = '25px'; // Margin for dates
						dayDiv.style.width = '420px'; // Width for a specific date
						dayDiv.style.textAlign = 'center';
						dayDiv.style.display = 'inline-block';
					}

					chatBox.appendChild(dayDiv); // Append the divider to the chat box
                }              
				
				//const imageDiv = document.createElement('div');
				const msgDiv = document.createElement('div');
				
                const timestampfix = msg.timestamp.split(' ');
				const timestampfix1 = timestampfix[1];								
				const timestampfix2 = timestampfix1.split('.');
				const timestampfix3 = timestampfix2[0];
				const timestampfix4 = timestampfix3.split(':');
				let ampm;
				if (timestampfix4[0] >= 12) 
				{
					ampm = 'pm';
					timestampfix4[0] = timestampfix4[0] > 12 ? timestampfix4[0] - 12 : 12; // Convert to 12-hour format, handle 12 PM
				} 
				else 
				{
					ampm = 'am';
					timestampfix4[0] = timestampfix4[0] == 0 ? 12 : timestampfix4[0]; // Handle midnight as 12 AM
				}
				const timestampfix5 = `${timestampfix4[0]}:${timestampfix4[1]}${ampm}`;

                const timestampDiv = document.createElement('div');
                timestampDiv.classList.add('timestamp');
				
				
				//const imgElement = document.createElement('div');

                // Check if the message is emoji-only using Unicode range
				const isEmoji = msg.message && /^[\u{1F44E}\u{1F91E}\u{1F44D}\u{1F447}\u{1F448}\u{1F44C}\u{1F44F}  \u{1F449}\u{1F44A}\u{1F91D}\u{1F91F}\u{1F3C6}]+|^[\u{1F600}-\u{1F64F}\u{1F300}-\u{1F5FF}\u{1F680}-\u{1F6FF}\u{1F700}-\u{1F77F}\u{1F780}-\u{1F7FF}\u{1F800}-\u{1F8FF}\u{1F900}-\u{1F9FF}\u{1FA00}-\u{1FAFF}\u{2600}-\u{26FF}\u{2700}-\u{27BF}]+$/u.test(msg.message);

                if (msg.selectedImage) 
				{
					const imgElement = document.createElement('img');
					imgElement.src = msg.selectedImage; // Assuming image URL is provided
					imgElement.innerHTML = `${selectedImage}`;
					imgElement.alt = `${selectedImage}`;
                    imgElement.style.maxWidth = '200px'; // Set image size
                    imgElement.style.maxHeight = '200px';
                    imgElement.style.borderRadius = '5px';
                    msgDiv.appendChild(imgElement); // Add the image to the message div
                } 
				else 
				{
					msgDiv.innerHTML = msg.message.replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/\n/g, '<br>');
                } 

				if (msg.senderid === senderid) 
				{
					msgDiv.classList.add('message', isEmoji ? 'sender8' : 'sender1');
					    
						if (!isEmoji) 
						{
							msgDiv.id = 'sender1-wrapper'; 
						}
						
					timestampDiv.classList.add('timestamp', isEmoji ? 'sender13' : 'sender12');
					
				} 
				else 
				{
					msgDiv.classList.add('message', isEmoji ? 'sender9' : 'sender6');
					timestampDiv.classList.add('timestamp', isEmoji ? 'sender15' : 'sender14');
				}

				timestampDiv.innerHTML = `${timestampfix5}`;

				chatBox.appendChild(msgDiv);
				chatBox.appendChild(timestampDiv);
				previousDate = msgDate;
            });

            chatBox.scrollTop = chatBox.scrollHeight;
        })
        .catch(error => 
        {
            console.error('Error fetching messages:', error);
        });
    }
} 

	


/////////////////////////////////////////////////////////////


/* 
function fetchMessages() 
{
    if (!isPaused) 
    {
        axios.get('chat1.php', 
        {
            params: 
            {
                fetch: 1,
                senderid: senderid,
                receiverid: receiverid,
                timestamp: timestamp
            }
        })
        .then(response => 
        {
            const data = response.data;
            console.log(data);

            const chatBox = document.getElementById('chat-box');
            chatBox.innerHTML = ''; // Clear previous messages
            let previousDate = null;

            data.forEach(msg => 
            {
                const msgDate = new Date(msg.timestamp.split(' ')[0]); // Get the date part of the timestamp
                const currentDate = new Date();
                const isToday = msgDate.toDateString() === currentDate.toDateString();
                const yesterday = new Date(currentDate);
                yesterday.setDate(currentDate.getDate() - 1); // Set yesterday's date
                const isYesterday = msgDate.toDateString() === yesterday.toDateString();

                // Insert "Today", "Yesterday", or a date divider if the day has changed
                if (previousDate === null || msgDate.toDateString() !== previousDate.toDateString()) 
                {
                    const dayDiv = document.createElement('div');
                    dayDiv.classList.add('day-divider');
                    dayDiv.textContent = isToday ? 'Today' : (isYesterday ? 'Yesterday' : msgDate.toDateString());

                    // Apply styles for the day divider
                    dayDiv.style.fontWeight = 'bold';
                    dayDiv.style.backgroundColor = '#2B5F5D'; // Green background
                    dayDiv.style.padding = '5px 10px'; // Add padding for better spacing
                    dayDiv.style.borderRadius = '5px'; // Optional: rounded corners
                    dayDiv.style.fontSize = '13px'; // Set font size
                    dayDiv.style.textAlign = 'center'; // Center-align the text
                    dayDiv.style.color = 'white';

                    if (isToday) 
                    {
                        dayDiv.style.marginLeft = '200px';
                        dayDiv.style.width = '50px'; // Width for "Today"
                    } 
                    else if (isYesterday) 
                    {
                        dayDiv.style.marginLeft = '185px';
                        dayDiv.style.width = '80px'; // Width for "Yesterday"
                    } 
                    else 
                    {
                        dayDiv.style.marginLeft = '165px';
                        dayDiv.style.width = '140px'; // Width for a specific date
                    }

                    chatBox.appendChild(dayDiv); // Append the divider to the chat box
                }              
                
                // Create a message div
                const msgDiv = document.createElement('div');
                
                // Format the timestamp
                const timestampfix = msg.timestamp.split(' ');
                const timePart = timestampfix[1].split('.')[0];
                const [hours, minutes] = timePart.split(':');
                const ampm = hours >= 12 ? 'pm' : 'am';
                const formattedHours = hours % 12 || 12; // Convert to 12-hour format
                const formattedTime = `${formattedHours}:${minutes}${ampm}`;

                const timestampDiv = document.createElement('div');
                timestampDiv.classList.add('timestamp');
				
				const isEmoji = msg.message && /^[\u{1F44E}\u{1F91E}\u{1F44D}\u{1F447}\u{1F448}\u{1F44C}\u{1F44F}  \u{1F449}\u{1F44A}\u{1F91D}\u{1F91F}\u{1F3C6}]+|^[\u{1F600}-\u{1F64F}\u{1F300}-\u{1F5FF}\u{1F680}-\u{1F6FF}\u{1F700}-\u{1F77F}\u{1F780}-\u{1F7FF}\u{1F800}-\u{1F8FF}\u{1F900}-\u{1F9FF}\u{1FA00}-\u{1FAFF}\u{2600}-\u{26FF}\u{2700}-\u{27BF}]+$/u.test(msg.message);				

                // Check if the message contains an image
                if (msg.selectedImage) 
                {
                    const imgElement = document.createElement('img');
                    imgElement.src = msg.selectedImage; // Assuming image URL is provided
                    imgElement.alt = 'Image';
                    imgElement.style.maxWidth = '200px'; // Set image size
                    imgElement.style.maxHeight = '200px';
                    imgElement.style.borderRadius = '5px';
                    msgDiv.appendChild(imgElement); // Add the image to the message div
                } 
                else 
                {
                    msgDiv.textContent = msg.message; // Handle regular text messages
                }
				
                if (msg.senderid === senderid) 
                {
                    msgDiv.classList.add('message', isEmoji ? 'sender8' : 'sender1');
                    //imgElement.classList.add('image-upload', isImage ? 'sender16' : 'sender1');
                    timestampDiv.classList.add('timestamp', isEmoji ? 'sender13' : 'sender12');
                } 
                else 
                {
                    msgDiv.classList.add('message', isEmoji ? 'sender9' : 'sender6');
                    //imgElement.classList.add('image-upload', isImage ? 'sender17' : 'sender6');
					timestampDiv.classList.add('timestamp', isEmoji ? 'sender15' : 'sender14');
                }				

                timestampDiv.textContent = formattedTime;
                
                // Append the message and timestamp divs to the chat box
                chatBox.appendChild(msgDiv);
                chatBox.appendChild(timestampDiv);

                // Update previous date to track date changes
                previousDate = msgDate;
            });

            // Scroll to the bottom of the chat box
            chatBox.scrollTop = chatBox.scrollHeight;
        })
        .catch(error => 
        {
            console.error('Error fetching messages:', error);
        });
    }
} */


<?php

}
else	
{
	
?>	
		

function fetchMessages() //Group
{
    if (!isPaused) 
    {
        const userNames = <?php echo json_encode($emparr); ?>;
        const you = <?php echo $you; ?>;
        const dp = <?php echo json_encode($dp); ?>; 

        axios.get('chat1.php', 
        {
            params: 
            {
                fetch: 1,
                senderid: senderid,
                receiverid: receiverid,
                name: name,
                timestamp: timestamp
            }
        })
        .then(response => 
        {
            const data = response.data;
            console.log(data);

            const chatBox = document.getElementById('chat-box');
            chatBox.innerHTML = ''; // Clear previous messages
			let previousDate = null;

		data.forEach(msg => 
		{
					const msgDate = new Date(msg.timestamp.split(' ')[0]); // Get the date part of the timestamp
					const currentDate = new Date();
					const isToday = msgDate.toDateString() === currentDate.toDateString();
					const yesterday = new Date(currentDate);
					yesterday.setDate(currentDate.getDate() - 1); // Set yesterday's date
					const isYesterday = msgDate.toDateString() === yesterday.toDateString();

					// Insert "Today", "Yesterday", or a date divider if the day has changed
					if (previousDate === null || msgDate.toDateString() !== previousDate.toDateString()) 
					{
                    const dayDiv = document.createElement('div');
                    dayDiv.classList.add('day-divider');
                    dayDiv.textContent = isToday ? 'Today' : msgDate.toDateString(); 

					if (isToday) 
					{
						dayDiv.style.marginLeft = '200px'; 
						dayDiv.style.overflowWrap = 'break-word'; 
						dayDiv.style.whiteSpace = 'pre-wrap'; 
					}

					else if (isYesterday) 
					{
						dayDiv.textContent = 'Yesterday';
						dayDiv.style.overflowWrap = 'break-word'; 
						dayDiv.style.whiteSpace = 'pre-wrap'; 					
					} 
					else 
					{
						dayDiv.textContent = msgDate.toDateString();
						dayDiv.style.overflowWrap = 'break-word'; 
						dayDiv.style.whiteSpace = 'pre-wrap'; 						
					}	
					
					dayDiv.style.fontWeight = 'bold';
					//dayDiv.style.backgroundColor = '#2B5F5D'; // Green background
					dayDiv.style.padding = '5px 10px'; // Add padding for better spacing
					dayDiv.style.borderRadius = '5px'; // Optional: rounded corners
					dayDiv.style.fontSize = '13px'; // Set font size
					dayDiv.style.textAlign = 'center'; // Center-align the text
					dayDiv.style.color = 'white'; // Center-align the text
					
					if (isToday) 
					{
						dayDiv.style.width = '50px'; // Width for "Today"
					} 
					else if (isYesterday) 
					{
						dayDiv.style.marginLeft = '185px'; // Margin for "Yesterday"
						dayDiv.style.width = '80px'; // Width for "Yesterday"
						dayDiv.style.textAlign = 'center';
						dayDiv.style.display = 'inline-block';						
					} 
					else 
					{
						dayDiv.style.marginLeft = '165px'; // Margin for dates
						dayDiv.style.width = '140px'; // Width for a specific date
						dayDiv.style.textAlign = 'center';
						dayDiv.style.display = 'inline-block';
					}

                    chatBox.appendChild(dayDiv); // Append the divider to the chat box
                }

			let name = userNames[msg.senderid] || 'Unknown';
			const nameParts = name.split(' ');
			const firstName = nameParts[0];

			const firstNameDiv = document.createElement('div');
			firstNameDiv.classList.add('first-name');                

			const dpDiv = document.createElement('div');
			dpDiv.classList.add('dp');

			const msgDiv = document.createElement('div');
			msgDiv.classList.add('message');

			// Correct the timestamp processing
			const timestampfix = msg.timestamp.split(' ');
			const timestampfix1 = timestampfix[1];                                
			const timestampfix2 = timestampfix1.split('.');
			const timestampfix3 = timestampfix2[0];
			const timestampfix4 = timestampfix3.split(':');
			let ampm;
			if (timestampfix4[0] >= 12) 
			{
				ampm = 'pm';
				timestampfix4[0] = timestampfix4[0] > 12 ? timestampfix4[0] - 12 : 12;
			} 
			else 
			{
				ampm = 'am';
				timestampfix4[0] = timestampfix4[0] == 0 ? 12 : timestampfix4[0]; 
			}
			const timestampfix5 = `${timestampfix4[0]}:${timestampfix4[1]}${ampm}`;

			const timestampDiv = document.createElement('div');
			timestampDiv.classList.add('timestamp');

			// Check if the message is emoji-only using the marker
			const isEmoji = msg.message && /^[\u{1F600}-\u{1F64F}\u{1F300}-\u{1F5FF}\u{1F680}-\u{1F6FF}\u{1F700}-\u{1F77F}\u{1F780}-\u{1F7FF}\u{1F800}-\u{1F8FF}\u{1F900}-\u{1F9FF}\u{1FA00}-\u{1FAFF}\u{2600}-\u{26FF}\u{2700}-\u{27BF}]+$/u.test(msg.message);

                if (msg.selectedImage) 
				{
                    const imgElement = document.createElement('img');
                    imgElement.src = msg.selectedImage; // Assuming image URL is provided
					imgElement.alt = `${selectedFile}`;
                    imgElement.style.maxWidth = '200px'; // Set image size
                    imgElement.style.maxHeight = '200px';
                    imgElement.style.borderRadius = '5px';
                    msgDiv.appendChild(imgElement); // Add the image to the message div
                } 
				else 
				{
                    // Add text message
                    msgDiv.textContent = msg.message;
                }

			if (msg.senderid === senderid) 
			{
				if (isEmoji) 
				{
					msgDiv.classList.add('sender8');
					timestampDiv.classList.add('sender13');
				} 
				else 
				{
					msgDiv.classList.add('sender1');
					timestampDiv.classList.add('sender12');
				}
			} 
			else 
			{
				firstNameDiv.classList.add('sender2');
				dpDiv.classList.add('sender5');

				if (isEmoji) 
				{
					msgDiv.classList.add('sender7');
					timestampDiv.classList.add('sender11');
				} 
				else 
				{
					msgDiv.classList.add('sender4');
					timestampDiv.classList.add('sender10');
				}
			}

			if (you != msg.senderid) 
			{
				firstNameDiv.innerHTML = `${firstName}`;
				timestampDiv.innerHTML = `${timestampfix5}`;

				const imgElement1 = document.createElement('img');
				imgElement1.src = dp[msg.senderid] || ''; // Set profile picture
				imgElement1.alt = `${firstName}`;
				imgElement1.alt = `${timestampfix5}`;
				imgElement1.style.width = '30px';
				imgElement1.style.height = '30px';
				imgElement1.style.borderRadius = '50%';
				dpDiv.appendChild(imgElement1);
			} 
			else 
			{
				timestampDiv.innerHTML = `${timestampfix5}`;
			}    

			msgDiv.innerHTML = `${msg.message}`;

			// Append the divs to the chat box
			chatBox.appendChild(dpDiv);
			chatBox.appendChild(firstNameDiv);
			chatBox.appendChild(msgDiv);
			chatBox.appendChild(timestampDiv);
			
			previousDate = msgDate;
		});

		chatBox.scrollTop = chatBox.scrollHeight; // Scroll to the bottom of the chat
        })
        .catch(error => 
        {
            console.error('Error fetching messages:', error);
        });
    }
}







<?php
}
	

?>

            var messageInput = document.getElementById('message-input');
            messageInput.addEventListener('keypress', function(event) 
			{
                if (event.key === 'Enter') 
				{
                  
				  event.preventDefault(); // Prevents newline in the input field
                  sendMessage(); // Call the sendMessage function
				
                }
				
            });
   


setInterval(fetchMessages, 1000);
fetchMessages();
populateEmojiPicker();

</script>

<style>
.notification-popup 
{
    position: fixed;
    top: 100px;
    right: 10px;
    padding: 15px;
    background-color: #f9edbe;
    border: 1px solid #f08a24;
    z-index: 1000;
    border-radius: 5px;
    font-size: 16px;
}

</style>


<br><br>

</body>
</html>


<script>
/* function checkForNewMessages() 
{
    // Make an AJAX request to the PHP script
    fetch('notification.php')
        .then(response => response.json())
        .then(data => 
		{
            if (data.new_message) 
			{
                // Show a popup notification if there's a new message
                showPopupNotification(data.message_count);
            }
        })
        .catch(error => console.error('Error checking for new messages:', error));
}

// Function to show the notification popup
function showPopupNotification(messageCount) 
{
    // Create a popup div element
    let notification = document.createElement('div');
    notification.className = 'notification-popup';
    notification.innerText = `You have ${messageCount} new message(s)`;
    
    // Style the popup
    notification.style.position = 'fixed';
    notification.style.bottom = '100px';
    notification.style.right = '100px';
    notification.style.backgroundColor = '#f9edbe';
    notification.style.padding = '15px';
    notification.style.border = '1px solid #f08a24';
    notification.style.borderRadius = '5px';
    notification.style.zIndex = '1000';

    // Append the popup to the body
    document.body.appendChild(notification);

    // Remove the notification after 15 seconds
    setTimeout(() => notification.remove(), 15000);
}

setInterval(checkForNewMessages, 1000); // Check every 1 seconds */

</script> 