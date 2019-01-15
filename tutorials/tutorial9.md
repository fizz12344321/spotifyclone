# Inserting User Details

In this tutorial, we are going to learn how to insert user data to the database.

**CAUTION:** This is not the most secure way to insert data to the database. There are any other methods as well.

## Check Errors

Before inserting the user data to the database, we have to check if there is an error or not. As you remember, before we've created many validations. So now lets add the following lines to the **register()** function in the **Account.php**.

~~~~

		//$this means in the instance of this class
		public function register($un, $fn, $ln, $em, $em2, $pw, $pw2) {
			$this->validateUsername($un);
			$this->validateFirstName($fn);
			$this->validateLastName($ln);
			$this->validateEmails($em, $em2);
			$this->validatePasswords($pw, $pw2);

			//If there is not error in validation //New Lines
			if(empty($this->errorArray) == true) {
				//Insert into db
				return $this->insertUserDetails($un, $fn, $ln, $em, $pw);
			} else {
				return false;
			}
		}

~~~~
If there is no error then we will call the **insertUserDetails()** function to insert the user data.

So now lets create this function as well.

## InsertUserDetails Function

~~~~

		private function insertUserDetails($un, $fn, $ln, $em, $pw) {
			$encryptedPw = md5($pw); //md5 is simplest one for beginners to start
			$profilePic = "assets/images/profile-pics/head_emerald.png"; //default one
			$date = date("Y-m-d");

			$result = mysqli_query($this->con, "INSERT INTO users VALUES ('', '$un', '$fn', '$ln', '$em', '$encryptedPw', '$date', '$profilePic')");

			return $result; //return true or false
		}

~~~~

## Assets Folder

We have to create an assets folder under this project folder to put the images. So lets create an assets folder and inside this folder create another folder called **profile-pics** and add the following image to this folder with a name **head_emerald.png** or you can put any name that you would like to do but do not forget to change **profilePic** variable in the **insertUserDetails()** function.

## Last Remaining Validations

Of course we need to validate if the username is already taken or the email is already been used.

So add the following lines to the **validateUsername()** function.

~~~~

		//Check the length of the username sent
		private function validateUsername($un) {

			if(strlen($un) > 25 || strlen($un) < 5) {
				array_push($this->errorArray, Constants::$usernameCharacters);
				return;
			}

			$checkUsernameQuery = mysqli_query($this->con, "SELECT username FROM users WHERE username='$un'");
			if(mysqli_num_rows($checkUsernameQuery) != 0) {
				array_push($this->errorArray, Constants::$usernameTaken);
				return;
			}
		}

~~~~

After that, we have to add other lines as well to the **validateEmails()** function as well.

~~~~

		private function validateEmails($em, $em2) {
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

			$checkEmailQuery = mysqli_query($this->con, "SELECT email FROM users WHERE email='$em'");
			if(mysqli_num_rows($checkEmailQuery) != 0) {
				array_push($this->errorArray, Constants::$emailTaken);
				return;
			}
		}

~~~~

And as you can see, we have added two new errors as like as **usernameTaken** and **emailTaken**.

## Constants Class

In the constants class add the following lines as well for the new errors.

~~~~

	public static $usernameCharacters = "Your username must be between 5 and 25 characters";
	public static $usernameTaken = "This username already exists";

~~~~

## Layout (register.php)

In the **register.php** we have to show new errors as well. So lets add following new lines to the **registeration** form.

~~~~

		<form id="registerForm" action="register.php" method="POST">
			<h2>Create your free account</h2>
			<p>
				<?php echo $account->getError(Constants::$usernameCharacters); ?>
				<?php echo $account->getError(Constants::$usernameTaken); ?>
				<label for="username">Username</label>
				<input id="username" name="username" type="text" placeholder="e.g. bartSimpson" value="<?php getInputValue('username') ?>" required>
			</p>

			<p>
				<?php echo $account->getError(Constants::$firstNameCharacters); ?>
				<label for="firstName">First name</label>
				<input id="firstName" name="firstName" type="text" placeholder="e.g. Bart" value="<?php getInputValue('firstName') ?>" required>
			</p>

			<p>
				<?php echo $account->getError(Constants::$lastNameCharacters); ?>
				<label for="lastName">Last name</label>
				<input id="lastName" name="lastName" type="text" placeholder="e.g. Simpson" value="<?php getInputValue('lastName') ?>" required>
			</p>

			<p>
				<?php echo $account->getError(Constants::$emailsDoNotMatch); ?>
				<?php echo $account->getError(Constants::$emailInvalid); ?>
				<?php echo $account->getError(Constants::$emailTaken); ?>
				<label for="email">Email</label>
				<input id="email" name="email" type="email" placeholder="e.g. bart@gmail.com" value="<?php getInputValue('email') ?>" required>
			</p>

			<p>
				<label for="email2">Confirm email</label>
				<input id="email2" name="email2" type="email" placeholder="e.g. bart@gmail.com" value="<?php getInputValue('email2') ?>" required>
			</p>

			<p>
				<?php echo $account->getError(Constants::$passwordsDoNoMatch); ?>
				<?php echo $account->getError(Constants::$passwordNotAlphanumeric); ?>
				<?php echo $account->getError(Constants::$passwordCharacters); ?>
				<label for="password">Password</label>
				<input id="password" name="password" type="password" placeholder="Your password" required>
			</p>

			<p>
				<label for="password2">Confirm password</label>
				<input id="password2" name="password2" type="password" placeholder="Your password" required>
			</p>

			<button type="submit" name="registerButton">SIGN UP</button>
			
		</form>

~~~~
