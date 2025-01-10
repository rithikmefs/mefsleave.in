<?php
include "connect.php";
session_start();

$empid = '';
if (isset($_SESSION['empid'])) 
{
    $empid = $_SESSION['empid'];    
}

$flag = '';
if (isset($_POST['flag'])) 
{
    $flag = $_POST['flag'];    
}

$timestamp = '';
if (isset($_SESSION['timestampIds'])) 
{
    $timestamp = $_SESSION['timestampIds'];    
} 

$senderid = '';
if (isset($_SESSION['senderIds'])) 
{
    $senderid = $_SESSION['senderIds'];    
}

$message = '';
if (isset($_SESSION['messageIds'])) 
{
    $message = $_SESSION['messageIds'];    
}

$emptype = '';
if (isset($_SESSION['emptype'])) 
{
    $emptype = $_SESSION['emptype'];    
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

if ($empid != '') 
{
    header("Location: main.php");
}

$error = false;


$status = '';
$c="status = 'UNREAD'";

if ($username != '') 
{
    $sql = "SELECT * FROM emp WITH (NOLOCK) WHERE username='$username' AND password='$password'";
    $res = sqlsrv_query($conn, $sql);
    $row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC);

    if ($row) 
    {
        $username = trim($row['username']);
        $password = trim($row['password']); 
        $empid = trim($row['empid']);
        $flag = trim($row['flag']);
        $emptype = trim($row['emptype']);
        $_SESSION['user'] = $username;
        $_SESSION['empid'] = $empid;
        $_SESSION['flag'] = $flag;
        $_SESSION['emptype'] = $emptype;  


		$sql_status = "SELECT DISTINCT(senderid), status, timestamp, message FROM msg WHERE receiverid = '$empid' AND status = 'UNREAD' order by timestamp";
		$res_status = sqlsrv_query($conn, $sql_status);

		$senderIds = array();
		$timestampIds = array();
		$messageIds = array();

		while ($row1 = sqlsrv_fetch_array($res_status, SQLSRV_FETCH_ASSOC)) 
		{
			$status = trim($row1['status']);
			$timestampIds[] = trim($row1['timestamp']);
			$messageIds[] = trim($row1['message']);
			$senderIds[] = trim($row1['senderid']);
		}

		$_SESSION['status'] = $status;
		$_SESSION['timestampIds'] = $timestampIds;
		$_SESSION['messageIds'] = $messageIds;
		$_SESSION['senderIds'] = $senderIds; 
		
		
/* 		$sql_status = "SELECT DISTINCT(senderid), status, timestamp FROM msg WHERE receiverid = '' AND status = 'UNREAD'"; //Group
		$res_status = sqlsrv_query($conn, $sql_status);

		$senderIds = array();

		while ($row2 = sqlsrv_fetch_array($res_status, SQLSRV_FETCH_ASSOC)) 
		{
			$status = trim($row2['status']);
			$timestamp = trim($row2['timestamp']);
			$senderIds[] = trim($row2['senderid']);
		}

		$_SESSION['status'] = $status;
		$_SESSION['timestamp'] = $timestamp;
		$_SESSION['senderIds'] = $senderIds;  */		
		

        echo "<script>alert('Login successful.');</script>";
        header("Location: main.php"); 
        exit();
    } 
    else 
    {
        $error = true;
    }
}

?>

<html>
<head>
<link rel="icon" href="../leave/images/MEFS.png">
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mefs - Leave Portal</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css"/>
    <style>
      @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap");
      *{
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Poppins", sans-serif;
       }
      body 
      {
        background: linear-gradient(135deg, #FFD699, #FFAD73 70%, #FFD699);
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        height: 100vh;
      }
      .header 
      {
        width: 100%;
        padding: 10px 0;
        text-align: center;
        color: black;
        font-size: 24px;
        font-weight: 600;
      }
      .form_container 
      {
        max-width: 320px;
        width: 100%;
        background: #fff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: rgba(0, 0, 0, 0.1);
        text-align: center;
      }
      
       .form_container img 
      {
        width: 50px;
        height: auto;
        caret-color:transparent;
      }
      
      .form_container h2 
      {
        font-size: 24px;
        color: #0b0217;
        margin-bottom: 20px;
        caret-color:transparent;
      }
      .input_box 
      {
        position: relative;
        margin-top: 30px;
        width: 100%;
        height: 40px;
        
      }
      .input_box input 
      {
        height: 100%;
        width: 100%;
        border: none;
        outline: none;
        padding: 0 30px;
        color: #333;
        transition: all 0.2s ease;
        border-bottom: 1.5px solid #aaaaaa;
        font-size: 15px;
      }
      .input_box input:focus 
      {
        border-color: #7d2ae8;
      }
      .input_box i 
      {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        font-size: 20px;
        color: #707070;
      }
      .input_box i.email,
      .input_box i.password 
      {
        left: 0;
      }
      .input_box i.pw_hide 
      {
        right: 0;
        font-size: 18px;
        cursor: pointer;
      }
	  .input_box .togglePassword 
	  {
	    position: absolute;
	    right: 10px;
	    top: 50%;
	    transform: translateY(-50%);
	    cursor: pointer;
	    color: #666;
	  }
	  .input_box .togglePassword:hover 
	  {
	    color: #333;
	  }	  	  
      .form_container .button 
      {
        caret-color:transparent;
        background:linear-gradient(135deg, #FFA31C , #F87412 70%, #FFA31C);
        margin-top: 30px;
        width: 100%;
        padding: 10px 0;
        border-radius: 10px;
        color: #fff;
        border: none;
        cursor: pointer;
        transition: transform 0.3s ease;
        font-size: 15px;
      }
      
      .form_container .button:hover
      {
       transform: scale(1.1);
       caret-color:transparent;
      }
        a:link
        {
        text-decoration:none;
        }
        .container 
        {
            display:flex;  
            justify-content: center; 
            gap:10px;  
            align-items:center;    
        }
        .error 
        {
            color: red;
            display: none;
        }
        .report
        {
            caret-color:transparent;
        }
		
		.logo 
		{
		  width: 200px;
		  height: auto;
		  animation: rotate 4s linear infinite;
		}
		.logo:hover
		{
			animation: rotate 0s linear infinite;
		}	

		@keyframes rotate 
		{
		  from 
		  {
			transform: rotateY(0deg);
		  }
		  to 
		  {
			transform: rotateY(360deg);
		  }
		}
		
</style>
</head>
<body bottommargin="0" topmargin="0" leftmargin="0" rightmargin="0">
<center>


    <div class="form_container">
      <img src="../leave/images/MEFS.png" alt="Mefs Logo" / class="logo">
      <h2>Login</h2>
      
      <form action="login.php" method="post">
        <div class="input_box">
          <input type="text" style="font-family:poppins;" placeholder="Enter username" name="username" required />
          <i class="uil uil-envelope-alt email"></i>
        </div>
		<div class="input_box">
		  <input type="password" id="passwordField" style="font-family:poppins;" placeholder="Enter password" name="password" required />
		  <i class="uil uil-lock password"></i>
		  <i class="uil uil-eye-slash togglePassword" onclick="togglePasswordVisibility()"></i>
		</div>
        <input type="hidden" name="empid" id="empid" value="<?php echo $empid; ?>">
        <input type="hidden" name="flag" id="flag" value="<?php echo $flag; ?>">
        <input type="hidden" name="status" id="status" value="<?php echo $status; ?>">
        <input type="hidden" name="timestamp" id="timestamp" value="<?php echo $timestamp; ?>">
        <input type="hidden" name="message" id="message" value="<?php echo $message; ?>">
        <input type="hidden" name="senderid" id="senderid" value="<?php echo $senderid; ?>">
        <input type="hidden" name="emptype" id="emptype" value="<?php echo $emptype; ?>">
        <input type="submit" class="button" name="submit" value="Login Now">
      </form>
    </div>
	<br>
   <p class="report" style='height: 10px;color:red;'>  
   <?php 
		  if ($error)
		  {
			echo "Invalid username or password";
		  }
	?> 
	</p>

<script>
function togglePasswordVisibility() 
{
  const passwordField = document.getElementById("passwordField");
  const toggleIcon = document.querySelector(".togglePassword");
  
  if (passwordField.type === "password") 
  {
    passwordField.type = "text";
    toggleIcon.classList.remove("uil-eye-slash");
    toggleIcon.classList.add("uil-eye");
  } 
  else 
  {
    passwordField.type = "password";
    toggleIcon.classList.remove("uil-eye");
    toggleIcon.classList.add("uil-eye-slash");
  }
}
</script>

</body>
</html>  
