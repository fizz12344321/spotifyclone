# Outputting error messages

In this tutorial we are going to learn how to show error messages in the registeration page.

## Layout (register.php)

In the register page add the following lines to the top of file to make sure that all the classes and functions are working as expected.

~~~~

<?php
	include("includes/classes/Account.php");
	include("includes/classes/Constants.php");

	$account = new Account(); //we have put it here, because we can call it in the **register-handler.php and login-handler.php** as well.

	include("includes/handlers/register-handler.php");
	include("includes/handlers/login-handler.php");

?>

~~~~

Then in the form components add the following lines or equivalent for the related form component.

~~~~

	<?php echo $account->getError(Constants::$usernameCharacters); ?>

~~~~

Basically this line will check if there is a error like **$usernameCharacters** in the **errorArray** which is found in the **account** object of the **Account** class. If there is then it will print this error to there.

After then your **registeration** form will be look like as follows.

~~~~

		<form id="registerForm" action="register.php" method="POST">
			<h2>Create your free account</h2>
			<p>
				<?php echo $account->getError(Constants::$usernameCharacters); ?>
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
