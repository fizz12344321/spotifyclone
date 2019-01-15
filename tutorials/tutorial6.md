## Remember Form Values

In this tutorial, if the validation is failed then the user still will be in the same page so we are going to implement a function to put the old values of the form components.

## Put the values

In the **register.php** add the following function add the top of the file.

~~~~

	//$name is used here as field_name so that means we have to put field name here
	function getInputValue($name) {
		if(isset($_POST[$name])) { //If this field has been sent
			echo $_POST[$name];
		}
	}

~~~~

Basically, whenever we call this function in any component with the component **name** then it will show the old value.

So add the following function to the each form element as follows.

~~~~

	<p>
		<?php echo $account->getError(Constants::$firstNameCharacters); ?>
		<label for="firstName">First name</label>
		<input id="firstName" name="firstName" type="text" placeholder="e.g. Bart" value="<?php getInputValue('firstName') ?>" required>
	</p>

~~~~

After then the all form elements will look like as follows.

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

**<?php getInputValue('firstName') ?>** is the nmae of the component so whenever user press to the submit button this component value will be submitted. But if there is an error and user still in the same page then the function that we've created automatically will put this value to this component again. Because the page is not redirected to anywhere.

**Remember that if you refresh the page then the value will be gone**
