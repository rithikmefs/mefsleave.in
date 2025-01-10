<?php
session_start();
include "connect.php";

if (isset($_POST['empid'])) 
{
    $empid = $_POST['empid'];
    $sql7 = "SELECT compodate FROM compo WHERE empid='$empid' order by compodate";
    $res7 = sqlsrv_query($conn, $sql7);
    
    if ($res7) 
	{
        $options7 = "";
        while ($row7 = sqlsrv_fetch_array($res7, SQLSRV_FETCH_ASSOC)) 
		{
            $compodate = isset($row7['compodate']) ? trim($row7['compodate']) : '';
			$date = DateTime::createFromFormat('Y-m-d', $compodate);
	        $cd = $date->format('d-m-Y');
            $options7 .= "<option value='$compodate'>$cd</option>";
        }
        echo $options7;
    }
}
?>
