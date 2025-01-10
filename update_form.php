<?php
include "connect.php";

$empid = isset($_GET['empid']) ? $_GET['empid'] : '';

if($empid=='' && isset($_POST['empid']))
{
	$empid=$_POST['empid'];
}	

$flag='Y';


	$sql2 = "SELECT * FROM [MISGlobal].[dbo].[emp] WITH (NOLOCK) WHERE empid='$empid'";
	$res2 = sqlsrv_query($conn, $sql2);
	if($res2)
	{
		$row2 = sqlsrv_fetch_array($res2, SQLSRV_FETCH_ASSOC);
	
		if($row2['name']==NULL)
		{
			$row2['name']='';
		} 
		$name = trim($row2['name']); 

		if($row2['dob']==NULL)
		{
			$row2['dob']='';
		}
		$dob = trim($row2['dob']); 

		if($row2['doj']==NULL)
		{
			$row2['doj']='';
		}
		$doj = trim($row2['doj']); 

		if($row2['address']==NULL)
		{
			$row2['address']='';
		}
		$address = trim($row2['address']); 

		if($row2['gender']==NULL)
		{
			$row2['gender']='';
		}
		$gender = trim($row2['gender']); 

		if($row2['email']==NULL)
		{
			$row2['email']='';
		}
		$email = trim($row2['email']); 

		if($row2['mob']==NULL)
		{
			$row2['mob']='';
		}
		$mob = trim($row2['mob']); 

		if($row2['designation']==NULL)
		{
			$row2['designation']='';
		}
		$designation = trim($row2['designation']); 

		if($row2['empid']==NULL)
		{
			$row2['empid']='';
		}
		$empid = trim($row2['empid']);
		
		if($row2['emptype']==NULL)
		{
			$row2['emptype']='';
		}
		$emptype  =  trim($row2['emptype']);

/* 		if($row2['flag']==NULL)
		{
			$row2['flag']='';
		}
		$flag  =  trim($row2['flag']);	 */		
	}



?>

<html lang="en">
  <head>
    <link rel="icon" href="MEFS.png">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
<style>
/* Import Google font - Poppins */
      @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap");
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Poppins", sans-serif;
        }
      
      .container 
	  {
        position: relative;
        max-width: 700px;
        width: 100%;
        background: #fff;
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
		flex: 1;
		align-items: center;
		margin-left:320px;
		margin-top:20px;
      }
	  .report
		{
			caret-color:transparent;
		}
      .container nav 
	  {
        font-size: 1.5rem;
        color: #333;
        font-weight: 600;
        text-align: center;
      }
      .container .form {
        margin-top: 30px;
      }
      .form .input-box 
	  {
        width: 100%;
        margin-top: 10px;
      }
      .input-box label 
	  {
        color: #333;
		font-weight:450;
      }
      .form :where(.input-box input, .input-box select, .input-box textarea) 
	  {
        position: relative;
        height: 40px;
        width: 100%;
        font-size: 15px;
        color: #707070;
        margin-top: 8px;
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 0 15px;
		outline-color:#ffc299;
      }
	  
      .input-box input:focus,
      .input-box select:focus 
	  {
        box-shadow: 0 1px 0 rgba(0, 0, 0, 0.1);
      }
      .form .column 
	  {
        display: flex;
        column-gap: 15px;
      }
	.error-msg
	{
		font-size:12px;
		color:	#ff3333;
	}

      .button input 
	  {
        height: 50px;
        width: 100%;
        color: #fff;
        font-size: 1rem;
        font-weight: 400;
        margin-top: 30px;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #e65c00;
      }
      .button input:hover 
	  {
        background: #ff944d;
      }
      /* Responsive code */
      @media screen and (max-width: 500px) 
	  {
        .form .column 
		{
          flex-wrap: wrap;
        }
      }
	  
	  	.error-msg
		{
			font-size:12px;
			color:	#ff3333;
	    }
</style>
</head>


<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{

	if(isset($_POST['name']))
	{
		$name = $_POST['name'];
	}

	if(isset($_POST['dob']))
	{
		$dob = $_POST['dob'];
	}

	if(isset($_POST['gender']))
	{
		$gender = $_POST['gender'];
	}

	if(isset($_POST['doj']))
	{
		$doj = $_POST['doj'];
	}

	if(isset($_POST['designation']))
	{
		$designation = $_POST['designation'];
	}

	if(isset($_POST['email']))
	{
		$email = $_POST['email'];
	}

	if(isset($_POST['mob']))
	{
		$mob = $_POST['mob'];
	}

	if(isset($_POST['emptype']))
	{
		$emptype = $_POST['emptype'];
	}

	if(isset($_POST['address']))
	{
		$address = $_POST['address'];
	}

	if(isset($_POST['flag']))
	{
		$flag = $_POST['flag'];
	}

	$sql="UPDATE [MISGlobal].[dbo].[emp] SET  name = '$name', dob = '$dob', gender = '$gender', emptype = '$emptype', designation = '$designation', doj = '$doj', mob = '$mob', email = '$email', address = '$address' WHERE empid = '$empid'";
	sqlsrv_query($conn, $sql);

}
?>



<body>
	
<?php 
include 'header2.html'; 
?>

<br> 
<section class="container">
<nav class="report">Update Employee Details</nav>
<form id="update_form" action="update_form.php" method="POST" class="form" name="update_form" onsubmit="return validateForm();">
<input type="hidden" name="empid" id="empid" value="<?php echo $empid; ?>">
<input type="hidden" name="flag" id="flag" value="<?php echo $flag; ?>">
	
	
	<div class="column">	
		
		<div class="input-box">
			<label class="report">Name</label>
			<input type="text" name="name" id="name" value="<?php echo $name; ?> "  readonly>
		</div>
		  
		<div class="input-box">
			<label class="report">Date of Birth</label>
			<input type="date" id="dob" name="dob" value="<?php echo $dob; ?>" readonly>
		</div>
	</div>


	<div class="column">		
		  <div class="input-box">
			<label class="report">Gender</label>
			<input type="text" id="gender" name="gender" value="<?php echo $gender; ?>" readonly>
		  </div>
		
		
		  <div class="input-box">
			<label class="report">Employee Type</label>
			<input type="text" id="emptype" name="emptype" value="<?php echo $emptype; ?>" readonly>
		  </div>
	</div>
				  
	
	 <div class="column">
		<div class="input-box">
		<label class="report">Designation</label>
		    <select id="designation" name="designation" >
				<option value="" disabled selected>Select type</option>
				<option value="HEAD OF OPERATIONS"  <?php if($designation=="HEAD OF OPERATIONS"){echo "selected";} ?>>Head of Operations</option>
				<option value="SOFTWARE TRAINEE"  <?php if($designation=="SOFTWARE TRAINEE"){echo "selected";} ?>>Software Trainee</option>
				<option value="JUNIOR DEVELOPER"  <?php if($designation=="JUNIOR DEVELOPER"){echo "selected";} ?>>Junior Developer</option>
				<option value="SENIOR DEVELOPER"  <?php if($designation=="SENIOR DEVELOPER"){echo "selected";} ?>>Senior Developer</option>
		    </select>
	    </div>
	  
	   <div class="input-box">
		   <label class="report">Date Of Joining</label>
		   <input type="date" name="doj"  value="<?php echo $doj; ?>" readonly>
	  </div>  
	 </div>
	
	
	<div class="column">
		<div class="input-box">
			<label class="report">Mobile</label>
			<input type="tel" id="mob" name="mob" value="<?php echo $mob; ?>">
			<span id="mobileError" class="error-msg"></span>
        </div>
  
        <div class="input-box">
			<label class="report">Email</label>
			<input id="email" name="email" value="<?php echo $email; ?>">
			<span id="emailError" class="error-msg"></span>
		</div>
	</div>
	
	<div class="input-box">
		<label class="report">Address</label>
		<input type="text" id="address" name="address" rows="3"  maxlength="80"  value="<?php echo $address; ?>">
    </div>
	
	<div class="button">
		<input type="submit" value="Update" name="Update">
		<!-- onClick = 'javascript: alert("Updated Successfully.");' -->
	</div>	


</form>
</section>
</body>
</html>


<script>

	  function validateForm() 
	  {
        var form = document.update_form;
		var mob = form.mob.value;
		var email = form.email.value;
		var isValid = true;
	
		
					 if (mob.trim() === '') 
					 {
						displayError('mobileError', 'Please enter Mobile Number');
						isValid = false;
					 } 
					 else if (!/^[0-9]{10}$/.test(mob)) 
					 {
						displayError('mobileError', 'Please enter a valid 10-digit Mobile Number');
						isValid = false;
					 } 
					 else 
					 {
						clearError('mobileError');
					 }
					
		            if (email.trim() === '') 
					{
						displayError('emailError', 'Please enter Email');
						isValid = false;
                    } 
					else if (!/^[a-z0-9]+([._][a-z0-9]+){0,2}@[a-z0-9]+\.[a-z0-9]+(\.[a-z0-9]+){0,1}\om$/.test(email)) 
					{
						displayError('emailError', 'Please enter a valid Email');
						isValid = false;
					} 
					else 
					{
						clearError('emailError');
					}

					if (isValid) 
					{
						alert('Updated Successfully');
					}
					
					return isValid;
		
	  }
	  
	  
	          function displayError(id, errmsg) 
			  {
				var disp = document.getElementById(id);
				disp.innerHTML = errmsg;
              }
			  
			  
	          function clearError(id) 
			  {
				var disp = document.getElementById(id);
				disp.innerHTML = "";
              }		  
	  
	
</script>	