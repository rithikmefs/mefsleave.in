<?php

session_start();
include "connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET') 
{
    echo "GET request received successfully.<br>";
    $power = $_POST['power'] ?? 'Y';
    $_SESSION['power'] = $power;
    echo "Power value updated to: $power <br>";
} 
else 
{
    echo "Request method: " . $_SERVER['REQUEST_METHOD'] . "<br>";
    echo "ERROR";
}



if (isset($_SESSION['power']) && $_SESSION['power'] === 'N') 
{
    echo "Current state: Power is OFF and value is $power";
}
elseif (isset($_SESSION['power']) && $_SESSION['power'] === 'Y') 
{
    echo "Current state: Power is ON and value is $power";
} 
else 
{
    echo "Invalid state.";
}

?>

