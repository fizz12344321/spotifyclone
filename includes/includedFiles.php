<?php

//If the request sent via ajax then this code will be executed (Every ajax request include HTTP_X_REQUESTED_WITH)
if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {

	//Loads all of those files again 
	include("includes/config.php");
	include("includes/classes/Artist.php");
	include("includes/classes/Album.php");
	include("includes/classes/Song.php");
} else {

	//If user manually writes some url then header and footer must be includes
	include("includes/header.php");
	include("includes/footer.php");

	//Then we have to sent an ajax request to load the rest of the page
	$url = $_SERVER['REQUEST_URI'];
	echo "<script>openPage('$url')</script>";
	exit();
}