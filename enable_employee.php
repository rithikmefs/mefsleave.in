<?php
session_start();
include "connect.php"; 

if (isset($_GET['empid'])) 
{
    
	$empid = $_GET['empid'];
    $sql = "UPDATE emp SET flag = ?, exitdate = ? WHERE empid = ?";
    $params = ['Y',null, $empid];

    $stmt = sqlsrv_prepare($conn, $sql, $params);

    if ($stmt && sqlsrv_execute($stmt)) 
	{
        echo "<script>alert('Employee enabled successfully!'); window.location.href='dashboard.php';</script>";
    } 
	else 
	{
        echo "<script>alert('Error enabling employee!'); window.location.href='dashboard.php';</script>";
    }

    sqlsrv_free_stmt($stmt); 
    sqlsrv_close($conn); 
} 

else 
{
    echo "<script>alert('Invalid Request!'); window.location.href='dashboard.php';</script>";
}

?>
