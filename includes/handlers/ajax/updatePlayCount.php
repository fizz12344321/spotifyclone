<?php

include("../../config.php"); //to get the database connection

if(isset($_POST['songId'])) {
	$songId = $_POST['songId'];

	$query = mysqli_query($con, "UPDATE songs SET plays = plays + 1 where id='$songId'");

	echo $songId;
}

?>