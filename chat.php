<?php
session_start();
include "connect.php";

$receiverid = isset($_GET['empid']) ? $_GET['empid'] : '';
$senderid = isset($_GET['eid']) ? $_GET['eid'] : '';
$n = isset($_GET['n']) ? $_GET['n'] : '';


$receiverid1 = isset($_GET['receiverid']) ? $_GET['receiverid'] : '';
$senderid1 = isset($_GET['senderid']) ? $_GET['senderid'] : '';
$markAsRead = isset($_GET['markAsRead']) ? $_GET['markAsRead'] : '';

$name='';
$gender='';



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
	$name1 = $n1[0];
	
	

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $message = $_POST['message'];
    $receiverid = $_POST['receiverid'];
    $senderid = $_POST['senderid'];
	$timestamp = $_POST['timestamp'];
	$status = $_POST['status'];

    date_default_timezone_set("Asia/Kolkata");
    $timestamp = date('Y-m-d H:i:s');

    $tsql = "INSERT INTO msg (senderid, receiverid, message, timestamp, status) VALUES (?, ?, ?, ?, 'UNREAD')";
    $params = array($senderid, $receiverid, $message, $timestamp, $status);

    $stmt = sqlsrv_query($conn, $tsql, $params);

    if ($stmt === false) 
	{
        $error = urlencode(print_r(sqlsrv_errors(), true));
        die("SQL error: $error");
    } 
	
	else 
	{
        sqlsrv_free_stmt($stmt);
        echo "Message sent successfully!";
    }
	
}

if ($markAsRead == 'UNREAD') //True
{
        $receiverid = $_GET['empid'];
        $senderid = $_GET['eid'];
        $update_sql = "UPDATE msg SET status = 'READ' WHERE receiverid = '$senderid' AND senderid = '$receiverid'";
        $update_stmt = sqlsrv_query($conn, $update_sql);
		$_SESSION['status'] = 'READ';

        if ($update_stmt === false) 
		{
            die(print_r(sqlsrv_errors(), true));  
        } 
		
}


if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['fetch'])) 
{     
  
    $tsql = "SELECT * FROM msg 
             WHERE (senderid = ? AND receiverid = ?) 
                OR (senderid = ? AND receiverid = ?)
             ORDER BY timestamp ASC";
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
	
	
////////////////////////////////////////////	
	
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
            height: 440px;
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
		

        .sender1 
		{
			border-radius: 8px 0px 20px 20px;
			padding: 5px 10px 5px 10px;
            background:linear-gradient(135deg, #c6f2f7, #baf0f7 50%,#d4f6fa);
			margin-left:222px;
			margin-top:16px;
            margin-right:auto;			
			display: flex; 
			justify-content: flex-end; 			
			width:240px;
			font-size: 15px;
			overflow-wrap: break-word; /* Ensures long words break */
			word-break: break-word; /* Ensures words break on overflow */
			white-space: normal; /* Allows text wrapping */
			text-align: left;
        }
				
		.sender4 
		{
			
			border-radius: 0px 20px 40px 20px;
			padding: 5px 10px 5px 10px;
            background-color: #ffccbc;
			margin-left:5px;
			width:225px;
			overflow-wrap:break-word;			
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
		
		.sender6 
		{
			
			border-radius: 0px 20px 40px 20px;
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
			margin-top:16px;
			overflow-wrap:break-word;
            text-align: left;
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
		
		.sender8
		{
			
			border-radius:  20px 0px 20px 40px;
			padding: 5px 10px 5px 10px;
			margin-right:-10px;
			font-size:35px;
			overflow-wrap:break-word;
            text-align:right;
        }			

		.sender9
		{
			
			border-radius: 0px 20px 40px 20px;
			padding: 5px 10px 5px 10px;
			margin-left:-10px;
			margin-top:0px;
			font-size:35px;
			width:225px;
			overflow-wrap:break-word;
            text-align: left;
        }		
		
        .sender10 
		{
			color:#F9F9F9;
			font-size:10px;
			margin-left:225px;
			margin-top: -8px;
			height: 30px;
            text-align:left;
        }  

        .sender11
		{
			color:#F9F9F9;
			font-size:10px;
		    margin-left: 40px;
            margin-top: -20px;
			height:30px;
            text-align:left;
        }  	

        .sender12 
		{
			color:#F9F9F9;
			font-size:10px;
			margin-left:215px;
			width:260px;
			margin-top: -8px;
			height: 18px;
            text-align:right;
        }  		
		
        .sender13 
		{
			color:#F9F9F9;
			font-size:10px;
			margin-right: 4px;
			margin-top: -20px;
			height: 2px;
            text-align:right;
        } 		
		
        .sender14 
		{
			color:#F9F9F9;
			font-size:10px;
			margin-left:8px;
			margin-top: -8px;
			height: 18px;
            text-align:left;
        }  

        .sender15
		{
			color:#F9F9F9;
			font-size:10px;
		    margin-left: 8px;
            margin-top: -20px;
			height:2px;
            text-align:left;
        }  		
		
        #message-input 
		{
            width: 375px;
			height:20px;
			margin-top:-8px;
			margin-left:5px;
			caret-color:black;
            padding: 10px;
			outline: 0;
			border:none;
			overflow:hidden;
        }
		
        #send-button 
		{
            padding: 10px 20px;
			color:white;
			background-color: #0d549c;
			transition: background-color 0.2s ease, transform 0.2s ease; 
		}

		#send-button:hover 
		{
			background-color: #0d549c;
			transform: scale(1.05); 
		}
		
		.report
		{
			caret-color:transparent;
		}	

        #emojiButton
		{
            //border-radius: 10px;
			margin-left:5px;
			width: 25px;
			height:25px;
			margin-top:-5px;
			 
		}	
		
		#ebutton
		{		
			float:left;
			margin-top:5px;
		}		
		
		
		#pauseButton
		{
			margin-left:25px;
			margin-top:-30px;
			width:6.5%;
			border-radius:5px;
			background-color:white;
			text-align:center;
			height:25px;
			color:white;
			cursor: pointer;
			transition: background-color 0.2s ease, transform 0.2s ease;			
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
		
		#imageupload
		{		
			float:left;
			margin-top:0px;
		}				
			
    </style>
	<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

</head>
<body class="report" bottommargin="0" topmargin="0" leftmargin="0" rightmargin="0" style="background:linear-gradient(135deg, #f7d9ad, #f7e7d0 50%, #f7d9ad);">
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
		<img src='../leave/dp/jinoy.png' style="width:60px;height:60px;border-radius:50px;margin-left:220px;"><b><?php echo $name1; ?></b></img>
		<?php		
	}
	elseif($receiverid=='1068')
	{
		?>
		<img src='../leave/dp/anandhu.png' style="width:60px;height:60px;border-radius:50px;margin-left:220px;"><b><?php echo $name1; ?></b></img>
		<?php		
	}
	elseif($receiverid=='1001')
	{
		?>
		<img src='../leave/dp/anurag.png' style="width:60px;height:60px;border-radius:50px;margin-left:220px;"><b><?php echo $name1; ?></b></img>
		<?php		
	}
	elseif($receiverid=='1071')
	{
		?>
		<img src='../leave/dp/vyshnav.png' style="width:60px;height:60px;border-radius:50px;margin-left:220px;"><b><?php echo $name1; ?></b></img>
		<?php		
	}
 	elseif($receiverid=='1003')
	{
		?>
		<img src='../leave/dp/rithik.png' style="width:60px;height:60px;border-radius:50px;margin-left:220px;"><b><?php echo $name1; ?></b></img>
		<?php		
	}
 	elseif($receiverid=='1004')
	{
		?>
		<img src='../leave/dp/sarath.png' style="width:60px;height:60px;border-radius:50px;margin-left:220px;"><b><?php echo $name1; ?></b></img>
		<?php	
	} 	
 	elseif($receiverid=='1000')
	{
		?>
		<img src='../leave/dp/sanoop.png' style="width:60px;height:60px;border-radius:50px;margin-left:220px;"><b><?php echo $name1; ?></b></img>
		<?php		
	}	
	
	else
	{
		?>
		<img src='../leave/dp/male1.png' style="width:60px;height:60px;margin-left:220px;"><b><?php echo $name1; ?></b></img>
		<?php		
	}

}


elseif($receiverid=='1070')
{
	?>
	<img src='../leave/dp/manju.png' style="width:60px;height:60px;border-radius:50px;margin-left:220px;"><b><?php echo $name1; ?></b></img>
	<?php	
}

else
{
	?>
	<img src='../leave/dp/female1.png' style="width:60px;height:60px;margin-left:220px;"><b><?php echo $name1; ?></b></img>
	<?php	
}



?>
<br><br>

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

</div>


<button id="pauseButton" style="color:red;" title="<?php echo 'Indicates whether you are online or offline. Turn on the Offline mode if you are trying to copy text. '; ?>">GO OFFLINE</button>

<script>
const senderid = "<?php echo $senderid; ?>";
const receiverid = "<?php echo $receiverid; ?>";
const timestamp = "<?php echo $timestamp; ?>";



///////////////////////  EMOJI  //////////////////////////


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
function populateEmojiPicker() {
  emojis.forEach(emoji => {
    const emojiElement = document.createElement('span');
    emojiElement.textContent = emoji;
    emojiElement.classList.add('emoji');
    emojiElement.addEventListener('click', () => 
	{
      messageinput.value += emoji; // Append emoji to the input field
      emojiPicker.style.display = 'none'; // Hide picker after selecting an emoji
	  messageInput.focus();
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

/////////////////////////  EMOJI  ///////////////////////////



function sendMessage() 
{
    
	var messageInput = document.getElementById('message-input');
    var message = messageInput.value.trim();

    if (message === '') 
	{
        alert('Blank messages cannot be sent.');
        return;
    }

    // Example AJAX request to send the message to the server
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'send_message.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() 
	{
		if (xhr.readyState == 4 && xhr.status == 200) 
		{
			messageInput.value = '';
		}
    };
            
	xhr.send('message=' + encodeURIComponent(message));

    if (!message) return;

    axios.post('chat.php', new URLSearchParams(
	{
  
        'message': message,
        'senderid':senderid,
        'receiverid':receiverid
    }).toString(), 
	{
        headers: 
		{
            'Content-Type': 'application/x-www-form-urlencoded',
        }
    })
    .then(response => 
	{
     
        messageInput.value = '';
        fetchMessages();
    })
    .catch(error => 
	{
        console.error('There was a problem with the request:', error);
    });
	
}


    let isPaused = false;
    
	document.getElementById('pauseButton').addEventListener('click', function() 
	{
		isPaused = !isPaused;
		this.textContent = isPaused ? 'GO LIVE' : 'GO OFFLINE';
		this.style.color = isPaused ? 'green' : 'red'; // Change color based on text content
	});
	

        // Event listener for Enter key press
            var messageInput = document.getElementById('message-input');
            messageInput.addEventListener('keypress', function(event) 
			{
                if (event.key === 'Enter') 
				{
                    event.preventDefault(); // Prevents newline in the input field
                    sendMessage(); // Call the sendMessage function
                }
            });



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
						dayDiv.style.marginLeft = '200px'; // Adjust the margin as needed
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
						dayDiv.style.width = '50px'; // Width for "Today"
					} 
					else if (isYesterday) 
					{
						dayDiv.style.marginLeft = '180px'; // Margin for "Yesterday"
						dayDiv.style.width = '130px'; // Width for "Yesterday"
					} 
					else 
					{
						dayDiv.style.marginLeft = '50px'; // Margin for dates
						dayDiv.style.width = '380px'; // Width for a specific date
						dayDiv.style.textAlign = 'center';
						dayDiv.style.display = 'inline-block';
					}

					chatBox.appendChild(dayDiv); // Append the divider to the chat box
                }              
				
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
				

                // Check if the message is emoji-only using Unicode range
				const isEmoji = msg.message && /^[\u{1F44E}\u{1F91E}\u{1F44D}\u{1F447}\u{1F448}\u{1F44C}\u{1F44F}  \u{1F449}\u{1F44A}\u{1F91D}\u{1F91F}\u{1F3C6}]+|^[\u{1F600}-\u{1F64F}\u{1F300}-\u{1F5FF}\u{1F680}-\u{1F6FF}\u{1F700}-\u{1F77F}\u{1F780}-\u{1F7FF}\u{1F800}-\u{1F8FF}\u{1F900}-\u{1F9FF}\u{1FA00}-\u{1FAFF}\u{2600}-\u{26FF}\u{2700}-\u{27BF}]+$/u.test(msg.message);


                if (msg.senderid === senderid) 
                {
                    msgDiv.classList.add('message', isEmoji ? 'sender8' : 'sender1');
                    timestampDiv.classList.add('timestamp', isEmoji ? 'sender13' : 'sender12');
                } 
                else 
                {
                    msgDiv.classList.add('message', isEmoji ? 'sender9' : 'sender6');
					timestampDiv.classList.add('timestamp', isEmoji ? 'sender15' : 'sender14');
                }

                // Set the message content
                msgDiv.textContent = `${msg.message}`;
				timestampDiv.innerHTML = `${timestampfix5}`;
                
                // Append the message div to the chat box
                chatBox.appendChild(msgDiv);
				chatBox.appendChild(timestampDiv);
				
				previousDate = msgDate;
            });

            // Scroll to the bottom of the chat
            chatBox.scrollTop = chatBox.scrollHeight;
        })
        .catch(error => 
        {
            console.error('Error fetching messages:', error);
        });
    }
}	
	
	


setInterval(fetchMessages, 1000);
fetchMessages();
populateEmojiPicker();
</script>


<br><br><br>

</body>
</html>
