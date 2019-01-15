<?php

include("../../config.php"); //to get the database connection

if(isset($_POST['artistId'])) {
	$artistId = $_POST['artistId'];

	$query = mysqli_query($con, "SELECT * FROM artists where id='$artistId'");

	$resultArray = mysqli_fetch_array($query); //to return it to the array

	echo json_encode($resultArray); //to show the array as JSON aRRAY
}

?>