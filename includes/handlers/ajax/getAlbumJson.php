<?php

include("../../config.php"); //to get the database connection

if(isset($_POST['albumId'])) {
	$albumId = $_POST['albumId'];

	$query = mysqli_query($con, "SELECT * FROM albums where id='$albumId'");

	$resultArray = mysqli_fetch_array($query); //to return it to the array

	echo json_encode($resultArray); //to show the array as JSON aRRAY
}

?>