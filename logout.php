<?php
session_start();

unset($_SESSION['user']);
unset($_SESSION['empid']);
session_destroy();
header("Location:login.php");
?>