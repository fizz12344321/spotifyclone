# Sessions

In this tutorial, we are going to learn how to use **sessions** to make sure that user can logged in to the system directly without have to provide **username** and **password** again.

## Start Session

First of all, we have to start the session in the **config.php** file which is the first file that we've included in our **register.php** file.

So lets add the following line to the **config.php** file.

~~~~

	session_start();

~~~~

Then go to the **login-handler.php** and **reggister-handler.php** to add SESSION.

## Login Handler

Add the following lines.

~~~~

	if($result == true) {
		$_SESSION['userLoggedIn'] = $username;
		header("Location: index.php");
	}

~~~~

## Register Handler

Add the following lines.

~~~~

	if($wasSuccesfull == true) {
		$_SESSION['userLoggedIn'] = $username;
		header("Location: index.php"); //redirect to this page
	}

~~~~

Basically **$_SESSION['userLoggedIn'] = $username** means it will put the $username into this session and store it into the 'userLoggedIn' variable.

## Layout (index.php)

If you haven't created it before then create a file in this project folder called as **index.php**.

In the index.php add the following lines to be sure that just only logged in users can see this page otherwise redirect them to the **register.php**.

~~~~

<?php
	include("includes/config.php");


	if(isset($_SESSION['userLoggedIn'])) {
		$userLoggedIn = $_SESSION['userLoggedIn'];
	}
	else {
		header("Location: register.php");
	}

?>

<html>
	<head>
		<title>Welcome to Slotify!</title>
	</head>

	<body>
		Hello!
	</body>

</html>

~~~~



