<?php
session_start();
include "connect.php";
$myempid='';
if(isset($_SESSION['empid']))
{
	$myempid=$_SESSION['empid'];	
}
?>

<html>
<head>
<title>
Mefs - Leave Portal
</title>
<style>
tbody tr:nth-child(odd)
{
	background-color:#e6eaf0;	
}

tbody tr:nth-child(even)
{
	background-color:#f2f3f5;	
}

tr.dis:hover 
{
	background-color:#E8AB2E;
}
</style>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="icon" href="../leave/images/MEFS.png">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">

<body bottommargin="0" topmargin="0" leftmargin="0" rightmargin="0" style="caret-color:transparent;">
<br><br><br>


<?php

$trdata = '';
$Slno = 1;
$color = '';
$fontsize = '';


 $sql2 = "SELECT e.empid, e.name, ISNULL((SELECT COUNT(l.leavedate) FROM leave l 
         WHERE l.empid = e.empid AND YEAR(l.leavedate) = YEAR(GETDATE())), 0) AS leavecount, 
         (SELECT MAX(l.leavedate) FROM leave l WHERE l.empid = e.empid AND l.leavedate < GETDATE()) AS lastleavedate FROM emp e WITH (NOLOCK)
         WHERE e.emptype != 'TEST' AND e.flag = 'Y' AND e.empid NOT IN ('1118', '1119', '1108', '1070')
         ORDER BY leavecount ASC, lastleavedate ASC";  
		 

/*  $sql2 = "SELECT 
    e.empid, 
    e.name, 
    ISNULL(
        (SELECT COUNT(l.leavedate) 
         FROM leave l 
         WHERE l.empid = e.empid AND YEAR(l.leavedate) = 2024), 0
    ) AS leavecount, 
    (SELECT MAX(l.leavedate) 
     FROM leave l 
     WHERE l.empid = e.empid AND l.leavedate < GETDATE()) AS lastleavedate 
FROM emp e WITH (NOLOCK)
WHERE 
    e.emptype != 'TEST' 
    AND e.flag = 'Y' 
    AND e.empid NOT IN ('1118', '1119', '1108', '1070')
ORDER BY leavecount ASC, lastleavedate ASC;
";  */
		 

$res2 = sqlsrv_query($conn, $sql2);

$employees = [];

while ($row2 = sqlsrv_fetch_array($res2, SQLSRV_FETCH_ASSOC)) 
{
    $name = $row2['name'] ?? '';
    $empid = $row2['empid'] ?? '';
    $leavecount = $row2['leavecount'] ?? 0;
    $lastleavedate = $row2['lastleavedate'] ?? null;

    $formattedLastleavedate = $lastleavedate ? DateTime::createFromFormat('Y-m-d', $lastleavedate)->format('d-m-Y') : null;

    $employees[] = 
	[
        'empid' => $empid,
        'name' => trim($name),
        'leavecount' => $leavecount,
        'lastleavedate' => $formattedLastleavedate,
    ];
	
}

usort($employees, function ($a, $b) 
{
    if ($a['leavecount'] == $b['leavecount']) 
	{
        return strcmp($a['lastleavedate'] ?? '', $b['lastleavedate'] ?? '');
    }
    return $a['leavecount'] - $b['leavecount'];
});


$slno = 1;
$trdata = '';

foreach ($employees as $employee) 
{
    
	if ($employee['empid'] == $myempid) 
	{
		if($slno < 4)
		{
			$color = "background-color:#5bc951;";
		}
		elseif($slno > 3 && $slno < 9) 
		{
			$color = "background-color:#e3bc5b;";
		}
		else 
		{
			$color = "background-color:#c48684;";
		}		

    } 
	else 
	{
		$color = '';
	}

    $fontsize = ($employee['empid'] == $myempid) ? 'font-size:22px;' : '';
	$lastleavedate = empty($employee['lastleavedate']) ? '-' : $employee['lastleavedate'];

    $trdata .= "
        <tr class='dis' style='font-family:Poppins;padding:6px;'>
            <td align='center' style='$color $fontsize font-family:Poppins;padding:6px;'>$slno</td>
            <td align='center' style='$color $fontsize font-family:Poppins;padding:6px;'>{$employee['name']}</td>
            <td align='center' style='$color $fontsize font-family:Poppins;padding:6px;'>{$employee['leavecount']}</td>
            <td align='center' style='$color $fontsize font-family:Poppins;padding:6px;'>$lastleavedate</td>
        </tr>";
		
    $slno++;
	
}
	
		 
?>	

<div style='width:50%;min-height:560px;max-height:750px;margin-left:370;'>
<h1><b style='font-size:25px;font-family:Poppins;margin-left:200;'>Leave Rankings</b></h1>
<table class="report" style='border-radius:10px;box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);'>
<tr>
<td align=center style='background-color:#28373E;color:white;font-family:Poppins;padding:6px;'>RANK</td>
<td align=center style='background-color:#28373E;color:white;font-family:Poppins;padding:6px;'>EMPLOYEE NAME</td>
<td align=center style='background-color:#28373E;color:white;font-family:Poppins;padding:6px;'>LEAVE COUNT OF <?php echo date('Y'); ?></td>
<td align=center style='background-color:#28373E;color:white;font-family:Poppins;padding:6px;'>LAST LEAVEDATE</td>
</tr>

<?php echo $trdata; ?>

</table>
</div>
	 
<br><br>



</body>
</html>