<?php
session_start();
include "connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $empid = $_POST['empid'];
    $compodate = $_POST['compodate'];

    $sql = "DELETE FROM compo WHERE empid = '$empid' AND compodate = '$compodate'";
    $res = sqlsrv_query($conn, $sql);

    if ($res === false) 
	{
        die(print_r(sqlsrv_errors(), true));
    } 
	else 
	{
        echo "Compensatory date deleted successfully.";
    }
	
}
?>
