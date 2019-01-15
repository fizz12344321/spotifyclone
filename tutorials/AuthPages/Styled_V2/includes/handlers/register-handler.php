<?php 

function sanitizeFormPassword($inputText) {
	$inputText = strip_tags($inputText);
	return $inputText;
}

function sanitizeFormUsername($inputText) {
	$inputText = strip_tags($inputText);
	$inputText = str_replace(" ", "", $inputText);
	return $inputText;
}

function sanitizeFormString($inputText) {
	$inputText = strip_tags($inputText); //delete all html tags that submitted in form
	$inputText = str_replace(" ", "", $inputText); //replace or empty strings
	$inputText = ucfirst(strtolower($inputText)); //make first letter uppercase and rest will be lower case thats why we've used strtolower
	return $inputText;
}

if(isset($_POST['registerButton'])) { //If 
	//Register button was pressed
	$username = sanitizeFormUsername($_POST['username']);
	$firstName = sanitizeFormString($_POST['firstName']);
	$lastName = sanitizeFormString($_POST['lastName']);
	$email = sanitizeFormString($_POST['email']);
	$email2 = sanitizeFormString($_POST['email2']);
	$password = sanitizeFormPassword($_POST['password']);
	$password2 = sanitizeFormPassword($_POST['password2']);

	//Validation -> send those variables to the register function in the account class
	$wasSuccesfull = $account->register($username,$firstName,$lastName,$email,$email2,$password,$password2);

	if($wasSuccesfull == true) {
		$_SESSION['userLoggedIn'] = $username;
		header("Location: index.php"); //redirect to this page
	}
}

?>