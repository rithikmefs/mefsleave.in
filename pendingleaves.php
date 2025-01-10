<html>
<head>
<style>
tr.dis:hover 
{
	background-color:#cbcdd1;
}
</style>
</head>
<body style="caret-color:transparent;" bottommargin="0" topmargin="0" leftmargin="0" rightmargin="0">	

<?php
session_start();
include "connect.php"; 

$empid = $_SESSION['empid']; // Adjust based on your session management

$sql = "SELECT * FROM leaverequest WHERE empid = '$empid' order by time desc";
$result = sqlsrv_query($conn, $sql);

date_default_timezone_set("Asia/Kolkata");
$count = 0;

if ($result === false) 
{
    echo "<p style='color: red; text-align: center;'>Error fetching leave requests.</p>";
    die(print_r(sqlsrv_errors(), true));
}

if (sqlsrv_has_rows($result)) 
{
    echo "<table style='width:100%; border-collapse: collapse;'>";
    echo "<tr>
            <th style='border: 1px solid #ccc; padding: 8px;'>SL No</th>
            <th style='border: 1px solid #ccc; padding: 8px;'>Leave Date</th>
            <th style='border: 1px solid #ccc; padding: 8px;'>Leave Type</th>
            <th style='border: 1px solid #ccc; padding: 8px;'>Reason for Leave</th>
            <th style='border: 1px solid #ccc; padding: 8px;'>Sent Time</th>
            <th style='border: 1px solid #ccc; padding: 8px;'>Status</th>
          </tr>";

    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) 
	{
        $count++;

        $leavetype = $row['leavetype'] ?? '';
        $leavedate = $row['leavedate'] ?? '';
        $time = $row['time'] ?? '';
        $reason = $row['reason'] ?? '';

        // Format time
        $formattedTime = $time ? (new DateTime($time))->format('jS F g:ia') : 'N/A';

        // Format leave date
        $formattedLeaveDate = $leavedate ? DateTime::createFromFormat('Y-m-d', $leavedate)->format('d-m-Y') : 'N/A';

        // Conditional formatting
        $color = ($leavetype === 'COMPO') ? "background-color:#b8f0ad;" : '';

        echo "<tr class='dis'>
                <td align=center style='border: 1px solid #ccc; padding: 8px;'>$count</td>
                <td align=center style='border: 1px solid #ccc; padding: 8px;'>$formattedLeaveDate</td>
                <td align=center style='$color border: 1px solid #ccc; padding: 8px;'>$leavetype</td>
                <td align=center style='border: 1px solid #ccc; padding: 8px;'>$reason</td>
                <td align=center style='border: 1px solid #ccc; padding: 8px;'>$formattedTime</td>
                <td align=center style='border: 1px solid #ccc; padding: 8px;'>Pending</td>
              </tr>";
    }

    echo "</table>";
	
} 
else 
{
    echo "<p style='text-align:center;'>No pending leave requests.</p>";
}
?>


</body>
</html>