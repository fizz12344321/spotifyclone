<?php

include("../../config.php"); //to get the database connection

if(isset($_POST['songId'])) {
	$songId = $_POST['songId'];

	$query = mysqli_query($con, "SELECT * FROM songs where id='$songId'");

	$resultArray = mysqli_fetch_array($query); //to return it to the array

	echo json_encode($resultArray); //to show the array as JSON aRRAY
}

?>