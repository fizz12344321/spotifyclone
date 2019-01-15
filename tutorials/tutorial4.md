# Validation Values

In this tutorial, we are going to learn how to validate the sended values.

As you remember in the **Account.php** we've already created a function called **register()** and in this function we have called some function using **$this->**. So for remember, lets check it out again.

~~~~

		private $errorArray;

		public function __construct() {
			$this->errorArray = array();
		}

		//$this means in the instance of this class
		public function register($un, $fn, $ln, $em, $em2, $pw, $pw2) {
			$this->validateUsername($un);
			$this->validateFirstName($fn);
			$this->validateLastName($ln);
			$this->validateEmails($em, $em2);
			$this->validatePasswords($pw, $pw2);

			if(empty($this->errorArray) == true) {
				return true;
			} else {
				return false;
			}
		}

~~~~

## Handler (register-handler.php)

In the register-handler.php we have to add the following lines to the **if(isset($_POST['registerButton']))** to validate the sended values.

~~~~

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
		header("Location: index.php"); //redirect to this page
	}
}

~~~~

So basically, **$account->register** will call the the **register** function in the **account** object which is the instance of the **Account** class and then it will validate those values.


## Validation (Username)

First of all, we have to create a function called as **validateUsername()** and add the following lines to it for validate the username.

~~~~

		//Check the length of the username sent
		private function validateUsername($un) {

			if(strlen($un) > 25 || strlen($un) < 5) {
				array_push($this->errorArray, Constants::$usernameCharacters);
				return;
			}

			//TODO:: check if username exists
		}

~~~~

This function will check whether the username length is greather than 5 and less than 25 otherwise that means its cannot be and we have to put a error message to the **errorArray** that we already created in the **Account Class**.

The error messages will be getted from the **Constants** class with using following code **Constants::$usernameCharacters**.

## Validation (FirstName)

~~~~

		private function validateFirstName($fn) {

			if(strlen($un) > 25 || strlen($un) < 2) {
				array_push($this->errorArray, Constants::$firstNameCharacters);
				return;
			}
		}

~~~~

## Validation (LastName)

~~~~

		private function validateLastName($ln) {

			if(strlen($un) > 25 || strlen($un) < 2) {
				array_push($this->errorArray, Constants::$lastNameCharacters);
				return;
			}

		}

~~~~

## Validation (Emails)

~~~~

		private function validateEmails($ems, $ems2) {
			if($em != $em2) {
				array_push($this->errorArray, Constants::$emailsDoNotMatch);
				return;
			}

			//check the format if it is an email or not
			if(!filter_var($em, FILTER_VALIDATE_EMAIL)){
				array_push($this->errorArray, Constant::$emailInvalid);
				return;
			}

			/* In the html side type="email" just checks if the user has put @ or not */

			//TODO:: Check that username hasn't aldready been used
		}

~~~~

## Validation (Passwords)

~~~~

		private function validatePasswords($pw, $pw2) {

			if($pw != $pw2) {
				array_push($this->errorArray, Constants::$passwordsDoNoMatch);
				return;
			}

			//Regext it checks whether that password is include other characters than numbers and letters, if so gives an error
			if(preg_match('/[^A-Za-z0-9]/', $pw)) {
				array_push($this->errorArray, Constants::$passwordNotAlphanumeric);
				return;
			}

			if(strlen($pw) > 30 || strlen($pw) < 5) {
				array_push($this->errorArray, Constants::$passwordCharacters);
				return;
			}
		}

~~~~

