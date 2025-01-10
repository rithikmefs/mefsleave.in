<?php
session_start();
include "connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the data from the request
    $input = json_decode(file_get_contents('php://input'), true);
    $empid = $input['empid'];
    $flag = $input['flag'];
    $exitdate = ($flag === 'Y') ? null : date('Y-m-d'); // Set exitdate to NULL if enabled, otherwise current date

    // Check database connection
    if ($conn === false) {
        echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
        exit;
    }

    // First query: Update flag
    $query1 = "UPDATE emp SET flag = ? WHERE empid = ?";
    $params1 = [$flag, $empid];
    $stmt1 = sqlsrv_query($conn, $query1, $params1);

    if ($stmt1 === false) {
        echo json_encode(['success' => false, 'message' => sqlsrv_errors()]);
        sqlsrv_free_stmt($stmt1); // Free statement resource before exiting
        sqlsrv_close($conn); // Close connection
        exit;
    }

    // Second query: Update exitdate (NULL if enabled)
    $query2 = "UPDATE emp SET exitdate = ? WHERE empid = ?";
    $params2 = [$exitdate, $empid];
    $stmt2 = sqlsrv_query($conn, $query2, $params2);

    if ($stmt2 === false) {
        echo json_encode(['success' => false, 'message' => sqlsrv_errors()]);
        sqlsrv_free_stmt($stmt1);
        sqlsrv_free_stmt($stmt2);
        sqlsrv_close($conn);
        exit;
    }

    // Both updates successful
    echo json_encode(['success' => true]);

    // Free statement resources
    sqlsrv_free_stmt($stmt1);
    sqlsrv_free_stmt($stmt2);

    // Close connection
    sqlsrv_close($conn);
}
?>
