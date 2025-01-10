<?php

$serverIP = "192.168.1.100";
$serverName = "192.168.1.100";
// $serverName = $_SERVER['SERVER_ADDR'];   
$uid = "sa";      
$pwd = "sa_2014";     
$databaseName = "MISGlobal";    
    
$connectionInfo = array( "UID"=>$uid,                               
                         "PWD"=>$pwd,                               
                         "Database"=>$databaseName , 'ReturnDatesAsStrings'=>true);    
     
/* Connect using SQL Server Authentication. */     
$conn = sqlsrv_connect( $serverName, $connectionInfo);
if(!$conn)
    echo "Connection Error !";   
  
?>    