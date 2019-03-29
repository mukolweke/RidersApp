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
                            <li><a href="give-ride.php" class="form-facebook-button">Given Rides</a></li>
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
                <h2>Given rides</h2>
            </div>
             <div class="option-content">
                <table style="width: 100%; font-size: 16px; border-bottom: 1px solid #eef; padding-bottom: 10px;">
                    <tr class="table-head">
                        <td>Origin</td>
                        <td>Destination</td>
                        <td>Booked By:</td>
                        <td>Status</td>
                    </tr>
                        <?php
                            $driver_id = $row['userID'];
                            $stmt1 = $user_home -> runQuery("select * from reserved_rides where driver_id = $driver_id");
                            $stmt1->execute();

                            while($row1 = $stmt1 -> fetch(PDO::FETCH_ASSOC))
                            {
                                $ridid = $row1['ride_id'];
                                if($row1['book_status'] == 0)
                                    {
                                        $stts = "NC";
                                    }
                                    else if($row1['book_status'] == 1)
                                    {
                                        $stts = "C";
                                    }
                                    else
                                    {
                                        $stts = "X";
                                    }  
                                    echo "<tr>";
                                    echo "<td>" . $row1['origin'] . "</td>";
                                    echo "<td>" . $row1['destination'] . "</td>";
                                    echo "<td>" . $row1['rider_email'] . "</td>";
                                    echo "<td>" . $stts . "</td>";
                                echo "</tr>";
                            }
                        ?>
                </table>
            </div>
            <div class="option-content">
                <p> Note: NC: Not Confirmed, C: Confirmed X: Cancelled</p>
            </div>
            <div class="option-footer">
                <a href="user-account.php">Back</a>
            </div>
        </div>
    </div>

</body>

</html>