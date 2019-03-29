<?php
require_once 'user_class.php';
$user = new USER();

if(empty($_GET['id']) && empty($_GET['ride_id']))
{
	$user->redirect('index.php');
}

if(isset($_GET['id']) && isset($_GET['ride_id']))
{
	$id = base64_decode($_GET['id']);
	$ridid = $_GET['ride_id'];
	
	$book_statusc = 1;
	$book_statusn = 0;
	
	$stmt = $user->runQuery("SELECT ride_id,book_status FROM reserved_rides WHERE ride_id=:ride_id AND id = :id LIMIT 1");
	$stmt->execute(array(":ride_id"=>$ridid, ":id"=>$id));
	$row=$stmt->fetch(PDO::FETCH_ASSOC);
	if($stmt->rowCount() > 0)
	{
		if($row['book_status']==$book_statusn)
		{
			$stmt = $user->runQuery("UPDATE reserved_rides SET book_status=:book_status WHERE ride_id=:ride_id AND id = :id");
			$stmt->bindparam(":book_status",$book_statusc);
			$stmt->bindparam(":ride_id",$ridid);
			$stmt->bindparam(":id",$id);
			$stmt->execute();	
			
			$msg = "
		           <div class='alert alert-success'>
					  <strong>WoW !</strong>  Thank you for confirming your ride : <a href='user-account'>Click here to go home</a>
			       </div>
			       ";	
		}
		else
		{
			$msg = "
		           <div class='alert alert-error'>
					  <strong>sorry !</strong>  Your ride is already confirmed : <a href='user_account.php'>Click here to go home</a>
			       </div>
			       ";
		}
	}
	else
	{
		$msg = "
		       <div class='alert alert-error'>
			   <strong>sorry !</strong>  No Account Found : <a href='index.php'>Signup here</a>
			   </div>
			   ";
	}	
}

?>
<?php
session_start();
require_once 'user_class.php';

$user_home = new USER();

if(!$user_home->is_logged_in())
{
    $user_home->redirect('index.php');
}

$stmt = $user_home->runQuery("SELECT * FROM users_table WHERE userID=:uid");
$stmt->execute(array(":uid"=>$_SESSION['userSession']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
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
            <p class="p-welcome" style="float: right; margin-right: 5%; color: white;">Welcome: <?php echo $row['fname']. ' ' .$row['lname']; ?></p>
        </div>
    </header>
    <div class="main-menu">
        <div class="col-md-6 left-menu">
            <ul>
                <li><a href="user-account.php">Home</a></li>
                <li><a href="give-ride.php" class="form-facebook-button">Give ride</a></li>
                <li><a href="all-rides.php" class="form-facebook-button">Find ride</a></li>
            </ul>
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                    <div class="menu-left">
                        <ul>
                            <li><a href="given-rides.php" class="form-facebook-button">Given Rides</a></li>
                            <li><a href="reserved-rides.php" class="form-facebook-button">Reserved rides</a></li>
                            <li><a href="logout.php" class="form-facebook-button">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
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
                <a href="user-account.php">Back</a>
            </div>
        </div>
    </div>

</body>

</html>