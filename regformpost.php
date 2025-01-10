<?php
include "connect.php";
if (isset($_POST['create'])) 
{ 
    $first_name ='';  if(isset($_POST['fname'])){$first_name = trim(strtoupper($_POST['fname']));}
    $middle_name ='';  if(isset($_POST['mname'])){$middle_name = trim(strtoupper($_POST['mname']));}
    $last_name ='';  if(isset($_POST['lname'])){$last_name = trim(strtoupper($_POST['lname']));}
    $gender ='';  if(isset($_POST['gender'])){$gender = trim($_POST['gender']);}
    $dob ='';  if(isset($_POST['dob'])){$dob = trim($_POST['dob']);}
    $doj ='';  if(isset($_POST['doj'])){$doj = trim($_POST['doj']);}
	$emptype ='';  if(isset($_POST['emptype'])){$emptype = trim($_POST['emptype']);}
	$designation ='';  if(isset($_POST['designation'])){$designation = trim($_POST['designation']);}
    $email ='';  if(isset($_POST['email'])){$email = trim($_POST['email']);}
    $mobile ='';  if(isset($_POST['mobile'])){$mobile = trim($_POST['mobile']);}
    $address ='';  if(isset($_POST['address'])){$address = trim(strtoupper($_POST['address']));}
    $username ='';  if(isset($_POST['username'])){$username = trim($_POST['username']);}
    $password ='';  if(isset($_POST['password'])){ $password = trim($_POST['password']);}
	
	$arr = explode('-',$doj);
	
	$day=(int)$arr[2];
	$dojtype='N';
	if($day <= 10){
		$dojtype='Y';}
		
 $name = $first_name . ' ' . $middle_name . ' ' . $last_name;
if ($address == NULL) {
    $address = '';
}

 $dobDate = new DateTime($dob);
    $dojDate = new DateTime($doj);
    $currentDate = new DateTime();

    
    $ageAtJoining = $dobDate->diff($dojDate)->y;

    
    if ($dojDate > $currentDate) {
        echo "<script>alert('Date of Joining cannot be in the future'); window.location.href='regform.php'</script>";
        exit();
    }

    
    if ($ageAtJoining < 18) {
        echo "<script>alert('Date of Joining must be at least 18 years after Date of Birth'); window.location.href='regform.php'</script>";
        exit();
    }

$checkQuery = "SELECT COUNT(*) AS count FROM emp WHERE username = ?";
$params = array($username);
$stmt = sqlsrv_query($conn, $checkQuery, $params);
if ($stmt === false) {
	die(print_r(sqlsrv_errors(), true));
}
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
if ($row['count'] > 0) {
	echo "<script>alert('Username already exists'); window.location.href='regform.php'</script>";
} else {

$sql = "INSERT INTO emp (username, password, name, dob, doj, dojtype, gender, email, address, mob, emptype, designation, flag, flag2) 
        VALUES ('$username', '$password', '$name', '$dob', '$doj', '$dojtype', '$gender', '$email', '$address', '$mobile', '$emptype', '$designation', 'Y', '0')";
		


$result = sqlsrv_query($conn, $sql);


	if ($result === false) 
	{
		die(print_r(sqlsrv_errors(), true));
	} 
	else 
	{
		echo "<script>alert('Successfully Inserted'); window.location.href='regform.php'</script>";
	}
  }
}
sqlsrv_close($conn);
?>
	