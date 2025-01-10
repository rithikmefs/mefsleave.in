<html>
<head>
<style>
tr.dis:hover 
{
	background-color:#cbcdd1;
} 
.edit-icon 
{
    width: 20px;
    height: 20px;
    display: block;
    margin: auto;
    cursor: pointer;
}
.edit-icon:hover 
{
    transform: scale(1.2);
}
.sub
{
	width:95%;
	border-radius:8px;
	background-color:#2b2826;
	text-align:center;
	font-family:poppins;
	font-size:16px;
	transition:background-color 0.3s;
	height:8%;
	color:white;
    margin: 0;
    cursor: pointer;
    transition: background-color 0.2s ease, transform 0.2s ease;	
}	
.sub:hover 
{
	transform: scale(1.05);
}
</style>
<title>
Mefs - Leave Portal
</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="icon" href="../leave/images/MEFS.png">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
</head>
<body style="caret-color:transparent;" bottommargin="0" topmargin="0" leftmargin="0" rightmargin="0">	

<?php
session_start();
include "connect.php";

if ($conn === false) 
{
    die("Connection failed: " . print_r(sqlsrv_errors(), true));
}


$sql = "SELECT empid,name AS 'Employee Name', doj AS 'Date of Joining', designation AS 'Designation', ISNULL(CONVERT(VARCHAR, exitdate, 105), CONVERT(VARCHAR, GETDATE(), 105)) AS 'Date of Departure' FROM emp WHERE flag = 'N' order by exitdate desc";
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) 
{
    die("Error in query execution: " . print_r(sqlsrv_errors(), true));
}


if (sqlsrv_has_rows($stmt)) 
{
	
    echo "<table style='width:100%;border-collapse:collapse;'>";
    echo "<tr style='font-family:poppins;background-color:#665763;color:white;'>
	<th style='border:1px solid #ccc;padding:8px;'>Employee Name</th>
	<th style='border:1px solid #ccc;padding:8px;'>Date of Joining</th>
	<th style='border:1px solid #ccc;padding:8px;'>Designation</th>
	<th style='border:1px solid #ccc;padding:8px;'>Date of Departure</th>
	<th style='border:1px solid #ccc;padding:8px;'>Action</th>
	</tr>";
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) 
	{

		$empid = isset($row['empid']) ? trim($row['empid']) : '';
		
		echo "<tr class='dis' style='font-family:poppins;'>
		
				<td align='center' style='border: 1px solid #ccc; padding: 8px;'>" . htmlspecialchars($row['Employee Name']) . "</td>
				
				<td align='center' style='border: 1px solid #ccc; padding: 8px;'>" . 
					htmlspecialchars(
						isset($row['Date of Joining']) && $row['Date of Joining'] instanceof DateTime
							? $row['Date of Joining']->format('d-m-Y')
							: date('d-m-Y', strtotime($row['Date of Joining']))
					) . 
					"</td>
					
				<td align='center' style='border: 1px solid #ccc; padding: 8px;'>" . htmlspecialchars($row['Designation']) . "</td>
				
				<td align='center' style='border: 1px solid #ccc; padding: 8px;'>" . 
					htmlspecialchars(
						isset($row['Date of Departure']) && $row['Date of Departure'] instanceof DateTime
							? $row['Date of Departure']->format('d-m-Y')
							: date('d-m-Y', strtotime($row['Date of Departure']))
					) . 
				"</td>
				
				<td align='center' style='border: 1px solid #ccc; padding: 8px;'>
					<a href='enable_employee.php?empid=$empid' onsubmit='return confirmEnableEmployee();'>
						<button type='button' class='sub'>ENABLE</button>
					</a>
				</td>
				
			</tr>";

			  
    }
    echo "</table>";
	
} 
else 
{
    echo "<p align='center'>No exit employees found.</p>";
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);

?>


<script>

function confirmEnableEmployee()
{
	return confirm("Are you sure you want to enable back this employee?");
}

</script>
