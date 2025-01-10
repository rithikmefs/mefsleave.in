<?php
include "connect.php";
$empid='';
if(isset($_POST['empid']))
{
	$empid=$_POST['empid'];
}

$name='';
if(isset($_POST['name']))
{
	$name=$_POST['name'];
}


if($empid!='999')
{
	$sql2 = "select name from emp with (nolock) where empid='$empid'";
	$res2 = sqlsrv_query($conn,$sql2);
	while($row2 = sqlsrv_fetch_array( $res2, SQLSRV_FETCH_ASSOC))
	{
		if($row2['name']==NULL)
		{
			$row2['name']='';
		}
		$name = trim($row2['name']);		
	}	
    echo "HELLO ".$name;
} 
?>

