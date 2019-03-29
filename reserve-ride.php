<?php

require_once 'user_class.php';

$reserve_ride = new USER();

if( $_POST )
{
	
	$rideid = $_POST['ride_id'];
	$driverid = $_POST['driver_id'];
	$riderid = $_POST['rider_id'];
	$drivername = $_POST['driver_name'];
	$origin = $_POST['origin'];
	$destination = $_POST['destination'];

    $reserve_ride->reserve_ride($rideid,$driverid,$drivername,$riderid,$origin,$destination);

    //updating the ride status in the rides table
    $stmt = $reserve_ride->runQuery("UPDATE riders_table set ride_status = '1' where ride_id = $rideid");
    $stmt->execute();
}
?>