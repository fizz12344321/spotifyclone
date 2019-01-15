# Form sanitation - Cleaning the input

In this tutorial, we are going to learn how to clean the submitted form values. Because we do not want to any user to manipulate our application with undesired components like **buttons, links** and etc.

## Sanitize Functions

We have to create some function in the **includes/handlers/register-handlers.php** to check and clean the submitted values (inputs).

So now lets create the following functions in the php file that mentioned above.

~~~~

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
}

~~~~