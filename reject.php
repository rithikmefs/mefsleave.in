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
$leavedate = $data['leavedate'];

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
    "Leave Request Rejected of date - $formattedleavedate",
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



/* $sql2 = "INSERT INTO msg (senderid, receiverid, message, timestamp, isread, status, typing) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";//RAJAGOPAL
$param2 = 
[
    '1108',
    $empid,
    "Leave Request Rejected of date - $formattedleavedate",
    $time, 
    '0', 
    'UNREAD',
    'N' 
];

// Execute the query
$stm2 = sqlsrv_query($conn, $sql2, $param2);

if ($stm2 === false) 
{
    die(print_r(sqlsrv_errors(), true));
} */



$sql3 = "DELETE FROM leaverequest WHERE empid = ? AND leavedate = ?";

$params = array($empid, $leavedate);
$stmt = sqlsrv_query($conn, $sql3, $params);

if ($stmt === false) 
{
    echo json_encode(["success" => false, "message" => "Error: " . sqlsrv_errors()]);
} 
else 
{
    echo json_encode(["success" => true, "message" => "Record deleted successfully"]);
}

sqlsrv_close($conn);

?>
