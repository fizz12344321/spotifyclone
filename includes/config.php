<?php
	ob_start(); //output buffer
	session_start();

	//this is useful when you want to store the times to the database
	$timezone = date_default_timezone_set("Europe/Istanbul");

	$con = mysqli_connect("localhost", "root", "123456", "slotify");

	/* Params: server, username, password, tablename */

	if(mysqli_connect_errno()) {
		echo "Failed to connect: " . mysqli_connect_errno();
	}

?>