<?php
session_start();
include "connect.php";
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
$empid = ['999'];
$empid[] = '1108';

// Display both values
foreach ($empid as $id) 
{
if (isset($_POST['empid'])) 
{
    $id = $_POST['empid'];
}
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
$designation='';
if(isset($_POST['designation']))
{
	$designation=$_POST['designation'];	
}
$leavedate = '';
?>

<html>
<head>
<link rel="icon" href="MEFS.png">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap">
<style>
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
.report 
{
    caret-color: transparent;
}
td
{
	font-size:16px;
	padding:6px;
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
	background-color:#E8AB2E;
}

#content
{
	margin-right:120;
}
</style>
</head>
<body class="report" bottommargin="0" topmargin="0" leftmargin="0" rightmargin="0">

<?php
$trdata = "";
if ($id == '999' || $id == '1108') 
{
    $sql = "select * from leave with (nolock) where empid='$id'";
    $res = sqlsrv_query($conn, $sql);
    while ($row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC)) 
	{ 
        if ($row['empid'] == NULL) 
		{
            $row['empid'] = '';
        }
        $empid = trim($row['empid']);
    }

    $sql2 = "select * from emp with (nolock) where emptype!='TEST' AND flag='Y' AND empid!='1118' AND empid!='1119' order by dob";
    $res2 = sqlsrv_query($conn, $sql2);
    while ($row2 = sqlsrv_fetch_array($res2, SQLSRV_FETCH_ASSOC)) 
	{
        if ($row2['name'] == NULL) 
		{
            $row2['name'] = '';
        }
        $name = trim($row2['name']);

        if ($row2['empid'] == NULL) 
		{
            $row2['empid'] = '';
        }
        $empid = trim($row2['empid']);
		
        if ($row2['emptype'] == NULL) 
		{
            $row2['emptype'] = '';
        }
        $emptype = trim($row2['emptype']);
		
        if ($row2['doj'] == NULL) 
		{
            $row2['doj'] = '';
        }
        $doj = trim($row2['doj']);	
		
        if ($row2['dob'] == NULL) 
		{
            $row2['dob'] = '';
        }
        $dob = trim($row2['dob']);
		
        if ($row2['gender'] == NULL) 
		{
            $row2['gender'] = '';
        }
        $gender = trim($row2['gender']);
		
        if ($row2['designation'] == NULL) 
		{
            $row2['designation'] = '';
        }
        $designation = trim($row2['designation']);
		
        if ($row2['email'] == NULL) 
		{
            $row2['email'] = '';
        }
        $email = trim($row2['email']);
		
        if ($row2['mob'] == NULL) 
		{
            $row2['mob'] = '';
        }
        $mob = trim($row2['mob']);
		
        if ($row2['address'] == NULL) 
		{
            $row2['address'] = '';
        }
        $address = trim($row2['address']);		
        
        $trdata .= "
        <tr class='dis'>
        <td align=center style='font-family:Poppins;'>$empid</td>
        <td align=center style='font-family:Poppins;'>$name</td>
        <td style='font-family:Poppins;'>
            <a href=\"update_form.php?empid=$empid\">
                <img src='../leave/images/editicon.png' class='edit-icon'>
            </a>
        </td>
        </tr>";
    }
}
?>


<?php 
//include "header.html"; 
?>
<br><br>
<br><br>
<center>
<div id='content'>
<table class="report">
<tr>
<td align=center style='background-color:#665763;color:white;font-family:Poppins;'>EMPLOYEE ID</td>
<td align=center style='background-color:#665763;color:white;font-family:Poppins;'>EMPLOYEE NAME</td>
<td align=center style='background-color:#665763;color:white;font-family:Poppins;'>ACTION</td>
</tr>

<?php echo $trdata; ?>

</table>
</div>
</center>

</body>
</html>
