# Creating the Account class

In this tutorial, we are going to create a class called **Account** to use for **login** and **registeration** functionality.

First go to the **includes** folder and create a folder if not existed called **classes**.

## Account Classes

Now lets create a php file called **Account.php** inside the classes folder also Make sure that first letter of the file is **Upper Case**.

Having done so, add the following lines to this file.

~~~~

	class Account {

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
		}

~~~~

We are going to use **register()** function to create a new account for the users. But first we have to validate the sended values by user.

If there is an error we have to send an error message to user back to show them.