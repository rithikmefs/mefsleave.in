<?php
session_start();
include "connect.php";

$leavetype = '';

if (isset($_POST['leavetype'])) 
{
    $leavetype = $_POST['leavetype'];
    $_SESSION['leavetype'] = $leavetype;
    echo "Selected leave type is: " . $leavetype;
}
?>

