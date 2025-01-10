<?php
session_start();
include "connect.php";
header('Content-Type: application/json');

if ($conn === false) 
{
    echo json_encode(["success" => false, "message" => "Connection failed: " . sqlsrv_errors()]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$empid = $data['empid'];
$compodate1 = $data['compodate'];
$leavedate = $data['leavedate'];
$reason = $data['reason'];
$leavetype = $data['leavetype'];


$date = DateTime::createFromFormat('Y-m-d', $leavedate);
$formattedleavedate = $date->format('d-m-Y');
	
	date_default_timezone_set("Asia/Kolkata");
    $time = date('Y-m-d H:i:s');	


$sql1 = "INSERT INTO msg (senderid, receiverid, message, timestamp, isread, status, typing) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";//ARUN
$param1 = 
[
    '999',
    $empid,
    "Leave Request Accepted of date - $formattedleavedate",
    $time, 
    '0', 
    'UNREAD',
    'N' 
];

// Execute the query
$stm1 = sqlsrv_query($conn, $sql1, $param1);

if ($stm1 === false) 
{
    die(print_r(sqlsrv_errors(), true));
}




$sql = "INSERT INTO leave (empid, compodate, leavedate, reason, leavetype) 
        VALUES (?, ?, ?, ?, ?)";
$params = array($empid, $compodate1, $leavedate, $reason, $leavetype);

$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) 
{
    echo json_encode(["success" => false, "message" => "Error: " . sqlsrv_errors()]);
    sqlsrv_close($conn); // Close connection on error
    exit;
} 
else 
{

    $delete_sql = "DELETE FROM leaverequest WHERE empid = ? AND leavedate = ?";
    $delete_params = array($empid, $leavedate);

    $delete_stmt = sqlsrv_query($conn, $delete_sql, $delete_params);

    if ($delete_stmt === false) 
	{
        echo json_encode(["success" => false, "message" => "Error deleting from leaverequest: " . sqlsrv_errors()]);
    } 
	else 
	{
        echo json_encode(["success" => true, "message" => "Record inserted successfully and request deleted from leaverequest"]);
    }

    sqlsrv_free_stmt($delete_stmt);
}


sqlsrv_close($conn);

?>
