<?php
require_once 'user_class.php';
$user = new USER();

if(empty($_GET['id']) && empty($_GET['code']))
{
	$user->redirect('index.php');
}

if(isset($_GET['id']) && isset($_GET['code']))
{
	$id = base64_decode($_GET['id']);
	$code = $_GET['code'];
	
	$statusY = "Y";
	$statusN = "N";
	
	$stmt = $user->runQuery("SELECT userID,userStatus FROM users_table WHERE userID=:uID AND tokenCode=:code LIMIT 1");
	$stmt->execute(array(":uID"=>$id,":code"=>$code));
	$row=$stmt->fetch(PDO::FETCH_ASSOC);
	if($stmt->rowCount() > 0)
	{
		if($row['userStatus']==$statusN)
		{
			$stmt = $user->runQuery("UPDATE users_table SET userStatus=:status WHERE userID=:uID");
			$stmt->bindparam(":status",$statusY);
			$stmt->bindparam(":uID",$id);
			$stmt->execute();	
			
			$msg = "
		           <div class='alert alert-success'>
				   <button class='close' data-dismiss='alert'>&times;</button>
					  <strong>WoW !</strong>  Your Account is Now Activated : <a href='index.php'>Login here</a>
			       </div>
			       ";	
		}
		else
		{
			$msg = "
		           <div class='alert alert-error'>
				   <button class='close' data-dismiss='alert'>&times;</button>
					  <strong>sorry !</strong>  Your Account is allready Activated : <a href='index.php'>Login here</a>
			       </div>
			       ";
		}
	}
	else
	{
		$msg = "
		       <div class='alert alert-error'>
			   <button class='close' data-dismiss='alert'>&times;</button>
			   <strong>sorry !</strong>  No Account Found : <a href='user-signup.php'>Signup here</a>
			   </div>
			   ";
	}	
}

?>
<!DOCTYPE html>
<html>
<head>

	<title>Shareride | Home</title>

	<link rel="stylesheet" href="assets/css/demo.css">
	<link rel="stylesheet" href="assets/css/style.css">

</head>

	<header>
        <div class="col-md-6">
            <h1>:: Shareride Inc.</h1>
        </div>
        <div class="col-md-6">
            <p class="p-welcome" style="float: right; margin-right: 5%; color: white;"></p>
        </div>
    </header>
    <div class="main-menu">
        <div class="col-md-6 left-menu">
            <ul>
                <li><a href="index.html" class="active">Home</a></li>
                <li><a href="form-register.html" class="form-facebook-button">Ride</a></li>
                <li><a href="form-register.html" class="form-facebook-button">How it works</a></li>
            </ul>
        </div>
        <div class="col-md-6 right-menu">
            <ul>
                <li>You already have an account? <a href="index.php" class="form-facebook-button">Sign in</a></li>
            </ul>
        </div>

    </div>
    <div class="row-view">
        <div class="column-view rightone">
            <div class="option-header">
                <h2>Well done </h2>
            </div>
             <div class="option-content">
                
                <div class="error-section">
                    <?php if(isset($msg)) echo $msg;  ?>
                </div>
            </div>
            <div class="option-footer">
                <a href="index.php">Login</a>
            </div>
        </div>
    </div>

</body>

</html>