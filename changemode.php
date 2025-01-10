<?php
session_start();
include "connect.php";


$empid = $_POST['empid'] ?? null;
$newRole = $_POST['newRole'] ?? null;

echo "EMPLOYEE ID: $empid<br>";
echo "NEW ROLE: $newRole<br>";

if (!$empid || !$newRole) 
{
    die(json_encode(['success' => false, 'message' => 'Missing parameters']));
}

$emptype = null;
$power = null;


$sql = "SELECT emptype, power FROM emp WHERE empid = ?";
$params = [$empid];
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) 
{
    die(json_encode(['success' => false, 'message' => 'Query execution failed', 'error' => sqlsrv_errors()]));
}

if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) 
{
    $emptype = $row['emptype'];
    $power = $row['power'];
} 
else 
{
    die(json_encode(['success' => false, 'message' => 'Employee not found']));
}


if ($empid == '999' || $empid == '1108') 
{
    if ($newRole == 'PERSONAL' && ($emptype == 'ADMIN' && $power == 'Y')) 
	{
        $response = ['success' => true, 'message' => 'Entered elseif condition'];
    } 
	else 
	{
        $response = ['success' => false, 'message' => 'Conditions not met in inner block'];
    }
} 
elseif ($emptype == 'ADMIN' && $power == 'Y') 
{
    $response = ['success' => true, 'message' => 'Entered elseif for ADMIN with power Y'];
} 
else 
{
    $response = ['success' => false, 'message' => 'No conditions met'];
}


header('Content-Type: application/json');
echo json_encode($response);
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);

?>
