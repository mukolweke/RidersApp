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

//code to submit reserve ride
if(isset($_POST['reserve_button']))
{
    $rideid = $_POST['ride_id'];
    $driverid = $_POST['driver_id'];
    $riderid = $_POST['rider_id'];
    $drivername = $_POST['driver_name'];
    $origin = $_POST['origin'];
    $destination = $_POST['destination'];
    $email = $row['email'];

    if($user_home->reserve_ride($rideid,$driverid,$drivername,$riderid,$origin,$destination,$email))
        {        
            $id = $user_home->lasdID();      
            $key = base64_encode($id);
            $id = $key;

            $email = $row['email'];
            $ridid = $rideid;
            $ridername = $row['fname'] . " " . $row['lname'];

            $message = "                    
                        Hello $ridername,
                        <br /><br />
                        Welcome to Shareride Inc.!<br/>
                        To confirm your ride booking,  please , just click following link<br/>
                        <br /><br />
                        <a href='http://localhost/RidersApp/confirm_ride.php?id=$id&ride_id=$ridid&rider_email=$email'>Click here to aconfirm your booking.</a>
                        <br /><br />
                        Thanks you for being our customer,";
                        
            $subject = "Confirm Ride Booking | Shareride Inc";
                        
            $user_home->send_mail($email,$message,$subject); 
            $msg = "
                    <div class='alert alert-success'>
                        <strong>Success!</strong>  Please check your email to confirm your booking. 
                    </div>
                    ";
        }
        else
        {
            echo "sorry , Query could no execute...";
        } 

    //updating the ride status in the rides table
    $stmt = $user_home->runQuery("UPDATE riders_table set ride_status = '1' where ride_id = $rideid");
    $stmt->execute();
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
                <h2>Rides available</h2>
            </div>
             <div class="option-content">
                <table style="width: 100%; font-size: 16px; border-bottom: 1px solid #eef; padding-bottom: 10px;">
                    <tr class="table-head">
                        <td>Origin</td>
                        <td>Destination</td>
                        <td>Capacity</td>
                        <td>Driver</td>
                        <td>Operation</td>
                    </tr>      
                        <?php
                            $userid = $row['userID'];
                            $stmt1 = $user_home -> runQuery("select * from riders_table where ride_status = '0' and driver_id != $userid ");
                            $stmt1->execute();

                            while($row1 = $stmt1 -> fetch(PDO::FETCH_ASSOC))
                            {
                                $ridid = $row1['ride_id'];
                                $driverid = $row1['driver_id'];
                                $drivername = $row1['driver_name'];
                                $origin = $row1['origin'];
                                $destination = $row1['destination'];
                                echo "<tr>";
                                    echo "<td>" . $row1['origin'] . "</td>";
                                    echo "<td>" . $row1['destination'] . "</td>";
                                    echo "<td>" . $row1['capacity'] . "</td>";
                                    echo "<td>" . $row1['driver_name'] . "</td>";
                                    echo "<td>
                                            <form action='' method='POST'>
                                                <input type='hidden' name='ride_id' value='$ridid'>
                                                <input type='hidden' name='driver_id' value='$driverid'>
                                                <input type='hidden' name='rider_id' value='$userid'>
                                                <input type='hidden' name='driver_name' value='$drivername'>
                                                <input type='hidden' name='origin' value='$origin'>
                                                <input type='hidden' name='destination' value='$destination'>
                                                <button type='submit' class='reserve-button' name='reserve_button'>Reserve</button>
                                            </form>
                                          </td>";
                                echo "</tr>";
                            }
                        ?>
                </table>
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