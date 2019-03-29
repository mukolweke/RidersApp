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
        <div class="column-view leftone">
            <div class="rider-options">
                <div class="optionone optione1">
                    <div class="option-header">
                        <h2>My Account</h2>
                    </div>
                    <div class="option-content">
                        <table class="profile-table" style="width: 100%;">
                            <tr>
                                <th style="font-weight: lighter;">Name:</td>
                                <td><?php echo $row['fname'] . ' ' .$row['lname'] ?></td>
                            </tr>
                            <tr>
                                <th style="font-weight: lighter;">Email:</td>
                                <td><?php echo $row['email'] ?></td>
                            </tr>
                            <tr>
                                <th style="font-weight: lighter;">Phone:</td>
                                <td><?php echo $row['phone'] ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="rider-options">
                <div class="optionone optione2">
                    <div class="option-header">
                        <h3>Give a ride</h3>
                    </div>
                    <div class="option-content">
                        <p>Have space to give rides?</p>
                    </div>
                    <div class="option-footer">
                        <a href="give-ride.php">Give a ride</a>
                    </div>
                </div>
                <div class="optionone optione1">
                    <div class="option-header">
                        <h2>Find a ride</h2>
                    </div>
                    <div class="option-content">
                        <?php
                            $userid = $row['userID'];
                            $stmt1 = $user_home -> runQuery("select * from riders_table where ride_status = '0' and driver_id != $userid");
                            $stmt1->execute();

                            $rowcount = $stmt1 -> rowCount();
                        ?>
                        <p> <?php echo $rowcount; ?> Rides available</p>
                    </div>
                    <div class="option-footer">
                        <div class="option-footer">
                            <a href="all-rides.php">View Rides</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="column-view rightone">
            <div class="option-header">
                <h2>Reserved rides</h2>
            </div>
             <div class="option-content">
                <table class="table-confirmed">    
                        <tr class="table-head">
                            <td>Origin</td>
                            <td>Destination</td>
                            <td>Driver</td>
                            <td>Status</td>
                        </tr>
                        <?php
                            $rider_id = $row['userID'];
                            $stmt1 = $user_home -> runQuery("select * from reserved_rides where rider_id = $rider_id");
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
                                    echo "<td>" . $row1['driver_name'] . "</td>";
                                    echo "<td>" . $stts . "</td>";
                                echo "</tr>";
                            }
                        ?>
                </table>
            </div>
            <div class="option-footer">
                <a href="reserved-rides.php">View All</a>
            </div>

        </div>
    </div>

</body>

</html>