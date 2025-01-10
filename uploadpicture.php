<?php
session_start();
$imagePath = '';
$_SESSION['profile_image'] = $imagePath;
?>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
<script>
localStorage.setItem('profileImagePath', '<?php echo $imagePath; ?>');
</script>
<?php

if (isset($_POST['upload']) && isset($_FILES['profile_image'])) 
{
    $empid = $_POST['empid'];
    $targetDir = '../leave/dp/';

    $fileName = '';
    if ($empid == '1044') 
	{
        $fileName = 'jinoy.png';
    } 
	elseif ($empid == '1068') 
	{
        $fileName = 'anandhu.png';
    } 
	elseif ($empid == '1001')
	{
        $fileName = 'anurag.png';
    } 
	elseif ($empid == '1071') 
	{
        $fileName = 'vyshnav.png';
    } 
	elseif ($empid == '1003') 
	{
        $fileName = 'rithik.png';
    } 
	elseif ($empid == '1004') 
	{
        $fileName = 'sarath.png';
    } 	
	elseif ($empid == '1000') 
	{
        $fileName = 'sanoop.png';
    } 
	elseif ($empid == '1091') 
	{
        $fileName = 'akhil.png';
    } 
	elseif ($empid == '1002') 
	{
        $fileName = 'anna.png';
    } 	
	elseif ($empid == '1070') 
	{
        $fileName = 'manju.png';
    } 
	elseif ($empid == '1118') 
	{
        $fileName = 'test1.png';
    } 
	elseif ($empid == '1119') 
	{
        $fileName = 'test2.png';
    } 	
	elseif ($empid == '999') 
	{
		$fileName = 'arun.png';
	}
	elseif ($empid == '1108') 
	{
		$fileName = 'rajagopal.png';
	}
	else 
	{
	    ?>    
		<br><br>
		<div style='background-color:#FFFFFF;border-radius:10px;width:35%;margin-left:400;height:180px;box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);'>
		<?php
        echo "<br><br><p style='font-family:Poppins,sans-serif;font-weight:bold;font-size:18px;text-align:center;'>No matching profile image found for Emp ID - $empid</p>";
		echo "<br><p style='font-family: Poppins, sans-serif; font-weight: bold;text-align:center;'><a href='viewprofile.php'>Back</a></p>";
        exit;
		?>
		</div>
		<?php		

    }

    $targetFile = $targetDir . $fileName;

    if ($_FILES['profile_image']['error'] === UPLOAD_ERR_OK) 
	{
        $allowedTypes = ['image/jpeg', 'image/png'];
        if (!in_array($_FILES['profile_image']['type'], $allowedTypes)) 
		{
	    ?>    
		<br><br>
		<div style='background-color:#FFFFFF;border-radius:10px;width:35%;margin-left:400;height:180px;box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);'>
		<?php
            echo "<br><br><p style='font-family:Poppins,sans-serif;font-weight:bold;font-size:18px;text-align:center;'>Only JPG and PNG formats are allowed</p>";
			echo "<br><p style='font-family: Poppins, sans-serif; font-weight: bold;text-align:center;'><a href='viewprofile.php'>Back</a></p>";
			exit;
		?>
		</div>
		<?php
            
        }

        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetFile)) 
		{
		$_SESSION['profile_image'] = $targetFile;	
        ?>    
		<br><br>
		<div style='background-color:#FFFFFF;border-radius:10px;width:35%;margin-left:400;height:180px;box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);'>

		<?php
		echo "<br><br><p style='font-family:Poppins,sans-serif;font-weight:bold;font-size:18px;text-align:center;'>Profile picture updated successfully!</p>";
		//header("Location: viewprofile.php?upload=success");
        //exit();
		echo "<br><p style='font-family: Poppins, sans-serif; font-weight: bold;text-align:center;'><a href='viewprofile.php' onclick='refreshMainPage();'>Back</a></p>";
		?>
		<script>
		function refreshMainPage() 
		{
			window.open('main.php', '_self');
		}
		</script>
		</div>
		<?php
        }
		
		else 
		{
	    ?>    
		<br><br>
		<div style='background-color:#FFFFFF;border-radius:10px;width:35%;margin-left:400;height:180px;box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);'>
		<?php
			echo "<br><br><p style='font-family:Poppins,sans-serif;font-weight:bold;font-size:18px;text-align:center;'>Error moving the uploaded file</p>";
			echo "<br><p style='font-family: Poppins, sans-serif; font-weight: bold;text-align:center;'><a href='viewprofile.php'>Back</a></p>";
		?>
		</div>
		<?php
            
        }
		
    } 
	else 
	{
	    ?>    
		<br><br>
		<div style='background-color:#FFFFFF;border-radius:10px;width:35%;margin-left:400;height:180px;box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);'>

		<?php
			echo "<br><br><p style='font-family:Poppins,sans-serif;font-weight:bold;font-size:18px;text-align:center;'>File upload error. Error code: </p>" . $_FILES['profile_image']['error'];
			echo "<br><p style='font-family: Poppins, sans-serif; font-weight: bold;text-align:center;'><a href='viewprofile.php'>Back</a></p>";
		?>
		</div>
		<?php		
        
    }
} 
else 
{
	    ?>    
		<br><br>
		<div style='background-color:#FFFFFF;border-radius:10px;width:35%;margin-left:400;height:180px;box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);'>
		<?php
		echo "<br><br><p style='font-family:Poppins,sans-serif;font-weight:bold;font-size:18px;text-align:center;'>Please select an image to upload</p>";
		echo "<br><p style='font-family: Poppins, sans-serif; font-weight: bold;text-align:center;'><a href='viewprofile.php'>Back</a></p>";
		?>
		</div>
		<?php		
    
}
?>

