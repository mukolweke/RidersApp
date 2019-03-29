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

//fetch the rides

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
        <div class="column-view">
            
            <div class="rider-options">
                <div class="optionone optione1">
                    <div class="option-header">
                        <h3>Given rides:</h3>
                    </div>
                    <div class="option-content">
                        
                        <table style="width: 100%; margin-bottom: 20px;">
                            <tr class="table-head">
                                <td>Origin</td>
                                <td>Destination</td>
                                <td>Status</td>
                            </tr>
                        <?php
                            $driverid = $row['userID'];
                            $stmt1 = $user_home -> runQuery("select * from riders_table where driver_id = $driverid");
                            $stmt1->execute();

                            while($row1 = $stmt1 -> fetch(PDO::FETCH_ASSOC))
                            {
                                $ridid = $row1['ride_id'];
                                if($row1['ride_status'] == 0)
                                    {
                                        $stts = "NB";
                                    }
                                    else if($row1['ride_status'] == 1)
                                    {
                                        $stts = "B";
                                    }
                                    else
                                    {
                                        $stts = "X";
                                    }  
                                echo "<tr>";
                                    echo "<td>" . $row1['origin'] . "</td>";
                                    echo "<td>" . $row1['destination'] . "</td>";
                                    echo "<td>" . $stts . "</td>";
                                echo "</tr>";
                            }
                        ?>
                        </table>
                    </div>
                    <div class="option-content">
                        <p> Note: NB: Not Booked, B: Booked X: Cancelled</p>
                    </div>
                    <div class="option-footer">
                        <a href="user-account.php">Back</a>
                    </div>
                </div>
                <div class="optionone optione1">
                    <div class="option-header">
                        <h2>Give a Ride</h2>
                    </div>
                    <div class="option-content">
                        <form class="" method="post" action="submit-ride.php">

                            <div class="left-section">

                                <div class="form-white-background1">
                                    <input type="hidden" name="driver_name" value="<?php echo $row['fname'] . ' ' .$row['lname']?>">
                                    <input type="hidden" name="driver_id" value="<?php echo $row['userID']?>">
                                    <div class="form-title-row">
                                        <div class="error-section">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <select name="origin" class="select-input" style="border: 1px solid #eef; padding: 6px 10px 5px 10px; color: #006666;" required>
                                            <option>Enter Origin</option>
                                            <?php
                                                  
                                                $stmt1 = $user_home -> runQuery("select ward from locations");
                                                $stmt1 -> execute();

                                                while($row1 = $stmt1 -> fetch(PDO::FETCH_ASSOC))
                                                {
                                                    $ward = $row1['ward'];
                                                    echo "<option value='$ward'>" . $row1['ward'] . "</option>";
                                                }
                                            ?>  
                                        </select> 
                                        </label> 
                                    </div>   
                                    <div class="form-row">
                                        <label>
                                        <select name="destination" class="select-input" style="border: 1px solid #eef; padding: 6px 10px 5px 10px; color: #006666;" required>
                                            <option>Enter Origin</option>
                                            <?php
                                                  
                                                $stmt1 = $user_home -> runQuery("select ward from locations");
                                                $stmt1 -> execute();

                                                while($row1 = $stmt1 -> fetch(PDO::FETCH_ASSOC))
                                                {
                                                    $ward = $row1['ward'];
                                                    echo "<option value='$ward'>" . $row1['ward'] . "</option>";
                                                }
                                            ?>  
                                        </select> 
                                        </label> 
                                    </div> 

                                    <div class="form-row">
                                        <label>
                                            <input type="number" name="capacity" placeholder="CAPACITY" required>
                                        </label>
                                    </div>

                                    <div class="form-row">
                                        <button type="submit">Give</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
<script src="assets/jquery-1.12.4-jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {  
    
    // submit form using $.ajax() method
    
    $('#give-ride').submit(function(e){
        
        e.preventDefault(); // Prevent Default Submission
        
        $.ajax({
            url: 'submit-ride.php',
            type: 'POST',
            data: $(this).serialize() // it will serialize the form data
        })
        .done(function(data)
        {
            $('#form-content').fadeOut('slow', function(){
                $('#form-content').fadeIn('slow').html(data);
            });
            alert('Success ...'); 
        })
        .fail(function()
        {
            alert('Ajax Submit Failed ...');    
        });
    });
});
</script>
</body>

</html>