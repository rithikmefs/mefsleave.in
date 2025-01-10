<?php
include "connect.php";
session_start();
$inactive = 600; //10 Minutes

if (isset($_SESSION['timeout'])) 
{
    $session_life = time() - $_SESSION['timeout'];
    if ($session_life > $inactive) 
	{
        session_unset();
        session_destroy();
        header("Location:logout.php");
        exit();
    }
}

$_SESSION['timeout'] = time(); 


$status = '';
if (isset($_SESSION['status'])) 
{
    $status = $_SESSION['status'];    
}

$timestamp = '';
if (isset($_SESSION['timestampIds'])) 
{
    $timestamp = $_SESSION['timestampIds'];    
}

$message = '';
if (isset($_SESSION['messageIds'])) 
{
    $message = $_SESSION['messageIds'];    
}

$senderid = '';
if (isset($_SESSION['senderIds'])) 
{
    $senderid = $_SESSION['senderIds'];    
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

$empid='';
if(isset($_SESSION['empid']))
{
	$empid=$_SESSION['empid'];	
}

if($empid=='')
{
	header("Location:logout.php");
} 

//$_SESSION['receiverid'] = $empid;

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

$emptype='';
if(isset($_SESSION['emptype']))
{
	$emptype=$_SESSION['emptype'];	
}

$rowid='';
if(isset($_POST['rowid']))
{
	$rowid=$_POST['rowid'];	
}

$flag='';
if(isset($_SESSION['flag']))
{
	$flag=$_SESSION['flag'];	
}

$power = $_SESSION['power'] ?? 'N'; // Default to 'N' if not set

?>
<html>
<head>
<title>
Mefs - Leave Portal
</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="icon" href="../leave/images/MEFS.png">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
<style>
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap");
.sub
{
	background-color: black;
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 0;
    cursor: pointer;
    transition: background-color 0.2s ease, transform 0.2s ease; 
	border-radius:9px;
}	
.sub:hover 
{
    background-color: #657081;
    transform: scale(1.05); 
}
a:link
{
text-decoration:none;
}
.button-container 
{
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 20px; 
    margin-top: 50px; 
}

.sub1 
{
    background-color: black;
    border: none;
    color: white;
    padding: 15px 32px;
	height:20%;
    text-align: center;
    text-decoration: none;
    display: inline-block;
	border-radius:9px;
    font-size: 16px;
    margin: 0;
    cursor: pointer;
    transition: background-color 0.2s ease, transform 0.2s ease; 
	
}

.sub1:hover 
{
    background-color: #657081;
    transform: scale(1.05); 
}

.open-button 
{
  background-color: #555;
  color: white;
  padding: 16px 20px;
  border: none;
  cursor: pointer;
  opacity: 0.8;
  position: fixed;
  bottom: 23px;
  right: 28px;
  width: 200px;
}

/* The popup chat - hidden by default */
.chat-popup 
{
  display: none;
  position: fixed;
  bottom: 0;
  right: 15px;
  border: 3px solid #f1f1f1;
  z-index: 9;
}

/* Add styles to the form container */
.form-container 
{
  max-width: 300px;
  padding: 10px;
  background-color: white;
  height:270px;
}

/* Full-width textarea */
.form-container textarea 
{
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  border: none;
  background: #f1f1f1;
  resize: none;
  min-height: 200px;
}

/* When the textarea gets focus, do something */
.form-container textarea:focus 
{
  background-color: #ddd;
  outline: none;
}

/* Set a style for the submit/send button */
.form-container .btn 
{
  background-color: #04AA6D;
  color: white;
  padding: 16px 20px;
  border: none;
  cursor: pointer;
  width: 100%;
  margin-bottom:10px;
  opacity: 0.8;
}

/* Add a red background color to the cancel button */
.form-container .cancel 
{
  background-color: red;
}

/* Add some hover effects to buttons */
.form-container .btn:hover, .open-button:hover 
{
  opacity: 1;
}





        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body 
		{
            min-height: 100vh;
            margin: 0;
            display: flex;
			background:linear-gradient(135deg, #f7d9ad, #f7e7d0 50%, #f7d9ad);
            flex-direction: column;
        }

        .header 
		{
            background:#e8ab2e;
            background-size: 400% 400%;
            color: black;
            padding: 12px 20px;
            text-align: center;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
			display: flex;
			justify-content: space-between;
			align-items: center;
            
        }
		
		.header p 
		{
			font-weight:bold;
			align-items:center;		
		}	
		
		.logout-button 
		{
		  background-color: red;
		  padding: 6px 15px;
		  border-radius: 5px;
		  color: white;  
		  border: none;
		  font-size: 15px;  
		  cursor: pointer;  
		  text-decoration: none;
		  caret-color:transparent;
		}		

        .sidebar 
		{
            position: fixed;
            top: 47px; 
            left: 0;
            height: calc(100% - 47px); 
            width: 85px;
            display: flex;
            overflow-x: hidden;
            flex-direction: column;
            background: #665763;
            padding: 25px 20px;
            transition: all 0.4s ease;
            z-index: 999;
        }

        .sidebar:hover 
		{
            width: 260px;
        }

        .sidebar .sidebar-header 
		{
            display: flex;
            align-items: center;
        }

        .sidebar .sidebar-header img 
		{
            width: 42px;
            border-radius: 50%;
        }

        .sidebar .sidebar-header h2 
		{
            color: white;
            font-size: 1rem;           
            white-space: nowrap;
            margin-left: 23px;
        }

        .sidebar-links 
		{
            list-style: none;
            margin-top: 20px;
            height: 80%;
            overflow-y: auto;
            scrollbar-width: none;
        }

        .sidebar-links::-webkit-scrollbar 
		{
            display: none;
        }

        .sidebar-links h4
		{
            color: white;
            white-space: nowrap;
            margin: 10px 0;
            position: relative;
        }

        .sidebar-links h4 span 
		{
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .sidebar:hover .sidebar-links h4 span 
		{
            opacity: 1;
        }

        .sidebar-links .menu-separator 
		{
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            transform: translateY(-50%);
            background: #4f52ba;
            transform-origin: right;
            transition: transform 0.4s ease;
        }

        .sidebar:hover .sidebar-links .menu-separator 
		{
            transform: scaleX(0);
        }

        .sidebar-links li a 
		{
            display: flex;
            align-items: center;
            gap: 0 20px;
            color: white;
            
            white-space: nowrap;
            padding: 15px 10px;
            text-decoration: none;
            transition: color 0.2s ease, background 0.2s ease;
            cursor: pointer;
        }

        .sidebar-links li a.active 
		{
            color: #161a2d;
            background: #ffc815;
            border-radius: 4px;
        }
		
		.sidebar-links li a:hover
		{
		    color: white;
            background:  #a391a0;
            border-radius: 4px;
		}	

        .content 
		{
            margin-top: 60px;
            margin-left: 85px;
            flex-grow: 1;
            padding: 3px 20px;
            transition: margin-left 0.4s ease;
        }

        .content iframe 
		{
            width: 100%;
            height: 90vh;
            border: none;
        } 
		


		.back-button 
		{
		  background-color: transparent;
		  border: none;
		  display: flex;
		  align-items: center;
		  cursor: pointer;
		}

		.back-button img 
		{
		  caret-color:transparent;
		  width: 32px;
		  height: auto; 
		}


		.logo 
		{
		  width: 25px;
		  height: auto;
		}


        .disabled
		{
            pointer-events: none; /* Prevents clicking */
            background-color:grey; /* Greyed out color */
            cursor: not-allowed; /* Changes the cursor to indicate disabled */
        }		

</style>
</head>
<?php


if (!empty($empid)) 
{
	$sql = "select * from emp with (nolock) where empid='$empid' ";
	$res = sqlsrv_query($conn,$sql);
	while($row = sqlsrv_fetch_array( $res, SQLSRV_FETCH_ASSOC))
	{
		if($row['username']==NULL)
		{
			$row['username']='';
		}
		$username  =  trim($row['username']);
		
		if($row['password']==NULL)
		{
			$row['password']='';
		}
		$password = trim($row['password']);
		
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
		
		if($row['empid']==NULL)
		{
			$row['empid']='';
		}	
		$empid = trim($row['empid']);
		
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
		
		if($row['doj']==NULL)
		{
			$row['doj']='';
		}
		$doj  =  trim($row['doj']);
		
		if($row['dojtype']==NULL)
		{
			$row['dojtype']='';
		}
		$dojtype  =  trim($row['dojtype']);

		if($row['gender']==NULL)
		{
			$row['gender']='';
		}
		$gender  =  trim($row['gender']);	
		
	}

	
	$sql2 = "select empid,leavedate,leavetype from leave with (nolock) where empid='$empid'";
	$res2 = sqlsrv_query($conn,$sql2);
	while($row2 = sqlsrv_fetch_array( $res2, SQLSRV_FETCH_ASSOC))
	{	
		if($row2['empid']==NULL)
		{
			$row2['empid']='';
		}
		$empid  =  trim($row2['empid']);
		if($row2['leavedate']==NULL)
		{
			$row2['leavedate']='';
		}
		$leavedate  =  trim($row2['leavedate']);	
		if($row2['leavetype']==NULL)
		{
			$row2['leavetype']='';
		}
		$leavetype  =  trim($row2['leavetype']);		
	}
}	


if ($empid == "999" || $empid == "1108") //ADMIN
{
    if ($power == 'N') 
	{

		$_SESSION['power'] = 'Y';
		$power = 'Y'; 
?>



<style>

/* From Uiverse.io by victoryamaykin */ 
.switch 
{
 position: relative;
 display: inline-block;
 width: 180px;
 height: 34px;
}

.switch input 
{
 display: none;
}

.slider 
{
 position: absolute;
 cursor: pointer;
 top: 0;
 left: 65px;
 right: 0;
 bottom: 0;
 background-color: #28373E;
 -webkit-transition: .9s; /* Slower transition */
 transition: .9s; /* Slower transition */
 border-radius: 34px;
}

.slider:before 
{
 position: absolute;
 content: "";
 height: 26px;
 width: 26px;
 left: 4px;
 bottom: 4px;
 background-color: white;
 -webkit-transition: .9s; /* Slower transition */
 transition: .9s; /* Slower transition */
 border-radius: 50%;
}

input:checked + .slider 
{
 background-color: #2B1A28;
}

input:focus + .slider 
{
 box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before 
{
 -webkit-transform: translateX(26px);
 -ms-transform: translateX(26px);
 transform: translateX(85px);
}

/*------ ADDED CSS ---------*/
.slider:after 
{
 content: 'CORPORATE';
 color: white;
 display: block;
 position: absolute;
 transform: translate(-50%,-50%);
 top: 50%;
 left: 60%;
 font-size: 10px;
 font-family: Verdana, sans-serif;
}

input:checked + .slider:after 
{
 content: 'PERSONAL';
 left: 40%;
}

/*--------- END --------*/

</style>



<?php
	
date_default_timezone_set("Asia/Kolkata");

if(date("h:i:sa") > '05:50:00' && date("h:i:sa") < '05:58:00')
{
?>

<script type="text/javascript">
    
	var idleTime = 0;
    var idleInterval = setInterval(timerIncrement, 60000);
    window.onmousemove = resetTimer;
    window.onkeypress = resetTimer;
    window.onclick = resetTimer;
    window.onscroll = resetTimer;

    function timerIncrement() 
	{
        idleTime++;
        if (idleTime >= 10)// 10 minutes
		{ 
            window.location.href = 'login.php';
        }
    }

    function resetTimer() 
	{
        idleTime = 0;
    }
	
</script>

<style>
.n
{
 opacity:0; 
 transition:opacity 0.0s ease-out;	
}
.sidebar:hover .n
{
    opacity: 1;
	transition:opacity 0.5s ease-in;
}
</style>

<center>

<div class="header">
    <button class="back-button" onclick="goBack()">
      <img src="../leave/images/back-button.png" alt="Back">
    </button>
    <b style="font-size:20px;align-items:center;">MIDDLE EAST FINANCIAL SOFTWARE SOLUTIONS</b>
	<a href="logout.php" class="logout-button">Logout</a>
</div>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <img src='../leave/images/MEFS.png' alt="logo"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b class="n" style="color:white;"><?php echo "MEFS ADMIN"; ?></b>
    </div>
	<br>
	
	<label class="switch">
	  <input type="checkbox" id="personalSwitch">
	  <span class="slider"></span>
	</label>
	
    <ul class="sidebar-links">
        <h4>
            <span>Main Menu</span>
            <div class="menu-separator"></div>
        </h4>
        <li>
            <a href="dashboard.php" class="active" title="<?php echo 'Access denied since maintenance is running';?>">
				<span class="material-symbols-outlined">DASHBOARD</span>Dashboard
			</a>
		</li>

		
        <li>
            <a href="viewprofile.php" class="disabled" onclick="return false;" title="<?php echo 'Access denied since maintenance is running';?>">
                <span class="material-symbols-outlined">add</span>Add Employee 
            </a>
        </li>
        <li>
            <a href="leavereport.php" class="disabled" onclick="return false;">
                <span class="material-symbols-outlined">edit</span>Update Employee
            </a>
        </li>
        <li>
            <a href="leaverequest.php" class="disabled" onclick="return false;">
                <span class="material-symbols-outlined">overview</span>Leave Report
            </a>
        </li>
		
        <li>
            <a href="leaverequest.php" class="disabled" onclick="return false;">
                <span class="material-symbols-outlined">check_box</span>Mark Leave
            </a>
        </li>

		<li>
			<a href="markcompo.php" class="disabled" onclick="return false;">
				<span class="material-symbols-outlined">task_alt</span>Mark Compo
			</a>
		</li>  

		<!--<li>
			<a href="marksalarycut.php" class="disabled" onclick="return false;">
				<span class="material-symbols-outlined">indeterminate_check_box</span>Mark Salary Cut
			</a>
		</li>--> 		

        <h4>
            <span>Account</span>
            <div class="menu-separator"></div>
        </h4>

        <li>
            <a href="changepassword.php" class="disabled"  onclick="return false;">
			<span class="material-symbols-outlined">Password</span>Passwords
			</a>
        </li>
		
        <li>
            <a href="logout.php" class="logout" onclick="logoutAndRedirect(event)"><span class="material-symbols-outlined"> logout </span>Logout</a>
        </li>
    </ul>
</aside>


<div class="content" id="content">
    <iframe id="contentFrame" src="dashboard.php" title="Learning Iframe"></iframe>
</div>

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

    const sidebarLinks = document.querySelectorAll('.sidebar-links li a');
    sidebarLinks.forEach(link => 
	{
		
        link.addEventListener('click', function(e) 
		{
            e.preventDefault();
            sidebarLinks.forEach(link => link.classList.remove('active'));
            this.classList.add('active');

            const href = this.getAttribute('href');
            loadContent(href); 
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
}



else //ADMIN MAIN PAGE
{

?>
<body class="report" bottommargin="0" topmargin="0" leftmargin="0" rightmargin="0" style="background-color: #edb984;caret-color:transparent;">
<script type="text/javascript">
    
	var idleTime = 0;
    var idleInterval = setInterval(timerIncrement, 60000);
    window.onmousemove = resetTimer;
    window.onkeypress = resetTimer;
    window.onclick = resetTimer;
    window.onscroll = resetTimer;

    function timerIncrement() 
	{
        idleTime++;
        if (idleTime >= 10)// 10 minutes
		{ 
            window.location.href = 'login.php';
        }
    }

    function resetTimer() 
	{
        idleTime = 0;
    }
	
</script>

<style>
.n
{
 opacity:0; 
 transition:opacity 0.0s ease-out;	
}
.sidebar:hover .n
{
    opacity: 1;
	transition:opacity 0.5s ease-in;
}

@media screen and (max-width: 768px) 
{
    #sidebar
	{
        width: 200px;
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

<center>

<div class="header">
    <button class="back-button" onclick="goBack()">
      <img src="../leave/images/back-button.png" alt="Back">
    </button>
    <b style="font-size:20px;align-items:center;">MIDDLE EAST FINANCIAL SOFTWARE SOLUTIONS</b>
	<a href="logout.php" class="logout-button">Logout</a>
</div>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <img src='../leave/images/MEFS.png' alt="logo"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b class="n" style="color:white;"><?php echo "MEFS ADMIN"; ?></b>
    </div>
	<br>
	
	<label class="switch">
	  <input type="checkbox" id="personalSwitch">
	  <span class="slider"></span>
	</label>
	
    <ul class="sidebar-links">
        <h4>
            <span>Main Menu</span>
            <div class="menu-separator"></div>
        </h4>
		
        <li>
            <a href="dashboard.php" class="active">
				<span class="material-symbols-outlined">DASHBOARD</span>Dashboard
			</a>
		</li>
		
		<li>
			<a href="regform.php">
				<span class="material-symbols-outlined">Add</span>Add New Employee
			</a>
        </li>

		<li>
		<a href="update.php">
		  <span class="material-symbols-outlined">Edit</span> Update Employee
		</a>
			
        </li>

		<li>
		<a href="view.php">
	      <span class="material-symbols-outlined">overview</span>Leave Report
		</a>
        </li>

		<li>
			<a href="markleave.php">
				<span class="material-symbols-outlined">check_box</span>Mark Leave
			</a>
        </li>		

		<li>
			<a href="markcompo.php">
				<span class="material-symbols-outlined">task_alt</span>Mark Compo
			</a>
		</li>   

		<!--<li>
			<a href="marksalarycut.php">
				<span class="material-symbols-outlined">indeterminate_check_box</span>Mark Salary Cut
			</a>
		</li>-->  		

        <h4>
            <span>Account</span>
            <div class="menu-separator"></div>
        </h4>

        <li>
            <a href="changepassword.php">
			<span class="material-symbols-outlined">Password</span>Passwords
			</a>
        </li>

        <li>
            <a href="logout.php" class="logout" onclick="logoutAndRedirect(event)">
			<span class="material-symbols-outlined">logout</span>Logout
			</a>
        </li>
    </ul>
</aside>


<div class="content" id="content">
    <iframe id="contentFrame" src="dashboard.php" title="Learning Iframe"></iframe>
</div>

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

    const sidebarLinks = document.querySelectorAll('.sidebar-links li a');
    sidebarLinks.forEach(link => 
	{
		
        link.addEventListener('click', function(e) 
		{
            e.preventDefault(); 
            sidebarLinks.forEach(link => link.classList.remove('active'));
            this.classList.add('active');
            const href = this.getAttribute('href');
            loadContent(href); 
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


<br>




<script>


/* document.querySelector('.switch input[type="checkbox"]').addEventListener('change', function () 
{
    let newRole = this.checked ? 'PERSONAL' : 'ADMIN';
    let empid = "<?php echo $_SESSION['empid']; ?>"; // Use session-stored empid or PHP variable
    // alert(newRole , empid )
    let formData = new FormData();
    formData.append('empid', empid);
    formData.append('newRole', newRole);
 


    fetch('changemode.php', 
    {
        method: 'POST',
        body: formData,
    })
    .then((response) => response.text()) // Fetching text response
    .then((data) => 
    {
        console.log('Response from server:', data);

        if (data.includes('success')) 
        {
            console.log('Condition executed successfully');
        } 
        else 
        {
            console.error('Error in processing the request');
        }
    })
    .catch((error) => 
    {
        console.error('Error:', error);
    });
}); */



  
</script>

<script>

document.getElementById('personalSwitch').addEventListener('change', function () 
{
    let powerValue = this.checked ? 'Y' : 'N';

    fetch('updatepower.php', 
	{
        method: 'POST',
        headers: 
		{
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `power=${powerValue}`
    })
    .then(response => response.text())
    .then(data =>
	{
        console.log(data);
        setTimeout(() => 
		{
            location.href = location.href;
        }, 1000);
    })
    .catch(error => 
	{
        console.error('Error:', error);
    });
});


</script>
	
<?php
	
	
	}



} 


elseif (($empid == '999' && $power == 'Y') || ($empid == '1108' && $power == 'Y')) //ADMIN'S PERSONAL MAIN PAGE 
{

		$_SESSION['power'] = 'N';
		$power = 'N'; 
	
include "header2.html";
?>

<style>

        .sidebar1 
		{
            position: fixed;
            top: 47px; 
            left: 0;
            height: calc(100% - 47px); 
            width: 85px;
            display: flex;
            overflow-x: hidden;
            flex-direction: column;
            background: #54646b;
            padding: 25px 20px;
            transition: all 0.4s ease;
            z-index: 999;
        }

        .sidebar1:hover 
		{
            width: 260px;
        }

        .sidebar1 .sidebar1-header 
		{
            display: flex;
            align-items: center;
        }

        .sidebar1 .sidebar1-header img 
		{
            width: 42px;
            border-radius: 50%;
        }

        .sidebar1 .sidebar1-header h2 
		{
            color: white;
            font-size: 1rem;            
            white-space: nowrap;
            margin-left: 23px;
        }

        .sidebar1-links 
		{
            list-style: none;
            margin-top: 20px;
            height: 80%;
            overflow-y: auto;
            scrollbar-width: none;
        }

        .sidebar1-links::-webkit-scrollbar 
		{
            display: none;
        }

        .sidebar1-links h4
		{
            color: white;
            white-space: nowrap;
            margin: 10px 0;
            position: relative;
        }

        .sidebar1-links h4 span 
		{
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .sidebar1:hover .sidebar1-links h4 span 
		{
            opacity: 1;
        }

        .sidebar1-links .menu-separator 
		{
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            transform: translateY(-50%);
            background: #4f52ba;
            transform-origin: right;
            transition: transform 0.4s ease;
        }

        .sidebar1:hover .sidebar1-links .menu-separator 
		{
            transform: scaleX(0);
        }

        .sidebar1-links li a 
		{
            display: flex;
            align-items: center;
            gap: 0 20px;
            color: white;          
            white-space: nowrap;
            padding: 15px 10px;
            text-decoration: none;
            transition: color 0.2s ease, background 0.2s ease;
            cursor: pointer;
        }

        .sidebar1-links li a.active 
		{
            color: #161a2d;
            background: #ffc815;
            border-radius: 4px;
        }
		
		.sidebar1-links li a:hover
		{
		    color: white;
            background:  #738187;
            border-radius: 4px;
		}


.switch 
{
 position: relative;
 display: inline-block;
 width: 180px;
 height: 34px;
}

.switch input 
{
 display: none;
}

.slider 
{
 position: absolute;
 cursor: pointer;
 top: 0;
 left: 80;
 right: -15px; /* Adjusted to place the slider handle initially at the right */
 bottom: 0;
 background-color: #2B1A28;
 -webkit-transition: .9s; /* Slower transition */
 transition: .9s; /* Slower transition */
 border-radius: 34px;
}

.slider:before 
{
 position: absolute;
 content: "";
 height: 26px;
 width: 26px;
 right: 4px; /* Start at the right */
 bottom: 4px;
 background-color: white;
 -webkit-transition: .9s; /* Slower transition */
 transition: .9s; /* Slower transition */
 border-radius: 50%;
}

input:checked + .slider 
{
 background-color: #28373E;
}

input:focus + .slider 
{
 box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before 
{
 -webkit-transform: translateX(-85px); /* Move from right to left */
 -ms-transform: translateX(-85px);
 transform: translateX(-85px);
}

/*------ ADDED CSS ---------*/
.slider:after 
{
 content: 'PERSONAL';
 color: white;
 display: block;
 position: absolute;
 transform: translate(-50%, -50%);
 top: 50%;
 left: 40%;
 font-size: 10px;
 font-family: Verdana, sans-serif;
}

input:checked + .slider:after 
{
 content: 'CORPORATE';
 left: 55%;
}



</style>


<?php
date_default_timezone_set("Asia/Kolkata");	
if(date("h:i:sa") > '05:50:00' && date("h:i:sa")< '05:58:00')
{
?>


	<script type="text/javascript">
    
	var idleTime = 0;
    var idleInterval = setInterval(timerIncrement, 60000); 
    window.onmousemove = resetTimer;
    window.onkeypress = resetTimer;
    window.onclick = resetTimer;
    window.onscroll = resetTimer;

    function timerIncrement() 
	{
        idleTime++;
        if (idleTime >= 10)// 10 minutes
		{ 
            window.location.href = 'login.php';
        }
    }

    function resetTimer() 
	{
        idleTime = 0;
    }
	
</script>


<center>

<div class="header">
    <button class="back-button" onclick="goBack()">
      <img src="../leave/images/back-button.png" alt="Back">
    </button>
    <b style="font-size:20px;align-items:center;font-weight: bold;">MIDDLE EAST FINANCIAL SOFTWARE SOLUTIONS &nbsp; <img src="../leave/images/MEFS.png" alt="Mefs Logo" / class="logo"></b>
	<a href="logout.php" class="logout-button">Logout</a>
</div>
<aside class="sidebar1" id="sidebar1">
    <div class="sidebar1-header">


<script>
localStorage.setItem('profileImagePath', '<?php echo $imagePath; ?>');
</script>		
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
    '1108' => 'rajagopal.png'
];

if ($empid == '1044') 
{
    $defaultImage = 'jinoy.png';
} 
elseif ($empid == '1068') 
{
    $defaultImage = 'anandhu.png';
} 
elseif ($empid == '1001') 
{
    $defaultImage = 'anurag.png';
} 
elseif ($empid == '1071') 
{
    $defaultImage = 'vyshnav.png';
} 
elseif ($empid == '1003') 
{
    $defaultImage = 'rithik.png';
} 
elseif ($empid == '1004') 
{
    $defaultImage = 'sarath.png';
} 
elseif ($empid == '1000') 
{
    $defaultImage = 'sanoop.png';
} 
elseif ($empid == '1091') 
{
    $defaultImage = 'akhil.png';
} 
elseif ($empid == '1002') 
{
    $defaultImage = 'anna.png';
} 
elseif ($empid == '1070') 
{
    $defaultImage = 'manju.png';
} 
elseif ($empid == '1118') 
{
    $defaultImage = 'test1.png';
}
elseif ($empid == '1119') 
{
    $defaultImage = 'test2.png';
}
elseif ($empid == '999') 
{
    $defaultImage = 'mefs.png';
}
elseif ($empid == '1108') 
{
    $defaultImage = 'rajagopal.png';
}
else 
{
    $defaultImage = ($gender == 'MALE') ? 'male1.png' : 'female1.png';//style="width:55px;height:55px;"
}

$imageFile = array_key_exists($empid, $profileImages) ? $profileImages[$empid] : $defaultImage;
$imagePath = "../leave/dp/$imageFile"; 
 
?>
<?php

$imagePath = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : "../leave/dp/$defaultImage";
?>
<img id="profileImage" src="<?php echo $imagePath . '?' . time(); ?>" style="width:55px;height:55px;border-radius:50px;"><br>

<script>
const updatedImagePath = localStorage.getItem('profileImagePath');
if (updatedImagePath) 
{
    document.getElementById('profileImage').src = updatedImagePath + '?' + new Date().getTime();
    localStorage.removeItem('profileImagePath');
}
</script>

	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style="color:white;"><?php echo $name; ?></b>
    </div>
    <ul class="sidebar1-links">
        <h4>
            <span>Main Menu</span>
            <div class="menu-separator"></div>
        </h4>
        <li>
            <a href="dashboard.php" class="active"  title="<?php echo 'Access denied since maintenance is running';?>">
				<span class="material-symbols-outlined">DASHBOARD</span>Dashboard
			</a>
		</li>
		
        <li>
            <a href="viewprofile.php" class="disabled" onclick="return false;">
                <span class="material-symbols-outlined">account_circle</span>My Profile 
            </a>
        </li>
		
        <li>
            <a href="leavereport.php" class="disabled" onclick="return false;">
                <span class="material-symbols-outlined">overview</span>Leave Report
            </a>
        </li>
		
        <li>
            <a href="leaverequest.php" class="disabled" onclick="return false;">
                <span class="material-symbols-outlined">event_note</span>Request for Leave
            </a>
        </li>

		<li>
			<a href="leaverankings.php" class="disabled" onclick="return false;">
				<span class="material-symbols-outlined">leaderboard</span>Leave Rankings
			</a>
		</li>
		
        <h4>
            <span>Account</span>
            <div class="menu-separator"></div>
        </h4>

        <li>
            <a href="changepassword.php" class="disabled" onclick="return false;">
				<span class="material-symbols-outlined">Password</span>Passwords
			</a>
        </li>

        <li>
            <a href="logout.php" class="logout" onclick="logoutAndRedirect(event)"><span class="material-symbols-outlined"> logout </span>Logout</a>
        </li>
		
    </ul>
</aside>


<div class="content" id="content">
    <iframe id="contentFrame" src="dashboard.php" title="Learning Iframe"></iframe>
</div>

<script>
    function loadContent(url) 
	{
        document.getElementById('contentFrame').src = url;
    }

    const sidebar = document.getElementById('sidebar1');
    const content = document.getElementById('content');

    sidebar.addEventListener('mouseover', () => 
	{
        content.style.marginLeft = '260px';
    });

    sidebar.addEventListener('mouseout', () => 
	{
        content.style.marginLeft = '85px';
    });

    const sidebarLinks = document.querySelectorAll('.sidebar1-links li a');
    sidebarLinks.forEach(link => 
	{
		
        link.addEventListener('click', function(e) 
		{
            e.preventDefault();
            sidebarLinks.forEach(link => link.classList.remove('active'));
            this.classList.add('active');
            const href = this.getAttribute('href');
            loadContent(href); 
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
}
else
{	
$totalLeaveBalance1='';

?>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap">
<body class="report" bottommargin="0" topmargin="0" leftmargin="0" rightmargin="0" style="background-color: #edb984;caret-color:transparent;">	

<script type="text/javascript">
    
	var idleTime = 0;

    // Increment the idle time counter every minute.
    var idleInterval = setInterval(timerIncrement, 60000); // 1 minute

    // Zero the idle timer on mouse movement, keypress, etc.
    window.onmousemove = resetTimer;
    window.onkeypress = resetTimer;
    window.onclick = resetTimer;
    window.onscroll = resetTimer;

    function timerIncrement() 
	{
        idleTime++;
        if (idleTime >= 10)			
		{
            window.location.href = 'login.php';
        }
    }

    function resetTimer() 
	{
        idleTime = 0;
    }
	
</script>


<style>

/* .drop-icon 
{
    position: relative;
    animation: drop 0.6s ease-out;
} */

@keyframes drop 
{
    0% { transform: translateY(-20px); opacity: 0; }
    60% { transform: translateY(5px); opacity: 1; }
    80% { transform: translateY(-2px); }
    100% { transform: translateY(0); }
}
	
	
.dropdown-content1
{
	display: none;
    position: absolute;
    top: 50px;
    margin-left: 200px;
    background-color: #ffffff;
    min-width: 250px;
    border: 1px solid #ccc;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    border-radius: 8px;
    z-index: 1;
}

.dropdown-content2
{
	display: none;
    position: absolute;
    top: 50px;
	height:50px;
    margin-left: 200px;
    background-color: #ffffff;
    min-width: 250px;
    border: 1px solid #ccc;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    border-radius: 8px;
    z-index: 1;
}

.message-item
{
    padding: 10px;
    border-bottom: 1px solid #eee;
}

.message-item:last-child 
{
    border-bottom: none;
}

.message-item strong 
{
    color: #333;
}

.message-item .time 
{
    font-size: 12px;
    color: #888;
}
	
</style>


<center>

<div class="header">
    <button class="back-button" onclick="goBack()">
      <img src="../leave/images/back-button.png" alt="Back">
    </button>
    <b style="font-size:20px;align-items:center;margin-left:460px;font-weight: bold;">MIDDLE EAST FINANCIAL SOFTWARE SOLUTIONS &nbsp; <img src="../leave/images/MEFS.png" alt="Mefs Logo" / class="logo"></b>
	
	
<?php


$names = array();
foreach ($_SESSION['senderIds'] as $senderid) 
{

    if ($senderid == '1000') { $names[] = 'Sanoop'; }
    if ($senderid == '1004') { $names[] = 'Sarath'; }
    if ($senderid == '1001') { $names[] = 'Anurag'; }
    if ($senderid == '1002') { $names[] = 'Anna'; }
    if ($senderid == '1003') { $names[] = 'Rithik'; }
    if ($senderid == '1044') { $names[] = 'Jinoy'; }
    if ($senderid == '1068') { $names[] = 'Anandhu'; }
    if ($senderid == '1091') { $names[] = 'Akhil'; }
    if ($senderid == '1070') { $names[] = 'Manju'; }
    if ($senderid == '1071') { $names[] = 'Vyshnav'; }
    if ($senderid == '1118') { $names[] = 'Test1'; }
    if ($senderid == '1119') { $names[] = 'Test2'; }
    if ($senderid == '999') { $names[] = 'MEFS'; }
    if ($senderid == '1108') { $names[] = 'MEFS'; }
}


$names = array_unique($names);

$times = array();
$timedelayList = array(); 

if (isset($_SESSION['timestampIds'])) 
{
    $uniqueTimestamps = array_unique($_SESSION['timestampIds']);
    
    foreach ($uniqueTimestamps as $timestamp) 
	{

        $times[] = $timestamp;

        $receivedTime = !empty($timestamp) ? new DateTime($timestamp) : new DateTime();
        $currentTime = new DateTime();
        $interval = $currentTime->diff($receivedTime);

		if ($interval->m > 1) 
		{
			$timedelay = $interval->m . " months ago";
		} 
		elseif ($interval->m > 0) 
		{
			$timedelay = $interval->m . " month ago";
		} 
        elseif ($interval->d > 1) 
		{
            $timedelay = $interval->d . " days ago";
        } 
		elseif ($interval->d > 0) 
		{
            $timedelay = $interval->d . " day ago";
        } 
		elseif ($interval->h > 1) 
		{
            $timedelay = $interval->h . " hours ago";
        } 
		elseif ($interval->h > 0) 
		{
            $timedelay = $interval->h . " hour ago";
        } 
		elseif ($interval->i > 1) 
		{
            $timedelay = $interval->i . " minutes ago";
        } 
		else 
		{
            $timedelay = "Just now";
        }

        $timedelayList[] = $timedelay;
    }
}

$newMessages = array();

if (count($names) > 1) 
{
	foreach ($names as $index => $nameList) 
	{
		$time = isset($timedelayList[$index]) ? $timedelayList[$index] : "Just now"; 
		$currentMessage = isset($message[$index]) ? $message[$index] : 'No new messages';
		
		if (strlen($currentMessage) > 23) 
		{
			$truncatedMessage = substr($currentMessage, 0, 23);
			$lastSpace = strrpos($truncatedMessage, ' '); // Find last space in the truncated message
			$currentMessage = $lastSpace !== false ? substr($truncatedMessage, 0, $lastSpace) : $truncatedMessage;
			$currentMessage = rtrim($currentMessage) . '...';
		}
		
		$newMessages[] = 
		[
			'name' => $nameList,
			'message' => $currentMessage,
			'time' => $time
		];
	}
} 
else 
{
	foreach ($names as $index => $nameList) 
	{
		$time = isset($timedelayList[$index]) ? $timedelayList[$index] : "Just now";
		$nameList = implode('', $names); 		
		$currentMessage = isset($message[$index]) ? $message[$index] : 'No new messages';
		if (strlen($currentMessage) > 23) 
		{
			$truncatedMessage = substr($currentMessage, 0, 23);
			$lastSpace = strrpos($truncatedMessage, ' '); // Find last space in the truncated message
			$currentMessage = $lastSpace !== false ? substr($truncatedMessage, 0, $lastSpace) : $truncatedMessage;
			$currentMessage = rtrim($currentMessage) . '...';
		}

		$newMessages[] = 
		[
			'name' => $nameList,
			'message' => $currentMessage,
			'time' => $time
		];
	}
}





$status1 = ($status == 'UNREAD' && count($newMessages) > 0);
?>	
<div style="position: relative;">
 
   <?php 
    if ($status1 && count($names) == 1) 
	{ 
	$status1 = 'UNREAD';
	?>
        <img src='../leave/images/notification.png' class='drop-icon' id="notificationIcon" style='width:35px;height:35px;margin-left:370px; cursor: pointer;position: relative; animation: drop 0.6s ease-out;'>
    <?php 
	}     
	elseif ($status1 && count($names) == 2) 
	{ 
	$status1 = 'UNREAD';
	?>
        <img src='../leave/images/notification2.png' class='drop-icon' id="notificationIcon" style='width:35px;height:35px;margin-left:370px; cursor: pointer;position: relative; animation: drop 0.6s ease-out;'>
    <?php 
	}  
	elseif ($status1 && count($names) == 3) 
	{ 
	$status1 = 'UNREAD';
	?>
        <img src='../leave/images/notification3.png' class='drop-icon' id="notificationIcon" style='width:35px;height:35px;margin-left:370px; cursor: pointer;position: relative; animation: drop 0.6s ease-out;'>
    <?php 
	}	
    elseif ($status1) 
	{ 
	$status1 = 'UNREAD';
	?>
        <img src='../leave/images/notification3plus.png' class='drop-icon' id="notificationIcon" style='width:35px;height:35px;margin-left:370px; cursor: pointer;position: relative; animation: drop 0.6s ease-out;'>
    <?php 
	} 
 	
	else 
	{
	$status1 = 'READ';		
	?>
        <img src='../leave/images/bellicon.png' class='drop-icon' id="notificationIcon" style='width:35px;height:35px;margin-left:370px; cursor: pointer;position: relative;animation: drop 0.6s ease-out;' title="You have no new messages">
    <?php 
	} 
	
	
	if ($status1 == 'UNREAD') 
	{ 
    ?>
		<div id="messageDropdown1" class="dropdown-content1">
			<?php 
			$newMessages = array_reverse($newMessages); 
			foreach ($newMessages as $msg) 
			{ 
			?>
				<div class="message-item">
				    <img align=left src="../leave/dp/<?php echo strtolower($msg['name']); ?>.png" alt="Profile Picture" style="width:35px; height:35px; border-radius:50%; vertical-align: middle; margin-right: 8px;">
					<strong style='margin-right:20px;'><?php echo $msg['name']; ?></strong><br>
					<span style='margin-left:-30px;'><?php echo $msg['message']; ?></span>
					<div style='margin-left:22px;' class="time"><?php echo $msg['time']; ?></div>
				</div>
			<?php 
			} 
			?>
		</div>
    <?php 
	}
	elseif(count($names) < 1)
	{
    ?>
        <div id="messageDropdown2" class="dropdown-content2">
            <?php foreach ($newMessages as $msg) 
			{ 
			?>
            <?php 
			} 
			?>
        </div>
    <?php 		
	}
	else
	{
		
	}
	?>
</div>
	<a href="logout.php" class="logout-button">Logout</a>
</div>
<aside class="sidebar1" id="sidebar1">
    <div class="sidebar1-header">


<script>


document.getElementById("notificationIcon").addEventListener("click", function () 
{
	const dropdown = document.getElementById("messageDropdown1");
    dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
});



document.getElementById("notificationIcon").addEventListener("click", function () 
{
    const dropdown = document.getElementById("messageDropdown2");

    if (dropdown.children.length === 0) 
	{
        dropdown.innerHTML = "<strong style='margin-top:10px;display:block;'>No new messages!</strong>";
    }

    if (dropdown.style.display === "block") 
	{
        dropdown.style.display = "none";
        dropdown.style.opacity = 0;
    } 
	else 
	{
        dropdown.style.display = "block";
        dropdown.style.opacity = 1;

        setTimeout(function () 
		{
            let fadeEffect = setInterval(function () 
			{
                if (dropdown.style.opacity > 0) 
				{
                    dropdown.style.opacity -= 0.05;
                } 
				else 
				{
                    clearInterval(fadeEffect);
                    dropdown.style.display = "none";
                }
            }, 50);
        }, 2000);
    }
});





</script>	


<script>
localStorage.setItem('profileImagePath', '<?php echo $imagePath; ?>');
</script>	


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
    '1108' => 'rajagopal.png'
];

if ($empid == '1044') 
{
    $defaultImage = 'jinoy.png';
} 
elseif ($empid == '1068') 
{
    $defaultImage = 'anandhu.png';
} 
elseif ($empid == '1001') 
{
    $defaultImage = 'anurag.png';
} 
elseif ($empid == '1071') 
{
    $defaultImage = 'vyshnav.png';
} 
elseif ($empid == '1003') 
{
    $defaultImage = 'rithik.png';
} 
elseif ($empid == '1004') 
{
    $defaultImage = 'sarath.png';
} 
elseif ($empid == '1000') 
{
    $defaultImage = 'sanoop.png';
} 
elseif ($empid == '1091') 
{
    $defaultImage = 'akhil.png';
} 
elseif ($empid == '1002') 
{
    $defaultImage = 'anna.png';
} 
elseif ($empid == '1070') 
{
    $defaultImage = 'manju.png';
} 
elseif ($empid == '1118') 
{
    $defaultImage = 'test1.png';
}
elseif ($empid == '1119') 
{
    $defaultImage = 'test2.png';
}
elseif ($empid == '999') 
{
    $defaultImage = 'arun.png';
}
elseif ($empid == '1108') 
{
    $defaultImage = 'rajagopal.png';
}
else 
{
    $defaultImage = ($gender == 'MALE') ? 'male1.png' : 'female1.png';//style="width:55px;height:55px;"
}
  
$imageFile = array_key_exists($empid, $profileImages) ? $profileImages[$empid] : $defaultImage;
$imagePath = "../leave/dp/$imageFile"; 
 
?>
<?php

$imagePath = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : "../leave/dp/$defaultImage";
?>

<img id="profileImage" src="<?php echo $imagePath . '?' . time(); ?>" style="width:55px;height:55px;border-radius:50px;"><br>

<script>
const updatedImagePath = localStorage.getItem('profileImagePath');
if (updatedImagePath) 
{
    document.getElementById('profileImage').src = updatedImagePath + '?' + new Date().getTime();
    localStorage.removeItem('profileImagePath');
}
</script>

<style>
.n
{
 opacity:0; 
 transition:opacity 0.0s ease-out;	
}
.sidebar1:hover .n
{
    opacity: 1;
	transition:opacity 0.5s ease-in;
}

@media screen and (max-width: 768px) 
{
    #sidebar1
	{
        width: 200px;
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

	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style="color:white;" class="n"><?php echo $name; ?></b>
    </div>
	<br>
	
	<label class="switch">
	  <input type="checkbox" id="personalSwitch">
	  <span class="slider"></span>
	</label>
	
    <ul class="sidebar1-links">
	
        <h4>
            <span>Main Menu</span>
            <div class="menu-separator"></div>
        </h4>
		
        <li>
            <a href="dashboard.php" class="active">
				<span class="material-symbols-outlined">DASHBOARD</span>Dashboard
			</a>
		</li>
		
		<li>
			<a href="viewprofile.php">
				<span class="material-symbols-outlined">account_circle</span>My Profile 
			</a>
        </li>

		<li>
			<a href="leavereport.php">
				<span class="material-symbols-outlined">assignment</span>Leave Report
			</a>			
        </li>

		<li>		
			<a href="leaverequest.php">		
				<span class="material-symbols-outlined">event</span>Request for Leave
			</a>		
        </li>	

		<li>	
			<a href="leaverankings.php">
				<span class="material-symbols-outlined">leaderboard</span>Leave Rankings
			</a>			
		</li>
		
        <h4>
            <span>Account</span>
            <div class="menu-separator"></div>
        </h4>

        <li>
            <a href="changepassword.php">
				<span class="material-symbols-outlined">Password</span>Passwords
			</a>
        </li>

        <li>
            <a href="logout.php" class="logout" onclick="logoutAndRedirect(event)"><span class="material-symbols-outlined"> logout </span>Logout</a>
        </li>
		
    </ul>
</aside>


<div class="content" id="content">
    <iframe id="contentFrame" src="dashboard.php" title="Learning Iframe"></iframe>
</div>

<script>

    function loadContent(url) 
	{
        document.getElementById('contentFrame').src = url;
    }

    const sidebar = document.getElementById('sidebar1');
    const content = document.getElementById('content');

    sidebar.addEventListener('mouseover', () => 
	{
        content.style.marginLeft = '260px';
    });

    sidebar.addEventListener('mouseout', () => 
	{
        content.style.marginLeft = '85px';
    });

    const sidebarLinks = document.querySelectorAll('.sidebar1-links li a');
    sidebarLinks.forEach(link => 
	{
		
        link.addEventListener('click', function(e) 
		{
            e.preventDefault();
            sidebarLinks.forEach(link => link.classList.remove('active'));
            this.classList.add('active');
            const href = this.getAttribute('href');
            loadContent(href);
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


<script>

document.getElementById('personalSwitch').addEventListener('change', function () 
{
    let powerValue = this.checked ? 'N' : 'Y';

    fetch('updatepower.php', 
	{
        method: 'POST',
        headers: 
		{
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `power=${powerValue}`
    })
    .then(response => response.text())
    .then(data =>
	{
        console.log(data);
        setTimeout(() => 
		{
            location.href = location.href;
        }, 1000);
    })
    .catch(error => 
	{
        console.error('Error:', error);
    });
});


</script>

<br>	
<center>
<?php

$color = "color:red;";


?>
</center>

<center>
&nbsp;&nbsp;
&nbsp;&nbsp;
&nbsp;&nbsp;

</div>

</div>

</center>

<?php
	

$eid='';

	
	}
	
	
	
?>

<style>	
select 
{
    width: 200px;
    padding: 5px;
}

.select-option img 
{
    width: 30px;
    height: 30px;
    border-radius: 50%;
    vertical-align: middle;
    margin-right: 5px;
}

.custom-select 
{
    width: 250px;
    position: relative;
    cursor: pointer;
    border-radius: 8px;
    border: 1px solid #ccc;
    padding: 5px;
}

.selected-option 
{
    display: flex;
    align-items: center;
    height: 30px;
	font-size:13px;
	font-family:poppins;
    padding-left: 10px;
}

.option-image 
{
    width: 30px;
    height: 30px;
    border-radius: 50%;
    margin-right: 10px;
}

.options-list 
{
    display: none;
    position: absolute;
    width: 100%;
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 8px;
    max-height: 280px;
    overflow-y: auto;
    z-index: 100;
}

.options-list.show 
{
    display: block;
	font-size:12px;
	bottom:100%;
}

.option 
{
    display: flex;
    align-items: center;
	font-size:13px;
    padding: 5px 10px;
    cursor: pointer;
}

.option:hover 
{
    background-color:#E6AA2E;
}

        #notification 
		{
			position: fixed;
			top: 75px;
			right: 20px;
			padding: 15px 20px;
			width: 300px;
			background: linear-gradient(135deg, #4caf50, #2e7d32);
			color: #ffffff;
			font-size: 16px;
			font-weight: bold;
			border-radius: 8px;
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
			opacity: 0;
			transition: opacity 0.3s ease-in-out;
			z-index:-2;
        }
		
		
        .new-message 
		{
            background-color: #28a745;
            color: white;
			width:45px;
            font-size: 12px;
            padding: 4px 8px;
            border-radius: 12px;
            margin-left: 20px;
            margin-right: 0;
            text-align: center;
            white-space: nowrap;
        }	
		
		svg 
		{
		  animation: fade 2s infinite;
		}

		@keyframes fade 
		{
		  0% { opacity: 1; }
		  50% { opacity: 0.5; }
		  100% { opacity: 1; }
		}

</style>

<?php	
$eid=$empid;	

$n = $name;

$senderdot = '';

$dot = '';


$dot = array();
foreach ($_SESSION['senderIds'] as $senderid) 
{

    if ($senderid == '1000') { $dot[] = '.'; }
    if ($senderid == '1004') { $dot[] = '.'; }
    if ($senderid == '1001') { $dot[] = '.'; }
    if ($senderid == '1002') { $dot[] = '.'; }
    if ($senderid == '1003') { $dot[] = '.'; }
    if ($senderid == '1044') { $dot[] = '.'; }
    if ($senderid == '1068') { $dot[] = '.'; }
    if ($senderid == '1091') { $dot[] = '.'; }
    if ($senderid == '1070') { $dot[] = '.'; }
    if ($senderid == '1071') { $dot[] = '.'; }
    if ($senderid == '999') { $dot[] = '.'; }
    if ($senderid == '1108') { $dot[] = '.'; }
	
}

			
			$sqlstatus = "SELECT senderid FROM msg WHERE receiverid = '$empid' AND status = 'UNREAD' order by timestamp";
			$resstatus = sqlsrv_query($conn, $sqlstatus);
			$senderdot = [];
			while ($row10 = sqlsrv_fetch_array($resstatus, SQLSRV_FETCH_ASSOC)) 
			{
				$currentSender = isset($row10['senderid']) ? trim($row10['senderid']) : '';
				if($currentSender) 
				{
					$senderdot[] = $currentSender;
				}
				
			}	
			

    $options = [];////////////////////
    $options[] = "<option value='group' data-image='../leave/dp/group.png' sts=''>GROUP CHAT</option>";
	
	$sql8 = "SELECT name,empid FROM emp WITH (NOLOCK) WHERE flag='Y' AND empid!='$empid' AND empid!='1118' AND empid!='1119' ORDER BY name";
	$res8 = sqlsrv_query($conn, $sql8);
	if ($res8) 
	{
		
        while ($row8 = sqlsrv_fetch_array($res8, SQLSRV_FETCH_ASSOC)) 
		{
			$empid1 = isset($row8['empid']) ? trim($row8['empid']) : '';
			$name1 = isset($row8['name']) ? trim($row8['name']) : '';
			
			$desiredLength = 22;
			$currentLength = strlen($name1);

			if ($currentLength < $desiredLength) 
			{
				$spacesNeeded = $desiredLength - $currentLength;
				$name1 .= str_repeat("&nbsp;", $spacesNeeded);
			}
			
			
			$dot = '';
			$sts = '';
			
			if ($empid1 == '1044') 
			{
				$defaultImage = 'jinoy.png';
			} 
			elseif ($empid1 == '1068') 
			{
				$defaultImage = 'anandhu.png';
				$name1 = 'ANANDHU';
			} 
			elseif ($empid1 == '1001') 
			{
				$defaultImage = 'anurag.png';
			} 
			elseif ($empid1 == '1071') 
			{
				$defaultImage = 'vyshnav.png';
			} 
			elseif ($empid1 == '1003') 
			{
				$defaultImage = 'rithik.png';
			} 
			elseif ($empid1 == '1004') 
			{
				$defaultImage = 'sarath.png';
			} 
			elseif ($empid1 == '1000') 
			{
				$defaultImage = 'sanoop.png';
				$name1 = 'SANOOP';
			} 
			elseif ($empid1 == '1091') 
			{
				$defaultImage = 'akhil.png';
			} 
			elseif ($empid1 == '1002') 
			{
				$defaultImage = 'anna.png';
			} 		
			elseif ($empid1 == '1070') 
			{
				$defaultImage = 'manju.png';
			} 
			elseif ($empid1 == '1118') 
			{
				$defaultImage = 'test1.png';
			} 
			elseif ($empid1 == '1119') 
			{
				$defaultImage = 'test2.png';
			} 			
			elseif ($empid1 == '999')//ARUN 
			{
				$defaultImage = 'arun.png';
				$name1 = 'ARUN PANICKER';
			}	
			elseif ($empid1 == '1108')//RAJAGOPAL 
			{
				$defaultImage = 'rajagopal.png';
				$name1 = 'RAJAGOPAL';
			}			
			else 
			{
				$defaultImage = ($gender == 'MALE') ? 'male1.png' : 'female1.png';//style="width:55px;height:55px;"
			}

			if(in_array($empid1, $senderdot))
			{
				$dot = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;🟢";
				//$sts = "<svg xmlns='http://www.w3.org/2000/svg' height='10' width='10' viewBox='0 0 512 512'> <path fill='#5eeb00' 
				//d='M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512z'/> </svg>";
				$sts = "<img style = 'height:40px;width:40px;float:right;' src=\"../leave/images/new.png\">";
				//$sts = "<span style=\"color:green;margin-right:auto;text-align: left;;float:right;\">(NEW)</span>";
			}
			
			$options[] = "<option value='$empid1' data-image='../leave/dp/$defaultImage' sts='$sts'>$name1</option>";				

        }
	}

	
?>	
	
<button class="open-button" onclick="openList()">CHAT WITH FRIENDS</button>

<div class="chat-popup" id="myFriends" style="width:290px;">
  
  
 <form action="chat1.php" class="form-container" method="get">
    <h1>Chat</h1>
	<input type="hidden" name="eid" id="eid" value="<?php echo $eid; ?>">
	<input type="hidden" name="n" id="n" value="<?php echo $n; ?>">
	<?php
	if($status=='UNREAD')
	{ 
		$markAsRead = 'UNREAD';
		?>
		<input type="hidden" name="markAsRead" id="markAsRead" value="<?php echo $markAsRead; ?>">
		<?php
	}
	else
	{ 
		$markAsRead = 'READ';
		?>
		<input type="hidden" name="markAsRead" id="markAsRead" value="<?php echo $markAsRead; ?>">
		<?php
	}	
	?>
    <select name="empid" id="empid" required style="width:260px;border-radius:8px;height:35px; display:none;">

    <?php 
    foreach ($options as $option) 
	{
        echo $option;
    }
    ?>
	
    </select>

<div class="custom-select">
    <div class="selected-option">Select a Friend</div>
    <div class="options-list">
        <?php 
        foreach ($options as $option) 
		{
            //preg_match('/value=\'(.*?)\'.*?data-image=\'(.*?)\'>(.*?)</', $option, $matches);
            preg_match("#value=\'(.*?)\'\s.*?data-image=\'(.*?)\'\s.*?sts=\'(.*?)\'>(.*?)</#", $option, $matches);
            if ($matches) 
			{
				$value = $matches[1];
                $image = $matches[2];
                $sts = $matches[3];
                $name = $matches[4];
                echo "<div class='option' data-value='$value'><img src='$image' class='option-image'>$name $sts</div>";
            }
        }
        ?>
    </div>
</div>

	<br><br>
    <button type="submit" class="btn">Select</button>
    <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
  </form>
  
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>


<script>


////////////////////////////////////////////////////////////////////////////////////////////////////////////

document.addEventListener('DOMContentLoaded', function() 
{
    const customSelect = document.querySelector('.custom-select');
    const selectedOption = customSelect.querySelector('.selected-option');
    const optionsList = customSelect.querySelector('.options-list');
    const originalSelect = document.getElementById('empid');

    selectedOption.addEventListener('click', () => 
	{
        optionsList.classList.toggle('show');
    });

    optionsList.addEventListener('click', (event) => 
	{
        if (event.target.classList.contains('option')) 
		{
            const value = event.target.getAttribute('data-value');
            const text = event.target.textContent.trim();
            const imgSrc = event.target.querySelector('img').src;

            selectedOption.innerHTML = `<img src="${imgSrc}" class="option-image"> ${text}`;
            originalSelect.value = value;
            optionsList.classList.remove('show');
        }
    });

    document.addEventListener('click', (event) => 
	{
        if (!customSelect.contains(event.target)) 
		{
            optionsList.classList.remove('show');
        }
    });
});



const selectElement = document.getElementById('yourSelectElementId');
selectElement.querySelectorAll('option').forEach(option => 
{
    const imageSrc = option.getAttribute('data-image');
    if (imageSrc) 
	{
        option.innerHTML = `<img src='${imageSrc}' style='width:30px;height:30px;border-radius:50%;'> ${option.textContent}`;
    }
});



  let senderid = '<?php echo $eid; ?>';

  function openList() 
  {
    document.getElementById("myFriends").style.display = "block";
  }

  function closeForm() 
  {
    document.getElementById("myFriends").style.display = "none";
  }



    document.addEventListener('click', function (event) 
	{
		const chatPopup = document.getElementById('myFriends');
		if (!chatPopup.contains(event.target) && chatPopup.style.display === 'block') 
		{
		  chatPopup.style.display = 'none';
		}
    });
  
  function showChatPopup() 
  {
    const chatPopup = document.getElementById('myFriends');
    chatPopup.style.display = 'block';
  }
  
  
   
</script>	

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<?php


$names = array();
foreach ($_SESSION['senderIds'] as $senderid) 
{

    if ($senderid == '1000') { $names[] = 'Sanoop'; }
    if ($senderid == '1004') { $names[] = 'Sarath'; }
    if ($senderid == '1001') { $names[] = 'Anurag'; }
    if ($senderid == '1002') { $names[] = 'Anna'; }
    if ($senderid == '1003') { $names[] = 'Rithik'; }
    if ($senderid == '1044') { $names[] = 'Jinoy'; }
    if ($senderid == '1068') { $names[] = 'Anandhu'; }
    if ($senderid == '1091') { $names[] = 'Akhil'; }
    if ($senderid == '1070') { $names[] = 'Manju'; }
    if ($senderid == '1071') { $names[] = 'Vyshnav'; }
    if ($senderid == '999') { $names[] = 'Mefs'; }
    if ($senderid == '1108') { $names[] = 'Rajagopal'; }
}


$names = array_unique($names);

if (count($names) > 1) 
{
    $displayNames = $names;
    $last_name = array_pop($displayNames);
    $nameList = implode(', ', $displayNames) . " and " . $last_name;
} 
else 
{
    $nameList = implode('', $names);
}
?>

<div id="notification" class="notification">
    You have a new message from <?php echo $nameList; ?>!
</div>

<?php
if (count($names) == 1)
{
?>
	<div id="notificationPopup" style="display: none; position: fixed; bottom: 80px; right: 28px; background: linear-gradient(135deg, #4caf50, #2e7d32); color:#ffffff; font-weight: bold; padding: 15px; border-radius: 8px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); z-index: 1000;">
	You have an unread message from <span id="nameList"></span>
	</div>

<?php
}
elseif(count($names) >= 2)
{
?>
	<div id="notificationPopup" style="display: none; position: fixed; bottom: 80px; right: 28px; background: linear-gradient(135deg, #4caf50, #2e7d32); color:#ffffff; font-weight: bold; padding: 15px; border-radius: 8px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); z-index: 1000;">
	You have unread messages from <span id="nameList"></span>
	</div>	
<?php
}	


if ($status == 'UNREAD') 
{ 
echo "<script>
        // Initial delay of 15 seconds
        setTimeout(function() 
		{
            showNotification('$nameList');

            // After the first display, set an interval of 30 seconds
            setInterval(function() 
			{
                showNotification('$nameList');
            }, 30000); // 30 seconds interval

        }, 15000); // Initial 15 seconds delay
      </script>";
?>	  
	  
    <script>
        const notification = document.getElementById("notification");
        notification.style.transform = "translateX(100%)";
        notification.style.opacity = "0";
        notification.style.display = "block";

        let opacity = 0;
        let position = 100;
        const slideIn = setInterval(() => 
		{
            if (position <= 0 && opacity >= 1) 
			{
                clearInterval(slideIn);

                setTimeout(() => 
				{
                    const fadeOut = setInterval(() => 
					{
                        if (opacity <= 0) 
						{
                            clearInterval(fadeOut);
                            notification.style.display = "none";
                        } 
						else 
						{
                            opacity -= 0.03;
                            notification.style.opacity = opacity;
                        }
                    }, 75);
                }, 1500);
            } 
			else 
			{
                position -= 2;
                opacity += 0.03;
                notification.style.transform = `translateX(${position}%)`;
                notification.style.opacity = opacity;
            }
        }, 20);
		

		function showNotification(nameList) 
		{
			const popup = document.getElementById('notificationPopup');
			const nameListElement = document.getElementById('nameList');

			nameListElement.textContent = nameList;

			popup.style.display = 'block';
			popup.style.opacity = 1; 

			setTimeout(function()
			{
				let opacity = 1;
				const fadeOut = setInterval(function() 
				{
					if (opacity <= 0) 
					{
						clearInterval(fadeOut);
						popup.style.display = 'none'; 
					} 
					else 
					{
						opacity -= 0.05; 
						popup.style.opacity = opacity;
					}
				}, 50);
			}, 5000); 
		}		
		
    </script>
<?php 
} 	

	
	
	
	
	
	
	
	
	
	
	
	
}

}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

elseif ($emptype == 'EMPLOYEE' && $flag == 'Y') //EMPLOYEE MAIN PAGE
{


include "header2.html";
?>

<style>

        .sidebar1 
		{
            position: fixed;
            top: 47px; 
            left: 0;
            height: calc(100% - 47px); 
            width: 85px;
            display: flex;
            overflow-x: hidden;
            flex-direction: column;
            background: #28373E;
            padding: 25px 20px;
            transition: all 0.4s ease;
            z-index: 999;
        }

        .sidebar1:hover 
		{
            width: 260px;
        }

        .sidebar1 .sidebar1-header 
		{
            display: flex;
            align-items: center;
        }

        .sidebar1 .sidebar1-header img 
		{
            width: 42px;
            border-radius: 50%;
        }

        .sidebar1 .sidebar1-header h2 
		{
            color: white;
            font-size: 1rem;
            
            white-space: nowrap;
            margin-left: 23px;
        }

        .sidebar1-links 
		{
            list-style: none;
            margin-top: 20px;
            height: 80%;
            overflow-y: auto;
            scrollbar-width: none;
        }

        .sidebar1-links::-webkit-scrollbar 
		{
            display: none;
        }

        .sidebar1-links h4
		{
            color: white;
            white-space: nowrap;
            margin: 10px 0;
            position: relative;
        }

        .sidebar1-links h4 span 
		{
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .sidebar1:hover .sidebar1-links h4 span 
		{
            opacity: 1;
        }

        .sidebar1-links .menu-separator 
		{
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            transform: translateY(-50%);
            background: #4f52ba;
            transform-origin: right;
            transition: transform 0.4s ease;
        }

        .sidebar1:hover .sidebar1-links .menu-separator 
		{
            transform: scaleX(0);
        }

        .sidebar1-links li a 
		{
            display: flex;
            align-items: center;
            gap: 0 20px;
            color: white;
            
            white-space: nowrap;
            padding: 15px 10px;
            text-decoration: none;
            transition: color 0.2s ease, background 0.2s ease;
            cursor: pointer;
        }

        .sidebar1-links li a.active 
		{
            color: #161a2d;
            background: #ffc815;
            border-radius: 4px;
        }
		
		.sidebar1-links li a:hover
		{
		    color: white;
            background:  #595959;
            border-radius: 4px;
		}

</style>


<?php
date_default_timezone_set("Asia/Kolkata");

if(date("h:i:sa") > '05:50:00' && date("h:i:sa") < '05:58:00')
{
?>


	<script type="text/javascript">
    
	var idleTime = 0;
    var idleInterval = setInterval(timerIncrement, 60000); 
    window.onmousemove = resetTimer;
    window.onkeypress = resetTimer;
    window.onclick = resetTimer;
    window.onscroll = resetTimer;

    function timerIncrement() 
	{
        idleTime++;
        if (idleTime >= 10)// 10 minutes
		{ 
            window.location.href = 'login.php';
        }
    }

    function resetTimer() 
	{
        idleTime = 0;
    }
	
</script>


<center>

<div class="header">
    <button class="back-button" onclick="goBack()">
      <img src="../leave/images/back-button.png" alt="Back">
    </button>
    <b style="font-size:20px;align-items:center;font-weight:bold;">MIDDLE EAST FINANCIAL SOFTWARE SOLUTIONS &nbsp; <img src="../leave/images/MEFS.png" alt="Mefs Logo" / class="logo"></b>
	<a href="logout.php" class="logout-button">Logout</a>
</div>
<aside class="sidebar1" id="sidebar1">
    <div class="sidebar1-header">


<script>
localStorage.setItem('profileImagePath', '<?php echo $imagePath; ?>');
</script>		
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
    '1119' => 'test2.png'
];

if ($empid == '1044') 
{
    $defaultImage = 'jinoy.png';
} 
elseif ($empid == '1068') 
{
    $defaultImage = 'anandhu.png';
} 
elseif ($empid == '1001') 
{
    $defaultImage = 'anurag.png';
} 
elseif ($empid == '1071') 
{
    $defaultImage = 'vyshnav.png';
} 
elseif ($empid == '1003') 
{
    $defaultImage = 'rithik.png';
} 
elseif ($empid == '1004') 
{
    $defaultImage = 'sarath.png';
} 
elseif ($empid == '1000') 
{
    $defaultImage = 'sanoop.png';
} 
elseif ($empid == '1091') 
{
    $defaultImage = 'akhil.png';
} 
elseif ($empid == '1002') 
{
    $defaultImage = 'anna.png';
} 
elseif ($empid == '1070') 
{
    $defaultImage = 'manju.png';
} 
elseif ($empid == '1118') 
{
    $defaultImage = 'test1.png';
}
elseif ($empid == '1119') 
{
    $defaultImage = 'test2.png';
}
else 
{
    $defaultImage = ($gender == 'MALE') ? 'male1.png' : 'female1.png';//style="width:55px;height:55px;"
}

$imageFile = array_key_exists($empid, $profileImages) ? $profileImages[$empid] : $defaultImage;
$imagePath = "../leave/dp/$imageFile"; 
 
?>
<?php

$imagePath = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : "../leave/dp/$defaultImage";
?>
<img id="profileImage" src="<?php echo $imagePath . '?' . time(); ?>" style="width:55px;height:55px;border-radius:50px;"><br>

<script>
const updatedImagePath = localStorage.getItem('profileImagePath');
if (updatedImagePath) 
{
    document.getElementById('profileImage').src = updatedImagePath + '?' + new Date().getTime();
    localStorage.removeItem('profileImagePath');
}
</script>

	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style="color:white;"><?php echo $name; ?></b>
    </div>
    <ul class="sidebar1-links">
        <h4>
            <span>Main Menu</span>
            <div class="menu-separator"></div>
        </h4>
        <li>
            <a href="dashboard.php" class="active"  title="<?php echo 'Access denied since maintenance is running';?>">
				<span class="material-symbols-outlined">DASHBOARD</span>Dashboard
			</a>
		</li>
		
        <li>
            <a href="viewprofile.php" class="disabled" onclick="return false;">
                <span class="material-symbols-outlined">account_circle</span>My Profile 
            </a>
        </li>
		
        <li>
            <a href="leavereport.php" class="disabled" onclick="return false;">
                <span class="material-symbols-outlined">overview</span>Leave Report
            </a>
        </li>
		
        <li>
            <a href="leaverequest.php" class="disabled" onclick="return false;">
                <span class="material-symbols-outlined">event_note</span>Request for Leave
            </a>
        </li>
		
		<li>
			<a href="leaverankings.php" class="disabled" onclick="return false;">
				<span class="material-symbols-outlined">leaderboard</span>Leave Rankings
			</a>
		</li>
		
        <h4>
            <span>Account</span>
            <div class="menu-separator"></div>
        </h4>

        <li>
            <a href="changepassword.php" class="disabled" onclick="return false;">
			<span class="material-symbols-outlined">Password</span>Passwords
			</a>
        </li>

        <li>
            <a href="logout.php" class="logout" onclick="logoutAndRedirect(event)"><span class="material-symbols-outlined"> logout </span>Logout</a>
        </li>
		
    </ul>
</aside>


<div class="content" id="content">
    <iframe id="contentFrame" src="dashboard.php" title="Learning Iframe"></iframe>
</div>

<script>
    function loadContent(url) 
	{
        document.getElementById('contentFrame').src = url;
    }

    const sidebar = document.getElementById('sidebar1');
    const content = document.getElementById('content');

    sidebar.addEventListener('mouseover', () => 
	{
        content.style.marginLeft = '260px';
    });

    sidebar.addEventListener('mouseout', () => 
	{
        content.style.marginLeft = '85px';
    });

    const sidebarLinks = document.querySelectorAll('.sidebar1-links li a');
    sidebarLinks.forEach(link => 
	{
		
        link.addEventListener('click', function(e) 
		{
            e.preventDefault();
            sidebarLinks.forEach(link => link.classList.remove('active'));
            this.classList.add('active');
            const href = this.getAttribute('href');
            loadContent(href); 
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
}



else //EMPLOYEE MAIN PAGE
{

$totalLeaveBalance1='';

?>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap">
<body class="report" bottommargin="0" topmargin="0" leftmargin="0" rightmargin="0" style="background-color: #edb984;caret-color:transparent;">	

<script type="text/javascript">
    
	var idleTime = 0;

    // Increment the idle time counter every minute.
    var idleInterval = setInterval(timerIncrement, 60000); // 1 minute

    // Zero the idle timer on mouse movement, keypress, etc.
    window.onmousemove = resetTimer;
    window.onkeypress = resetTimer;
    window.onclick = resetTimer;
    window.onscroll = resetTimer;

    function timerIncrement() 
	{
        idleTime++;
        if (idleTime >= 10)			
		{
            window.location.href = 'login.php';
        }
    }

    function resetTimer() 
	{
        idleTime = 0;
    }
	
</script>


<style>

/* .drop-icon 
{
    position: relative;
    animation: drop 0.6s ease-out;
} */

@keyframes drop 
{
    0% { transform: translateY(-20px); opacity: 0; }
    60% { transform: translateY(5px); opacity: 1; }
    80% { transform: translateY(-2px); }
    100% { transform: translateY(0); }
}
	
	
.dropdown-content1
{
	display: none;
    position: absolute;
    top: 50px;
    margin-left: 200px;
    background-color: #ffffff;
    min-width: 250px;
    border: 1px solid #ccc;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    border-radius: 8px;
    z-index: 1;
}

.dropdown-content2
{
	display: none;
    position: absolute;
    top: 50px;
	height:50px;
    margin-left: 200px;
    background-color: #ffffff;
    min-width: 250px;
    border: 1px solid #ccc;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    border-radius: 8px;
    z-index: 1;
}

.message-item
{
    padding: 10px;
    border-bottom: 1px solid #eee;
}

.message-item:last-child 
{
    border-bottom: none;
}

.message-item strong 
{
    color: #333;
}

.message-item .time 
{
    font-size: 12px;
    color: #888;
}
	
</style>


<center>

<div class="header">
    <button class="back-button" onclick="goBack()">
      <img src="../leave/images/back-button.png" alt="Back">
    </button>
    <b style="font-size:20px;align-items:center;margin-left:460px;font-weight:bold;">MIDDLE EAST FINANCIAL SOFTWARE SOLUTIONS &nbsp; <img src="../leave/images/MEFS.png" alt="Mefs Logo" / class="logo"></b>
	
	
<?php


$names = array();
foreach ($_SESSION['senderIds'] as $senderid) 
{

    if ($senderid == '1000') { $names[] = 'Sanoop'; }
    if ($senderid == '1004') { $names[] = 'Sarath'; }
    if ($senderid == '1001') { $names[] = 'Anurag'; }
    if ($senderid == '1002') { $names[] = 'Anna'; }
    if ($senderid == '1003') { $names[] = 'Rithik'; }
    if ($senderid == '1044') { $names[] = 'Jinoy'; }
    if ($senderid == '1068') { $names[] = 'Anandhu'; }
    if ($senderid == '1091') { $names[] = 'Akhil'; }
    if ($senderid == '1070') { $names[] = 'Manju'; }
    if ($senderid == '1071') { $names[] = 'Vyshnav'; }
    if ($senderid == '1118') { $names[] = 'Test1'; }
    if ($senderid == '1119') { $names[] = 'Test2'; }
    if ($senderid == '999') { $names[] = 'MEFS'; }
    if ($senderid == '1108') { $names[] = 'MEFS'; }
}


$names = array_unique($names);

$times = array();
$timedelayList = array(); 

if (isset($_SESSION['timestampIds'])) 
{
    $uniqueTimestamps = array_unique($_SESSION['timestampIds']);
    
    foreach ($uniqueTimestamps as $timestamp) 
	{

        $times[] = $timestamp;

        $receivedTime = !empty($timestamp) ? new DateTime($timestamp) : new DateTime();
        $currentTime = new DateTime();
        $interval = $currentTime->diff($receivedTime);

		if ($interval->m > 1) 
		{
			$timedelay = $interval->m . " months ago";
		} 
		elseif ($interval->m > 0) 
		{
			$timedelay = $interval->m . " month ago";
		} 
        elseif ($interval->d > 1) 
		{
            $timedelay = $interval->d . " days ago";
        } 
		elseif ($interval->d > 0) 
		{
            $timedelay = $interval->d . " day ago";
        } 
		elseif ($interval->h > 1) 
		{
            $timedelay = $interval->h . " hours ago";
        } 
		elseif ($interval->h > 0) 
		{
            $timedelay = $interval->h . " hour ago";
        } 
		elseif ($interval->i > 1) 
		{
            $timedelay = $interval->i . " minutes ago";
        } 
		else 
		{
            $timedelay = "Just now";
        }

        $timedelayList[] = $timedelay;
    }
}

$newMessages = array();

if (count($names) > 1) 
{
	foreach ($names as $index => $nameList) 
	{
		$time = isset($timedelayList[$index]) ? $timedelayList[$index] : "Just now"; 
		$currentMessage = isset($message[$index]) ? $message[$index] : 'No new messages';
		
		if (strlen($currentMessage) > 23) 
		{
			$truncatedMessage = substr($currentMessage, 0, 23);
			$lastSpace = strrpos($truncatedMessage, ' '); // Find last space in the truncated message
			$currentMessage = $lastSpace !== false ? substr($truncatedMessage, 0, $lastSpace) : $truncatedMessage;
			$currentMessage = rtrim($currentMessage) . '...';
		}
		
		$newMessages[] = 
		[
			'name' => $nameList,
			'message' => $currentMessage,
			'time' => $time
		];
	}
} 
else 
{
	foreach ($names as $index => $nameList) 
	{
		$time = isset($timedelayList[$index]) ? $timedelayList[$index] : "Just now";
		$nameList = implode('', $names); 		
		$currentMessage = isset($message[$index]) ? $message[$index] : 'No new messages';
		if (strlen($currentMessage) > 23) 
		{
			$truncatedMessage = substr($currentMessage, 0, 23);
			$lastSpace = strrpos($truncatedMessage, ' '); // Find last space in the truncated message
			$currentMessage = $lastSpace !== false ? substr($truncatedMessage, 0, $lastSpace) : $truncatedMessage;
			$currentMessage = rtrim($currentMessage) . '...';
		}

		$newMessages[] = 
		[
			'name' => $nameList,
			'message' => $currentMessage,
			'time' => $time
		];
	}
}





$status1 = ($status == 'UNREAD' && count($newMessages) > 0);
?>	
<div style="position: relative;">
 
   <?php 
    if ($status1 && count($names) == 1) 
	{ 
	$status1 = 'UNREAD';
	?>
        <img src='../leave/images/notification.png' class='drop-icon' id="notificationIcon" style='width:35px;height:35px;margin-left:370px; cursor: pointer;position: relative; animation: drop 0.6s ease-out;'>
    <?php 
	}     
	elseif ($status1 && count($names) == 2) 
	{ 
	$status1 = 'UNREAD';
	?>
        <img src='../leave/images/notification2.png' class='drop-icon' id="notificationIcon" style='width:35px;height:35px;margin-left:370px; cursor: pointer;position: relative; animation: drop 0.6s ease-out;'>
    <?php 
	}  
	elseif ($status1 && count($names) == 3) 
	{ 
	$status1 = 'UNREAD';
	?>
        <img src='../leave/images/notification3.png' class='drop-icon' id="notificationIcon" style='width:35px;height:35px;margin-left:370px; cursor: pointer;position: relative; animation: drop 0.6s ease-out;'>
    <?php 
	}	
    elseif ($status1) 
	{ 
	$status1 = 'UNREAD';
	?>
        <img src='../leave/images/notification3plus.png' class='drop-icon' id="notificationIcon" style='width:35px;height:35px;margin-left:370px; cursor: pointer;position: relative; animation: drop 0.6s ease-out;'>
    <?php 
	} 
 	
	else 
	{
	$status1 = 'READ';		
	?>
        <img src='../leave/images/bellicon.png' class='drop-icon' id="notificationIcon" style='width:35px;height:35px;margin-left:370px; cursor: pointer;position: relative;animation: drop 0.6s ease-out;' title="You have no new messages">
    <?php 
	} 
	
	
	if ($status1 == 'UNREAD') 
	{ 
    ?>
		<div id="messageDropdown1" class="dropdown-content1">
			<?php 
			$newMessages = array_reverse($newMessages); 
			foreach ($newMessages as $msg) 
			{ 
			?>
				<div class="message-item">
				    <img align=left src="../leave/dp/<?php echo strtolower($msg['name']); ?>.png" alt="Profile Picture" style="width:35px; height:35px; border-radius:50%; vertical-align: middle; margin-right: 8px;">
					<strong style='margin-right:20px;'><?php echo $msg['name']; ?></strong><br>
					<span style='margin-left:-30px;'><?php echo $msg['message']; ?></span>
					<div style='margin-left:22px;' class="time"><?php echo $msg['time']; ?></div>
				</div>
			<?php 
			} 
			?>
		</div>
    <?php 
	}
	elseif(count($names) < 1)
	{
    ?>
        <div id="messageDropdown2" class="dropdown-content2">
            <?php foreach ($newMessages as $msg) 
			{ 
			?>
            <?php 
			} 
			?>
        </div>
    <?php 		
	}
	else
	{
		
	}
	?>
</div>
	<a href="logout.php" class="logout-button">Logout</a>
</div>
<aside class="sidebar1" id="sidebar1">
    <div class="sidebar1-header">


<script>


document.getElementById("notificationIcon").addEventListener("click", function () 
{
	const dropdown = document.getElementById("messageDropdown1");
    dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
});



document.getElementById("notificationIcon").addEventListener("click", function () 
{
    const dropdown = document.getElementById("messageDropdown2");

    if (dropdown.children.length === 0) 
	{
        dropdown.innerHTML = "<strong style='margin-top:10px;display:block;'>No new messages!</strong>";
    }

    if (dropdown.style.display === "block") 
	{
        dropdown.style.display = "none";
        dropdown.style.opacity = 0;
    } 
	else 
	{
        dropdown.style.display = "block";
        dropdown.style.opacity = 1;

        setTimeout(function () 
		{
            let fadeEffect = setInterval(function () 
			{
                if (dropdown.style.opacity > 0) 
				{
                    dropdown.style.opacity -= 0.05;
                } 
				else 
				{
                    clearInterval(fadeEffect);
                    dropdown.style.display = "none";
                }
            }, 50);
        }, 2000);
    }
});





</script>	


<script>
localStorage.setItem('profileImagePath', '<?php echo $imagePath; ?>');
</script>	


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
    '999' => 'mefs.png',
    '1108' => 'mefs.png'
];

if ($empid == '1044') 
{
    $defaultImage = 'jinoy.png';
} 
elseif ($empid == '1068') 
{
    $defaultImage = 'anandhu.png';
} 
elseif ($empid == '1001') 
{
    $defaultImage = 'anurag.png';
} 
elseif ($empid == '1071') 
{
    $defaultImage = 'vyshnav.png';
} 
elseif ($empid == '1003') 
{
    $defaultImage = 'rithik.png';
} 
elseif ($empid == '1004') 
{
    $defaultImage = 'sarath.png';
} 
elseif ($empid == '1000') 
{
    $defaultImage = 'sanoop.png';
} 
elseif ($empid == '1091') 
{
    $defaultImage = 'akhil.png';
} 
elseif ($empid == '1002') 
{
    $defaultImage = 'anna.png';
} 
elseif ($empid == '1070') 
{
    $defaultImage = 'manju.png';
} 
elseif ($empid == '1118') 
{
    $defaultImage = 'test1.png';
}
elseif ($empid == '1119') 
{
    $defaultImage = 'test2.png';
}
else 
{
    $defaultImage = ($gender == 'MALE') ? 'male1.png' : 'female1.png';//style="width:55px;height:55px;"
}
  
$imageFile = array_key_exists($empid, $profileImages) ? $profileImages[$empid] : $defaultImage;
$imagePath = "../leave/dp/$imageFile"; 
 
?>
<?php

$imagePath = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : "../leave/dp/$defaultImage";
?>

<img id="profileImage" src="<?php echo $imagePath . '?' . time(); ?>" style="width:55px;height:55px;border-radius:50px;"><br>

<script>
const updatedImagePath = localStorage.getItem('profileImagePath');
if (updatedImagePath) 
{
    document.getElementById('profileImage').src = updatedImagePath + '?' + new Date().getTime();
    localStorage.removeItem('profileImagePath');
}
</script>

<style>
.n
{
 opacity:0; 
 transition:opacity 0.0s ease-out;	
}
.sidebar1:hover .n
{
    opacity: 1;
	transition:opacity 0.5s ease-in;
}

@media screen and (max-width: 768px) 
{
    #sidebar1
	{
        width: 200px;
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

	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style="color:white;" class="n"><?php echo $name; ?></b>
    </div>
    <ul class="sidebar1-links">
	
        <h4>
            <span>Main Menu</span>
            <div class="menu-separator"></div>
        </h4>
		
        <li>
            <a href="dashboard.php" class="active">
				<span class="material-symbols-outlined">DASHBOARD</span>Dashboard
			</a>
		</li>
		
		<li>
			<a href="viewprofile.php">
				<span class="material-symbols-outlined">account_circle</span>My Profile 
			</a>
        </li>

		<li>
			<a href="leavereport.php">
			  <span class="material-symbols-outlined">assignment</span>Leave Report
			</a>	
        </li>

		<li>
			<a href="leaverequest.php">		
			  <span class="material-symbols-outlined">event</span>Request for Leave
			</a>		
        </li>	
 
		<li>		
			<a href="leaverankings.php">
				<span class="material-symbols-outlined">leaderboard</span>Leave Rankings
			</a>			
		</li>
 
        <h4>
            <span>Account</span>
            <div class="menu-separator"></div>
        </h4>

        <li>
            <a href="changepassword.php">
			<span class="material-symbols-outlined">Password</span>Passwords
			</a>
        </li>

        <li>
            <a href="logout.php" class="logout" onclick="logoutAndRedirect(event)"><span class="material-symbols-outlined"> logout </span>Logout</a>
        </li>
		
    </ul>
</aside>


<div class="content" id="content">
    <iframe id="contentFrame" src="dashboard.php" title="Learning Iframe"></iframe>
</div>

<script>

    function loadContent(url) 
	{
        document.getElementById('contentFrame').src = url;
    }

    const sidebar = document.getElementById('sidebar1');
    const content = document.getElementById('content');

    sidebar.addEventListener('mouseover', () => 
	{
        content.style.marginLeft = '260px';
    });

    sidebar.addEventListener('mouseout', () => 
	{
        content.style.marginLeft = '85px';
    });

    const sidebarLinks = document.querySelectorAll('.sidebar1-links li a');
    sidebarLinks.forEach(link => 
	{
		
        link.addEventListener('click', function(e) 
		{
            e.preventDefault();
            sidebarLinks.forEach(link => link.classList.remove('active'));
            this.classList.add('active');
            const href = this.getAttribute('href');
            loadContent(href);
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



<br>	
<center>
<?php

$color = "color:red;";


?>
</center>

<center>
&nbsp;&nbsp;
&nbsp;&nbsp;
&nbsp;&nbsp;

</div>

</div>

</center>

<?php
	

$eid='';

	}

//////////////////////////////////////////////////////////////// CHAT
if($empid=='1118' || $empid=='1119')
{
	
}
else
{
?>

<style>	
select 
{
    width: 200px;
    padding: 5px;
}

.select-option img 
{
    width: 30px;
    height: 30px;
    border-radius: 50%;
    vertical-align: middle;
    margin-right: 5px;
}

.custom-select 
{
    width: 250px;
    position: relative;
    cursor: pointer;
    border-radius: 8px;
    border: 1px solid #ccc;
    padding: 5px;
}

.selected-option 
{
    display: flex;
    align-items: center;
    height: 30px;
	font-size:13px;
	font-family:poppins;
    padding-left: 10px;
}

.option-image 
{
    width: 30px;
    height: 30px;
    border-radius: 50%;
    margin-right: 10px;
}

.options-list 
{
    display: none;
    position: absolute;
    width: 100%;
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 8px;
    max-height: 280px;
    overflow-y: auto;
    z-index: 100;
}

.options-list.show 
{
    display: block;
	font-size:12px;
	bottom:100%;
}

.option 
{
    display: flex;
    align-items: center;
	font-size:13px;
    padding: 5px 10px;
    cursor: pointer;
}

.option:hover 
{
    background-color:#E6AA2E;
}

        #notification 
		{
			position: fixed;
			top: 75px;
			right: 20px;
			padding: 15px 20px;
			width: 300px;
			background: linear-gradient(135deg, #4caf50, #2e7d32);
			color: #ffffff;
			font-size: 16px;
			font-weight: bold;
			border-radius: 8px;
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
			opacity: 0;
			transition: opacity 0.3s ease-in-out;
			z-index:-2;
        }
		
		
        .new-message 
		{
            background-color: #28a745;
            color: white;
			width:45px;
            font-size: 12px;
            padding: 4px 8px;
            border-radius: 12px;
            margin-left: 20px;
            margin-right: 0;
            text-align: center;
            white-space: nowrap;
        }	
		
		svg 
		{
		  animation: fade 2s infinite;
		}

		@keyframes fade 
		{
		  0% { opacity: 1; }
		  50% { opacity: 0.5; }
		  100% { opacity: 1; }
		}

</style>

<?php	
$eid=$empid;	

$n = $name;

$senderdot = '';

$dot = '';


$dot = array();
foreach ($_SESSION['senderIds'] as $senderid) 
{

    if ($senderid == '1000') { $dot[] = '.'; }
    if ($senderid == '1004') { $dot[] = '.'; }
    if ($senderid == '1001') { $dot[] = '.'; }
    if ($senderid == '1002') { $dot[] = '.'; }
    if ($senderid == '1003') { $dot[] = '.'; }
    if ($senderid == '1044') { $dot[] = '.'; }
    if ($senderid == '1068') { $dot[] = '.'; }
    if ($senderid == '1091') { $dot[] = '.'; }
    if ($senderid == '1070') { $dot[] = '.'; }
    if ($senderid == '1071') { $dot[] = '.'; }
    if ($senderid == '999') { $dot[] = '.'; }
    if ($senderid == '1108') { $dot[] = '.'; }
	
}

			
			$sqlstatus = "SELECT senderid FROM msg WHERE receiverid = '$empid' AND status = 'UNREAD' order by timestamp";
			$resstatus = sqlsrv_query($conn, $sqlstatus);
			$senderdot = [];
			while ($row10 = sqlsrv_fetch_array($resstatus, SQLSRV_FETCH_ASSOC)) 
			{
				$currentSender = isset($row10['senderid']) ? trim($row10['senderid']) : '';
				if($currentSender) 
				{
					$senderdot[] = $currentSender;
				}
				
			}	
			

    $options = [];////////////////////
    $options[] = "<option value='group' data-image='../leave/dp/group.png' sts=''>GROUP CHAT</option>";
	
	$sql8 = "SELECT name,empid FROM emp WITH (NOLOCK) WHERE flag='Y' AND empid!='1108' AND empid!='$empid' AND empid!='1118' AND empid!='1119' ORDER BY name";
	$res8 = sqlsrv_query($conn, $sql8);
	if ($res8) 
	{
		
        while ($row8 = sqlsrv_fetch_array($res8, SQLSRV_FETCH_ASSOC)) 
		{
			$empid1 = isset($row8['empid']) ? trim($row8['empid']) : '';
			$name1 = isset($row8['name']) ? trim($row8['name']) : '';
			
			$desiredLength = 22;
			$currentLength = strlen($name1);

			if ($currentLength < $desiredLength) 
			{
				$spacesNeeded = $desiredLength - $currentLength;
				$name1 .= str_repeat("&nbsp;", $spacesNeeded);
			}
			
			
			$dot = '';
			$sts = '';
			
			if ($empid1 == '1044') 
			{
				$defaultImage = 'jinoy.png';
			} 
			elseif ($empid1 == '1068') 
			{
				$defaultImage = 'anandhu.png';
				$name1 = 'ANANDHU';
			} 
			elseif ($empid1 == '1001') 
			{
				$defaultImage = 'anurag.png';
			} 
			elseif ($empid1 == '1071') 
			{
				$defaultImage = 'vyshnav.png';
			} 
			elseif ($empid1 == '1003') 
			{
				$defaultImage = 'rithik.png';
			} 
			elseif ($empid1 == '1004') 
			{
				$defaultImage = 'sarath.png';
			} 
			elseif ($empid1 == '1000') 
			{
				$defaultImage = 'sanoop.png';
				$name1 = 'SANOOP';
			} 
			elseif ($empid1 == '1091') 
			{
				$defaultImage = 'akhil.png';
			} 
			elseif ($empid1 == '1002') 
			{
				$defaultImage = 'anna.png';
			} 		
			elseif ($empid1 == '1070') 
			{
				$defaultImage = 'manju.png';
			} 
			elseif ($empid1 == '1118') 
			{
				$defaultImage = 'test1.png';
			} 
			elseif ($empid1 == '1119') 
			{
				$defaultImage = 'test2.png';
			} 			
			elseif ($empid1 == '999')//ARUN 
			{
				$defaultImage = 'mefs.png';
				$name1 = 'MEFS';
			}	
			elseif ($empid1 == '1108')//RAJAGOPAL 
			{
				$defaultImage = 'mefs.png';
				$name1 = 'MEFS';
			}			
			else 
			{
				$defaultImage = ($gender == 'MALE') ? 'male1.png' : 'female1.png';//style="width:55px;height:55px;"
			}

			if(in_array($empid1, $senderdot))
			{
				$dot = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;🟢";
				//$sts = "<svg xmlns='http://www.w3.org/2000/svg' height='10' width='10' viewBox='0 0 512 512'> <path fill='#5eeb00' 
				//d='M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512z'/> </svg>";
				$sts = "<img style = 'height:40px;width:40px;float:right;' src=\"../leave/images/new.png\">";
				//$sts = "<span style=\"color:green;margin-right:auto;text-align: left;;float:right;\">(NEW)</span>";
			}
			
			$options[] = "<option value='$empid1' data-image='../leave/dp/$defaultImage' sts='$sts'>$name1</option>";				

        }
	}

	
?>	
	
<button class="open-button" onclick="openList()">CHAT WITH FRIENDS</button>

<div class="chat-popup" id="myFriends" style="width:290px;">
  
  
 <form action="chat1.php" class="form-container" method="get">
    <h1>Chat</h1>
	<input type="hidden" name="eid" id="eid" value="<?php echo $eid; ?>">
	<input type="hidden" name="n" id="n" value="<?php echo $n; ?>">
	<?php
	if($status=='UNREAD')
	{ 
		$markAsRead = 'UNREAD';
		?>
		<input type="hidden" name="markAsRead" id="markAsRead" value="<?php echo $markAsRead; ?>">
		<?php
	}
	else
	{ 
		$markAsRead = 'READ';
		?>
		<input type="hidden" name="markAsRead" id="markAsRead" value="<?php echo $markAsRead; ?>">
		<?php
	}	
	?>
    <select name="empid" id="empid" required style="width:260px;border-radius:8px;height:35px; display:none;">

    <?php 
    foreach ($options as $option) 
	{
        echo $option;
    }
    ?>
	
    </select>

<div class="custom-select">
    <div class="selected-option">Select a Friend</div>
    <div class="options-list">
        <?php 
        foreach ($options as $option) 
		{
            //preg_match('/value=\'(.*?)\'.*?data-image=\'(.*?)\'>(.*?)</', $option, $matches);
            preg_match("#value=\'(.*?)\'\s.*?data-image=\'(.*?)\'\s.*?sts=\'(.*?)\'>(.*?)</#", $option, $matches);
            if ($matches) 
			{
				$value = $matches[1];
                $image = $matches[2];
                $sts = $matches[3];
                $name = $matches[4];
                echo "<div class='option' data-value='$value'><img src='$image' class='option-image'>$name $sts</div>";
            }
        }
        ?>
    </div>
</div>

	<br><br>
    <button type="submit" class="btn">Select</button>
    <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
  </form>
  
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>


<script>


////////////////////////////////////////////////////////////////////////////////////////////////////////////

document.addEventListener('DOMContentLoaded', function() 
{
    const customSelect = document.querySelector('.custom-select');
    const selectedOption = customSelect.querySelector('.selected-option');
    const optionsList = customSelect.querySelector('.options-list');
    const originalSelect = document.getElementById('empid');

    selectedOption.addEventListener('click', () => 
	{
        optionsList.classList.toggle('show');
    });

    optionsList.addEventListener('click', (event) => 
	{
        if (event.target.classList.contains('option')) 
		{
            const value = event.target.getAttribute('data-value');
            const text = event.target.textContent.trim();
            const imgSrc = event.target.querySelector('img').src;

            selectedOption.innerHTML = `<img src="${imgSrc}" class="option-image"> ${text}`;
            originalSelect.value = value;
            optionsList.classList.remove('show');
        }
    });

    document.addEventListener('click', (event) => 
	{
        if (!customSelect.contains(event.target)) 
		{
            optionsList.classList.remove('show');
        }
    });
});



const selectElement = document.getElementById('yourSelectElementId');
selectElement.querySelectorAll('option').forEach(option => 
{
    const imageSrc = option.getAttribute('data-image');
    if (imageSrc) 
	{
        option.innerHTML = `<img src='${imageSrc}' style='width:30px;height:30px;border-radius:50%;'> ${option.textContent}`;
    }
});



  let senderid = '<?php echo $eid; ?>';

  function openList() 
  {
    document.getElementById("myFriends").style.display = "block";
  }

  function closeForm() 
  {
    document.getElementById("myFriends").style.display = "none";
  }



    document.addEventListener('click', function (event) 
	{
		const chatPopup = document.getElementById('myFriends');
		if (!chatPopup.contains(event.target) && chatPopup.style.display === 'block') 
		{
		  chatPopup.style.display = 'none';
		}
    });
  
  function showChatPopup() 
  {
    const chatPopup = document.getElementById('myFriends');
    chatPopup.style.display = 'block';
  }
  
  
   
</script>	

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<?php


$names = array();
foreach ($_SESSION['senderIds'] as $senderid) 
{

    if ($senderid == '1000') { $names[] = 'Sanoop'; }
    if ($senderid == '1004') { $names[] = 'Sarath'; }
    if ($senderid == '1001') { $names[] = 'Anurag'; }
    if ($senderid == '1002') { $names[] = 'Anna'; }
    if ($senderid == '1003') { $names[] = 'Rithik'; }
    if ($senderid == '1044') { $names[] = 'Jinoy'; }
    if ($senderid == '1068') { $names[] = 'Anandhu'; }
    if ($senderid == '1091') { $names[] = 'Akhil'; }
    if ($senderid == '1070') { $names[] = 'Manju'; }
    if ($senderid == '1071') { $names[] = 'Vyshnav'; }
    if ($senderid == '999') { $names[] = 'MEFS'; }
    if ($senderid == '1108') { $names[] = 'MEFS'; }
}


$names = array_unique($names);

if (count($names) > 1) 
{
    $displayNames = $names;
    $last_name = array_pop($displayNames);
    $nameList = implode(', ', $displayNames) . " and " . $last_name;
} 
else 
{
    $nameList = implode('', $names);
}
?>

<div id="notification" class="notification">
    You have a new message from <?php echo $nameList; ?>!
</div>

<?php
if (count($names) == 1)
{
?>
	<div id="notificationPopup" style="display: none; position: fixed; bottom: 80px; right: 28px; background: linear-gradient(135deg, #4caf50, #2e7d32); color:#ffffff; font-weight: bold; padding: 15px; border-radius: 8px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); z-index: 1000;">
	You have an unread message from <span id="nameList"></span>
	</div>

<?php
}
elseif(count($names) >= 2)
{
?>
	<div id="notificationPopup" style="display: none; position: fixed; bottom: 80px; right: 28px; background: linear-gradient(135deg, #4caf50, #2e7d32); color:#ffffff; font-weight: bold; padding: 15px; border-radius: 8px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); z-index: 1000;">
	You have unread messages from <span id="nameList"></span>
	</div>	
<?php
}	


if ($status == 'UNREAD') 
{ 
echo "<script>
        // Initial delay of 15 seconds
        setTimeout(function() 
		{
            showNotification('$nameList');

            // After the first display, set an interval of 30 seconds
            setInterval(function() 
			{
                showNotification('$nameList');
            }, 30000); // 30 seconds interval

        }, 15000); // Initial 15 seconds delay
      </script>";
?>	  
	  
    <script>
        const notification = document.getElementById("notification");
        notification.style.transform = "translateX(100%)";
        notification.style.opacity = "0";
        notification.style.display = "block";

        let opacity = 0;
        let position = 100;
        const slideIn = setInterval(() => 
		{
            if (position <= 0 && opacity >= 1) 
			{
                clearInterval(slideIn);

                setTimeout(() => 
				{
                    const fadeOut = setInterval(() => 
					{
                        if (opacity <= 0) 
						{
                            clearInterval(fadeOut);
                            notification.style.display = "none";
                        } 
						else 
						{
                            opacity -= 0.03;
                            notification.style.opacity = opacity;
                        }
                    }, 75);
                }, 1500);
            } 
			else 
			{
                position -= 2;
                opacity += 0.03;
                notification.style.transform = `translateX(${position}%)`;
                notification.style.opacity = opacity;
            }
        }, 20);
		

		function showNotification(nameList) 
		{
			const popup = document.getElementById('notificationPopup');
			const nameListElement = document.getElementById('nameList');

			nameListElement.textContent = nameList;

			popup.style.display = 'block';
			popup.style.opacity = 1; 

			setTimeout(function()
			{
				let opacity = 1;
				const fadeOut = setInterval(function() 
				{
					if (opacity <= 0) 
					{
						clearInterval(fadeOut);
						popup.style.display = 'none'; 
					} 
					else 
					{
						opacity -= 0.05; 
						popup.style.opacity = opacity;
					}
				}, 50);
			}, 5000); 
		}		
		
    </script>
<?php 
} 
?>


</body>
<?php	
}
	
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

elseif($emptype == 'TEST' and $flag == 'Y') // TEST EMPLOYEES
{
	
include "header2.html";
?>

<style>

        .sidebar3 
		{
            position: fixed;
            top: 47px; 
            left: 0;
            height: calc(100% - 47px); 
            width: 85px;
            display: flex;
            overflow-x: hidden;
            flex-direction: column;
            background: #162913;
            padding: 25px 20px;
            transition: all 0.4s ease;
            z-index: 999;
        }

        .sidebar3:hover 
		{
            width: 260px;
        }

        .sidebar3 .sidebar3-header 
		{
            display: flex;
            align-items: center;
        }

        .sidebar3 .sidebar3-header img 
		{
            width: 42px;
            border-radius: 50%;
        }

        .sidebar3 .sidebar3-header h2 
		{
            color: white;
            font-size: 1rem;
            
            white-space: nowrap;
            margin-left: 23px;
        }

        .sidebar3-links 
		{
            list-style: none;
            margin-top: 20px;
            height: 80%;
            overflow-y: auto;
            scrollbar-width: none;
        }

        .sidebar3-links::-webkit-scrollbar 
		{
            display: none;
        }

        .sidebar3-links h4
		{
            color: white;
            white-space: nowrap;
            margin: 10px 0;
            position: relative;
        }

        .sidebar3-links h4 span 
		{
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .sidebar3:hover .sidebar3-links h4 span 
		{
            opacity: 1;
        }

        .sidebar3-links .menu-separator 
		{
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            transform: translateY(-50%);
            background: #4f52ba;
            transform-origin: right;
            transition: transform 0.4s ease;
        }

        .sidebar3:hover .sidebar3-links .menu-separator 
		{
            transform: scaleX(0);
        }

        .sidebar3-links li a 
		{
            display: flex;
            align-items: center;
            gap: 0 20px;
            color: white;
            
            white-space: nowrap;
            padding: 15px 10px;
            text-decoration: none;
            transition: color 0.2s ease, background 0.2s ease;
            cursor: pointer;
        }

        .sidebar3-links li a.active 
		{
            color: #161a2d;
            background: #ffc815;
            border-radius: 4px;
        }
		
		.sidebar3-links li a:hover
		{
		    color: white;
            background:  #3e543a;
            border-radius: 4px;
		}

</style>


<?php
date_default_timezone_set("Asia/Kolkata");

if(date("h:i:sa") > '05:50:00' && date("h:i:sa") < '05:58:00')
{
?>


	<script type="text/javascript">
    
	var idleTime = 0;
    var idleInterval = setInterval(timerIncrement, 60000); 
    window.onmousemove = resetTimer;
    window.onkeypress = resetTimer;
    window.onclick = resetTimer;
    window.onscroll = resetTimer;

    function timerIncrement() 
	{
        idleTime++;
        if (idleTime >= 10)// 10 minutes
		{ 
            window.location.href = 'login.php';
        }
    }

    function resetTimer() 
	{
        idleTime = 0;
    }
	
</script>


<center>

<div class="header">
    <button class="back-button" onclick="goBack()">
      <img src="../leave/images/back-button.png" alt="Back">
    </button>
    <b style="font-size:20px;align-items:center;font-weight: bold;">MIDDLE EAST FINANCIAL SOFTWARE SOLUTIONS &nbsp; <img src="../leave/images/MEFS.png" alt="Mefs Logo" / class="logo"></b>
	<a href="logout.php" class="logout-button">Logout</a>
</div>
<aside class="sidebar3" id="sidebar3">
    <div class="sidebar3-header">


<script>
localStorage.setItem('profileImagePath', '<?php echo $imagePath; ?>');
</script>		
<?php

$profileImages = 
[
    '1118' => 'test1.png',
    '1119' => 'test2.png'
];

if ($empid == '1118') 
{
    $defaultImage = 'test1.png';
}
elseif ($empid == '1119') 
{
    $defaultImage = 'test2.png';
}
else 
{
    $defaultImage = ($gender == 'MALE') ? 'male1.png' : 'female1.png';//style="width:55px;height:55px;"
}

$imageFile = array_key_exists($empid, $profileImages) ? $profileImages[$empid] : $defaultImage;
$imagePath = "../leave/dp/$imageFile"; 
 
?>
<?php

$imagePath = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : "../leave/dp/$defaultImage";
?>
<img id="profileImage" src="<?php echo $imagePath . '?' . time(); ?>" style="width:55px;height:55px;border-radius:50px;"><br>

<script>
const updatedImagePath = localStorage.getItem('profileImagePath');
if (updatedImagePath) 
{
    document.getElementById('profileImage').src = updatedImagePath + '?' + new Date().getTime();
    localStorage.removeItem('profileImagePath');
}
</script>

	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style="color:white;"><?php echo $name; ?></b>
    </div>
    <ul class="sidebar3-links">
        <h4>
            <span>Main Menu</span>
            <div class="menu-separator"></div>
        </h4>
        <li>
            <a href="dashboard.php" class="active"  title="<?php echo 'Access denied since maintenance is running';?>">
				<span class="material-symbols-outlined">DASHBOARD</span>Dashboard
			</a>
		</li>
		
        <li>
            <a href="viewprofile.php" class="disabled" onclick="return false;">
                <span class="material-symbols-outlined">account_circle</span>My Profile 
            </a>
        </li>
		
        <li>
            <a href="leavereport.php" class="disabled" onclick="return false;">
                <span class="material-symbols-outlined">overview</span>Leave Report
            </a>
        </li>
		
        <li>
            <a href="leaverequest.php" class="disabled" onclick="return false;">
                <span class="material-symbols-outlined">event_note</span>Request for Leave
            </a>
        </li>

        <h4>
            <span>Account</span>
            <div class="menu-separator"></div>
        </h4>

        <li>
            <a href="changepassword.php" class="disabled" onclick="return false;">
				<span class="material-symbols-outlined">Password</span>Passwords
			</a>
        </li>

        <li>
            <a href="logout.php" class="logout" onclick="logoutAndRedirect(event)"><span class="material-symbols-outlined"> logout </span>Logout</a>
        </li>
    </ul>
</aside>


<div class="content" id="content">
    <iframe id="contentFrame" src="dashboard.php" title="Learning Iframe"></iframe>
</div>

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
	3
        content.style.marginLeft = '85px';
    });

    const sidebarLinks = document.querySelectorAll('.sidebar3-links li a');
    sidebarLinks.forEach(link => 
	{
		
        link.addEventListener('click', function(e) 
		{
            e.preventDefault();
            sidebarLinks.forEach(link => link.classList.remove('active'));
            this.classList.add('active');
            const href = this.getAttribute('href');
            loadContent(href); 
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
}



else //TEST EMPLOYEE MAIN PAGE
{

$totalLeaveBalance1='';

?>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap">
<body class="report" bottommargin="0" topmargin="0" leftmargin="0" rightmargin="0" style="background-color: #edb984;caret-color:transparent;">	

<script type="text/javascript">
    
	var idleTime = 0;

    // Increment the idle time counter every minute.
    var idleInterval = setInterval(timerIncrement, 60000); // 1 minute

    // Zero the idle timer on mouse movement, keypress, etc.
    window.onmousemove = resetTimer;
    window.onkeypress = resetTimer;
    window.onclick = resetTimer;
    window.onscroll = resetTimer;

    function timerIncrement() 
	{
        idleTime++;
        if (idleTime >= 10)			
		{
            window.location.href = 'login.php';
        }
    }

    function resetTimer() 
	{
        idleTime = 0;
    }
	
</script>


<style>

/* .drop-icon 
{
    position: relative;
    animation: drop 0.6s ease-out;
} */

@keyframes drop 
{
    0% { transform: translateY(-20px); opacity: 0; }
    60% { transform: translateY(5px); opacity: 1; }
    80% { transform: translateY(-2px); }
    100% { transform: translateY(0); }
}
	
	
.dropdown-content1
{
	display: none;
    position: absolute;
    top: 50px;
    margin-left: 200px;
    background-color: #ffffff;
    min-width: 250px;
    border: 1px solid #ccc;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    border-radius: 8px;
    z-index: 1;
}

.dropdown-content2
{
	display: none;
    position: absolute;
    top: 50px;
	height:50px;
    margin-left: 200px;
    background-color: #ffffff;
    min-width: 250px;
    border: 1px solid #ccc;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    border-radius: 8px;
    z-index: 1;
}

.message-item
{
    padding: 10px;
    border-bottom: 1px solid #eee;
}

.message-item:last-child 
{
    border-bottom: none;
}

.message-item strong 
{
    color: #333;
}

.message-item .time 
{
    font-size: 12px;
    color: #888;
}
	
</style>


<center>

<div class="header">
    <button class="back-button" onclick="goBack()">
      <img src="../leave/images/back-button.png" alt="Back">
    </button>
    <b style="font-size:20px;align-items:center;margin-left:460px;font-weight: bold;">MIDDLE EAST FINANCIAL SOFTWARE SOLUTIONS &nbsp; <img src="../leave/images/MEFS.png" alt="Mefs Logo" / class="logo"></b>
	
	
<?php


$names = array();
foreach ($_SESSION['senderIds'] as $senderid) 
{

    if ($senderid == '1000') { $names[] = 'Sanoop'; }
    if ($senderid == '1004') { $names[] = 'Sarath'; }
    if ($senderid == '1001') { $names[] = 'Anurag'; }
    if ($senderid == '1002') { $names[] = 'Anna'; }
    if ($senderid == '1003') { $names[] = 'Rithik'; }
    if ($senderid == '1044') { $names[] = 'Jinoy'; }
    if ($senderid == '1068') { $names[] = 'Anandhu'; }
    if ($senderid == '1091') { $names[] = 'Akhil'; }
    if ($senderid == '1070') { $names[] = 'Manju'; }
    if ($senderid == '1071') { $names[] = 'Vyshnav'; }
    if ($senderid == '1118') { $names[] = 'Test1'; }
    if ($senderid == '1119') { $names[] = 'Test2'; }
    if ($senderid == '999') { $names[] = 'MEFS'; }
    if ($senderid == '1108') { $names[] = 'MEFS'; }
}


$names = array_unique($names);

$times = array();
$timedelayList = array(); 

if (isset($_SESSION['timestampIds'])) 
{
    $uniqueTimestamps = array_unique($_SESSION['timestampIds']);
    
    foreach ($uniqueTimestamps as $timestamp) 
	{

        $times[] = $timestamp;

        $receivedTime = !empty($timestamp) ? new DateTime($timestamp) : new DateTime();
        $currentTime = new DateTime();
        $interval = $currentTime->diff($receivedTime);

		if ($interval->m > 1) 
		{
			$timedelay = $interval->m . " months ago";
		} 
		elseif ($interval->m > 0) 
		{
			$timedelay = $interval->m . " month ago";
		} 
        elseif ($interval->d > 1) 
		{
            $timedelay = $interval->d . " days ago";
        } 
		elseif ($interval->d > 0) 
		{
            $timedelay = $interval->d . " day ago";
        } 
		elseif ($interval->h > 1) 
		{
            $timedelay = $interval->h . " hours ago";
        } 
		elseif ($interval->h > 0) 
		{
            $timedelay = $interval->h . " hour ago";
        } 
		elseif ($interval->i > 1) 
		{
            $timedelay = $interval->i . " minutes ago";
        } 
		else 
		{
            $timedelay = "Just now";
        }

        $timedelayList[] = $timedelay;
    }
}

$newMessages = array();

if (count($names) > 1) 
{
	foreach ($names as $index => $nameList) 
	{
		$time = isset($timedelayList[$index]) ? $timedelayList[$index] : "Just now"; 
		$currentMessage = isset($message[$index]) ? $message[$index] : 'No new messages';
		
		if (strlen($currentMessage) > 23) 
		{
			$truncatedMessage = substr($currentMessage, 0, 23);
			$lastSpace = strrpos($truncatedMessage, ' '); // Find last space in the truncated message
			$currentMessage = $lastSpace !== false ? substr($truncatedMessage, 0, $lastSpace) : $truncatedMessage;
			$currentMessage = rtrim($currentMessage) . '...';
		}
		
		$newMessages[] = 
		[
			'name' => $nameList,
			'message' => $currentMessage,
			'time' => $time
		];
	}
} 
else 
{
	foreach ($names as $index => $nameList) 
	{
		$time = isset($timedelayList[$index]) ? $timedelayList[$index] : "Just now";
		$nameList = implode('', $names); 		
		$currentMessage = isset($message[$index]) ? $message[$index] : 'No new messages';
		if (strlen($currentMessage) > 23) 
		{
			$truncatedMessage = substr($currentMessage, 0, 23);
			$lastSpace = strrpos($truncatedMessage, ' '); // Find last space in the truncated message
			$currentMessage = $lastSpace !== false ? substr($truncatedMessage, 0, $lastSpace) : $truncatedMessage;
			$currentMessage = rtrim($currentMessage) . '...';
		}

		$newMessages[] = 
		[
			'name' => $nameList,
			'message' => $currentMessage,
			'time' => $time
		];
	}
}





$status1 = ($status == 'UNREAD' && count($newMessages) > 0);
?>	
<div style="position: relative;">
 
   <?php 
    if ($status1 && count($names) == 1) 
	{ 
	$status1 = 'UNREAD';
	?>
        <img src='../leave/images/notification.png' class='drop-icon' id="notificationIcon" style='width:35px;height:35px;margin-left:370px; cursor: pointer;position: relative; animation: drop 0.6s ease-out;'>
    <?php 
	}     
	elseif ($status1 && count($names) == 2) 
	{ 
	$status1 = 'UNREAD';
	?>
        <img src='../leave/images/notification2.png' class='drop-icon' id="notificationIcon" style='width:35px;height:35px;margin-left:370px; cursor: pointer;position: relative; animation: drop 0.6s ease-out;'>
    <?php 
	}  
	elseif ($status1 && count($names) == 3) 
	{ 
	$status1 = 'UNREAD';
	?>
        <img src='../leave/images/notification3.png' class='drop-icon' id="notificationIcon" style='width:35px;height:35px;margin-left:370px; cursor: pointer;position: relative; animation: drop 0.6s ease-out;'>
    <?php 
	}	
    elseif ($status1) 
	{ 
	$status1 = 'UNREAD';
	?>
        <img src='../leave/images/notification3plus.png' class='drop-icon' id="notificationIcon" style='width:35px;height:35px;margin-left:370px; cursor: pointer;position: relative; animation: drop 0.6s ease-out;'>
    <?php 
	} 
 	
	else 
	{
	$status1 = 'READ';		
	?>
        <img src='../leave/images/bellicon.png' class='drop-icon' id="notificationIcon" style='width:35px;height:35px;margin-left:370px; cursor: pointer;position: relative;animation: drop 0.6s ease-out;' title="You have no new messages">
    <?php 
	} 
	
	
	if ($status1 == 'UNREAD') 
	{ 
    ?>
		<div id="messageDropdown1" class="dropdown-content1">
			<?php 
			$newMessages = array_reverse($newMessages); 
			foreach ($newMessages as $msg) 
			{ 
			?>
				<div class="message-item">
				    <img align=left src="../leave/dp/<?php echo strtolower($msg['name']); ?>.png" alt="Profile Picture" style="width:35px; height:35px; border-radius:50%; vertical-align: middle; margin-right: 8px;">
					<strong style='margin-right:20px;'><?php echo $msg['name']; ?></strong><br>
					<span style='margin-left:-30px;'><?php echo $msg['message']; ?></span>
					<div style='margin-left:22px;' class="time"><?php echo $msg['time']; ?></div>
				</div>
			<?php 
			} 
			?>
		</div>
    <?php 
	}
	elseif(count($names) < 1)
	{
    ?>
        <div id="messageDropdown2" class="dropdown-content2">
            <?php foreach ($newMessages as $msg) 
			{ 
			?>
            <?php 
			} 
			?>
        </div>
    <?php 		
	}
	else
	{
		
	}
	?>
</div>
	<a href="logout.php" class="logout-button">Logout</a>
</div>
<aside class="sidebar3" id="sidebar3">
    <div class="sidebar3-header">


<script>


document.getElementById("notificationIcon").addEventListener("click", function () 
{
	const dropdown = document.getElementById("messageDropdown1");
    dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
});



document.getElementById("notificationIcon").addEventListener("click", function () 
{
    const dropdown = document.getElementById("messageDropdown2");

    if (dropdown.children.length === 0) 
	{
        dropdown.innerHTML = "<strong style='margin-top:10px;display:block;'>No new messages!</strong>";
    }

    if (dropdown.style.display === "block") 
	{
        dropdown.style.display = "none";
        dropdown.style.opacity = 0;
    } 
	else 
	{
        dropdown.style.display = "block";
        dropdown.style.opacity = 1;

        setTimeout(function () 
		{
            let fadeEffect = setInterval(function () 
			{
                if (dropdown.style.opacity > 0) 
				{
                    dropdown.style.opacity -= 0.05;
                } 
				else 
				{
                    clearInterval(fadeEffect);
                    dropdown.style.display = "none";
                }
            }, 50);
        }, 2000);
    }
});





</script>	


<script>
localStorage.setItem('profileImagePath', '<?php echo $imagePath; ?>');
</script>	


<?php

$profileImages = 
[
    '1118' => 'test1.png',
    '1119' => 'test2.png'

];

if ($empid == '1118') 
{
    $defaultImage = 'test1.png';
}
elseif ($empid == '1119') 
{
    $defaultImage = 'test2.png';
}
else 
{
    $defaultImage = ($gender == 'MALE') ? 'male1.png' : 'female1.png';//style="width:55px;height:55px;"
}
  
$imageFile = array_key_exists($empid, $profileImages) ? $profileImages[$empid] : $defaultImage;
$imagePath = "../leave/dp/$imageFile"; 
 
?>
<?php

$imagePath = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : "../leave/dp/$defaultImage";
?>

<img id="profileImage" src="<?php echo $imagePath . '?' . time(); ?>" style="width:55px;height:55px;border-radius:50px;"><br>

<script>
const updatedImagePath = localStorage.getItem('profileImagePath');
if (updatedImagePath) 
{
    document.getElementById('profileImage').src = updatedImagePath + '?' + new Date().getTime();
    localStorage.removeItem('profileImagePath');
}
</script>

<style>
.n
{
 opacity:0; 
 transition:opacity 0.0s ease-out;	
}
.sidebar3:hover .n
{
    opacity: 1;
	transition:opacity 0.5s ease-in;
}

@media screen and (max-width: 768px) 
{
    #sidebar3
	{
        width: 200px;
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

	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style="color:white;" class="n"><?php echo $name; ?></b>
    </div>
    <ul class="sidebar3-links">
	
        <h4>
            <span>Main Menu</span>
            <div class="menu-separator"></div>
        </h4>
		
        <li>
            <a href="dashboard.php" class="active">
				<span class="material-symbols-outlined">DASHBOARD</span>Dashboard
			</a>
		</li>
		
		<li>
			<a href="viewprofile.php">
				<span class="material-symbols-outlined">account_circle</span>My Profile 
			</a>
        </li>

		<li>
			<a href="leavereport.php">
				<span class="material-symbols-outlined">assignment</span>Leave Report
			</a>			
        </li>

		<li>		
			<a href="leaverequest.php">		
				<span class="material-symbols-outlined">event</span>Request for Leave
			</a>		
        </li>	

		<li>		
			<a href="leaverankings.php">
				<span class="material-symbols-outlined">leaderboard</span>Leave Rankings
			</a>			
		</li>
		
        <h4>
            <span>Account</span>
            <div class="menu-separator"></div>
        </h4>

        <li>
            <a href="changepassword.php">
				<span class="material-symbols-outlined">Password</span>Passwords
			</a>
        </li>

        <li>
            <a href="logout.php" class="logout" onclick="logoutAndRedirect(event)"><span class="material-symbols-outlined"> logout </span>Logout</a>
        </li>
		
    </ul>
</aside>


<div class="content" id="content">
    <iframe id="contentFrame" src="dashboard.php" title="Learning Iframe"></iframe>
</div>

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

    const sidebarLinks = document.querySelectorAll('.sidebar3-links li a');
    sidebarLinks.forEach(link => 
	{
		
        link.addEventListener('click', function(e) 
		{
            e.preventDefault();
            sidebarLinks.forEach(link => link.classList.remove('active'));
            this.classList.add('active');
            const href = this.getAttribute('href');
            loadContent(href);
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



<br>	
<center>
<?php

$color = "color:red;";


?>
</center>

<center>
&nbsp;&nbsp;
&nbsp;&nbsp;
&nbsp;&nbsp;

</div>

</div>

</center>

<?php
	

$eid='';

	}


?>

</style>


<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>


<script>


document.addEventListener('DOMContentLoaded', function() 
{
    const customSelect = document.querySelector('.custom-select');
    const selectedOption = customSelect.querySelector('.selected-option');
    const optionsList = customSelect.querySelector('.options-list');
    const originalSelect = document.getElementById('empid');

    selectedOption.addEventListener('click', () => 
	{
        optionsList.classList.toggle('show');
    });

    optionsList.addEventListener('click', (event) => 
	{
        if (event.target.classList.contains('option')) 
		{
            const value = event.target.getAttribute('data-value');
            const text = event.target.textContent.trim();
            const imgSrc = event.target.querySelector('img').src;

            selectedOption.innerHTML = `<img src="${imgSrc}" class="option-image"> ${text}`;
            originalSelect.value = value;
            optionsList.classList.remove('show');
        }
    });

    document.addEventListener('click', (event) => 
	{
        if (!customSelect.contains(event.target)) 
		{
            optionsList.classList.remove('show');
        }
    });
});



const selectElement = document.getElementById('yourSelectElementId');
selectElement.querySelectorAll('option').forEach(option => 
{
    const imageSrc = option.getAttribute('data-image');
    if (imageSrc) 
	{
        option.innerHTML = `<img src='${imageSrc}' style='width:30px;height:30px;border-radius:50%;'> ${option.textContent}`;
    }
});



  let senderid = '<?php echo $eid; ?>';

  function openList() 
  {
    document.getElementById("myFriends").style.display = "block";
  }

  function closeForm() 
  {
    document.getElementById("myFriends").style.display = "none";
  }



    document.addEventListener('click', function (event) 
	{
		const chatPopup = document.getElementById('myFriends');
		if (!chatPopup.contains(event.target) && chatPopup.style.display === 'block') 
		{
		  chatPopup.style.display = 'none';
		}
    });
  
  function showChatPopup() 
  {
    const chatPopup = document.getElementById('myFriends');
    chatPopup.style.display = 'block';
  }
  
  
   
</script>	

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<?php


$names = array();
foreach ($_SESSION['senderIds'] as $senderid) 
{

    if ($senderid == '1000') { $names[] = 'Sanoop'; }
    if ($senderid == '1004') { $names[] = 'Sarath'; }
    if ($senderid == '1001') { $names[] = 'Anurag'; }
    if ($senderid == '1002') { $names[] = 'Anna'; }
    if ($senderid == '1003') { $names[] = 'Rithik'; }
    if ($senderid == '1044') { $names[] = 'Jinoy'; }
    if ($senderid == '1068') { $names[] = 'Anandhu'; }
    if ($senderid == '1091') { $names[] = 'Akhil'; }
    if ($senderid == '1070') { $names[] = 'Manju'; }
    if ($senderid == '1071') { $names[] = 'Vyshnav'; }
    if ($senderid == '999') { $names[] = 'MEFS'; }
    if ($senderid == '1108') { $names[] = 'MEFS'; }
}


$names = array_unique($names);

if (count($names) > 1) 
{
    $displayNames = $names;
    $last_name = array_pop($displayNames);
    $nameList = implode(', ', $displayNames) . " and " . $last_name;
} 
else 
{
    $nameList = implode('', $names);
}	


if ($status == 'UNREAD') 
{ 
echo "<script>
        // Initial delay of 15 seconds
        setTimeout(function() 
		{
            showNotification('$nameList');

            // After the first display, set an interval of 30 seconds
            setInterval(function() 
			{
                showNotification('$nameList');
            }, 30000); // 30 seconds interval

        }, 15000); // Initial 15 seconds delay
      </script>";
?>	  
	  
    <script>
        const notification = document.getElementById("notification");
        notification.style.transform = "translateX(100%)";
        notification.style.opacity = "0";
        notification.style.display = "block";

        let opacity = 0;
        let position = 100;
        const slideIn = setInterval(() => 
		{
            if (position <= 0 && opacity >= 1) 
			{
                clearInterval(slideIn);

                setTimeout(() => 
				{
                    const fadeOut = setInterval(() => 
					{
                        if (opacity <= 0) 
						{
                            clearInterval(fadeOut);
                            notification.style.display = "none";
                        } 
						else 
						{
                            opacity -= 0.03;
                            notification.style.opacity = opacity;
                        }
                    }, 75);
                }, 1500);
            } 
			else 
			{
                position -= 2;
                opacity += 0.03;
                notification.style.transform = `translateX(${position}%)`;
                notification.style.opacity = opacity;
            }
        }, 20);
		

		function showNotification(nameList) 
		{
			const popup = document.getElementById('notificationPopup');
			const nameListElement = document.getElementById('nameList');

			nameListElement.textContent = nameList;

			popup.style.display = 'block';
			popup.style.opacity = 1; 

			setTimeout(function()
			{
				let opacity = 1;
				const fadeOut = setInterval(function() 
				{
					if (opacity <= 0) 
					{
						clearInterval(fadeOut);
						popup.style.display = 'none'; 
					} 
					else 
					{
						opacity -= 0.05; 
						popup.style.opacity = opacity;
					}
				}, 50);
			}, 5000); 
		}		
		
    </script>
<?php 
} 
?>


</body>
<?php	
}	


elseif($flag=='N')//Disabled Employees
{
?>

<style>

        .header 
		{
            background:#e8ab2e;
            background-size: 400% 400%;
            color: black;
            padding: 12px 20px;
            text-align: center;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
			display: flex;
			justify-content: space-between;
			align-items: center;
            
        }
		
		.header p 
		{
			font-weight:bold;
			align-items:center;		
		}	
		
		.logout-button 
		{
		  background-color: red;
		  padding: 6px 15px;
		  border-radius: 5px;
		  color: white;  
		  border: none;
		  font-size: 15px;  
		  cursor: pointer;  
		  text-decoration: none;
		  caret-color:transparent;
		}		

        .sidebar2 
		{
            position: fixed;
            top: 47px; 
            left: 0;
            height: calc(100% - 47px); 
            width: 85px;
            display: flex;
            overflow-x: hidden;
            flex-direction: column;
            background: #ba4022;
            padding: 25px 20px;
            transition: all 0.4s ease;
            z-index: 999;
        }

        .sidebar2:hover 
		{
            width: 260px;
        }

        .sidebar2 .sidebar2-header 
		{
            display: flex;
            align-items: center;
        }

        .sidebar2 .sidebar2-header img 
		{
            width: 42px;
            border-radius: 50%;
        }

        .sidebar2 .sidebar2-header h2 
		{
            color: white;
            font-size: 1rem;
            
            white-space: nowrap;
            margin-left: 23px;
        }

        .sidebar2-links 
		{
            list-style: none;
            margin-top: 20px;
            height: 80%;
            overflow-y: auto;
            scrollbar-width: none;
        }

        .sidebar2-links::-webkit-scrollbar 
		{
            display: none;
        }

        .sidebar2-links h4
		{
            color: white;
            white-space: nowrap;
            margin: 10px 0;
            position: relative;
        }

        .sidebar2-links h4 span 
		{
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .sidebar2:hover .sidebar2-links h4 span 
		{
            opacity: 1;
        }

        .sidebar2-links .menu-separator 
		{
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            transform: translateY(-50%);
            background: #4f52ba;
            transform-origin: right;
            transition: transform 0.4s ease;
        }

        .sidebar2:hover .sidebar2-links .menu-separator 
		{
            transform: scaleX(0);
        }

        .sidebar2-links li a 
		{
            display: flex;
            align-items: center;
            gap: 0 20px;
            color: white;
            
            white-space: nowrap;
            padding: 15px 10px;
            text-decoration: none;
            transition: color 0.2s ease, background 0.2s ease;
            cursor: pointer;
        }

        .sidebar2-links li a.active 
		{
            color: #161a2d;
            background: #ffc815;
            border-radius: 4px;
        }
		
		.sidebar2-links li a:hover
		{
		    color: white;
            background:  #db7258;
            border-radius: 4px;
		}	

        .content 
		{
            margin-top: 60px;
            margin-left: 85px;
            flex-grow: 1;
            padding: 3px 20px;
            transition: margin-left 0.4s ease;
        }

        .content iframe 
		{
            width: 100%;
            height: 90vh;
            border: none;
        } 

</style>



	<body class="report" bottommargin="0" topmargin="0" leftmargin="0" rightmargin="0" style="background-color: #edb984;caret-color:transparent;">	


	<?php
	include "header2.html";
	?>
	<br><br><br><br><br><br>
	<center>
	<?php
	if($gender=='MALE')
	{
		?>
		<img src='../leave/dp/male1.png' style="width:100px;height:100px;"><br><b><?php echo $name; ?></b></img>
		<?php		
	}
	else
	{
		?>
		<img src='../leave/dp/female1.png' style="width:100px;height:100px;"><br><b><?php echo $name; ?></b></img>
		<?php	
	}	
		
	echo "<br><br><br><b>YOU ARE NO LONGER AN EMPLOYEE OF THIS COMPANY</b>";
	

?>

<style>
.n
{
 opacity:0; 
 transition:opacity 0.0s ease-out;	
}
.sidebar2:hover .n
{
    opacity: 1;
	transition:opacity 0.5s ease-in;
}
@keyframes drop 
{
    0% { transform: translateY(-20px); opacity: 0; }
    60% { transform: translateY(5px); opacity: 1; }
    80% { transform: translateY(-2px); }
    100% { transform: translateY(0); }
}
</style>


<div class="header">
    <button class="back-button" onclick="goBack()">
      <img src="../leave/images/back-button.png" alt="Back">
    </button>
    <b style="font-size:20px;align-items:center;margin-left:440px;">MIDDLE EAST FINANCIAL SOFTWARE SOLUTIONS</b>
	<img src='../leave/images/bellicon.png' class='drop-icon' style='width:35px;height:35px;margin-left:370px;cursor:pointer;position:relative;animation:drop 0.6s ease-out;' 
	title="<?php echo 'You have no new messages';?>">
	<a href="logout.php" class="logout-button">Logout</a>
</div>
<aside class="sidebar2" id="sidebar2">
    <div class="sidebar2-header">
        <img src='../leave/images/MEFS.png' alt="logo"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b class="n" style="color:white;"><?php echo $name; ?></b>
    </div>
    <ul class="sidebar2-links">
        <h4>
            <span>Main Menu</span>
            <div class="menu-separator"></div>
        </h4>
        <li>
            <a href="dashboard.php" class="active" title="<?php echo 'Access denied since you are not an active employee';?>">
				<span class="material-symbols-outlined">DASHBOARD</span>Dashboard
			</a>
		</li>
		
        <li>
            <a href="viewprofile.php" class="disabled" title="<?php echo 'Access denied since you are not an active employee';?>">
                <span class="material-symbols-outlined" title="<?php echo 'Access denied since you are not an active employee';?>">account_circle</span>My Profile 
            </a>
        </li>
        <li>
            <a href="leavereport.php" class="disabled" onclick="return false;">
                <span class="material-symbols-outlined" title="<?php echo 'Access denied since you are not an active employee';?>">visibility</span>View Leave Report
            </a>
        </li>
        <li>
            <a href="leaverequest.php" class="disabled" onclick="return false;">
                <span class="material-symbols-outlined" title="<?php echo 'Access denied since you are not an active employee';?>">event_note</span>Request for Leave
            </a>
        </li>
		<li>		
			<a href="leaverankings.php" class="disabled" onclick="return false;">
				<span class="material-symbols-outlined" title="<?php echo 'Access denied since you are not an active employee';?>">leaderboard</span>Leave Rankings
			</a>			
		</li>
		
        <h4>
            <span>Account</span>
            <div class="menu-separator"></div>
        </h4>

        <li>
            <a href="changepassword.php" class="disabled"  onclick="return false;">
				<span class="material-symbols-outlined" title="<?php echo 'Access denied since you are not an active employee';?>">Password</span>Passwords
			</a>
        </li>
        <li>
            <a href="logout.php" class="logout" onclick="logoutAndRedirect(event)"><span class="material-symbols-outlined"> logout </span>Logout</a>
        </li>
		
    </ul>
</aside>


<div class="content" id="content">
    <iframe id="contentFrame" src="dashboard.php" title="Learning Iframe"></iframe>
</div>


<script>


    function loadContent(url) 
	{
        document.getElementById('contentFrame').src = url;
    }

    const sidebar = document.getElementById('sidebar2');
    const content = document.getElementById('content');

    sidebar.addEventListener('mouseover', () => 
	{
        content.style.marginLeft = '260px';
    });

    sidebar.addEventListener('mouseout', () => 
	{
        content.style.marginLeft = '85px';
    });

    const sidebarLinks = document.querySelectorAll('.sidebar2-links li a');
    sidebarLinks.forEach(link => 
	{
		
        link.addEventListener('click', function(e) 
		{
            e.preventDefault();
            sidebarLinks.forEach(link => link.classList.remove('active'));
            this.classList.add('active');
            const href = this.getAttribute('href');
            loadContent(href); 
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

</body>

<?php

}
else
{
////////////////////////////////////////////////	
}	
?>
</center>

</html>
