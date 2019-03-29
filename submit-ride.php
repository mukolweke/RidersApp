<?php

require_once 'user_class.php';

$submit_ride = new USER();

if( $_POST )
{
	
	$name = $_POST['driver_name'];
	$did = $_POST['driver_id'];
	$origin = $_POST['origin'];
	$destination = $_POST['destination'];
    $capacity = $_POST['capacity'];

    $submit_ride->give_ride($name,$did,$origin,$destination,$capacity);
}
?>