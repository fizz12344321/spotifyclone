# Login Handler

In this tutorial, we are going to learn how to logged in.

## Login Handler

In the **includes/handlers/login-handler.php** add the following lines to be sure that we can logged in to the system.

~~~~

	if(isset($_POST['loginButton'])) {
		//Login button was pressed
		$username = $_POST['loginUsername'];
		$password = $_POST['loginPassword'];

		$result = $account->login($username, $password);

		if($result == true) {
			header("Location: index.php");
		}
	}

~~~~

Basically whenever the **loginButton** is pressed or clicked then this **if** statement will run and call the **login** function in the **account** which is the instance object of **Account** class.

## Login Function

In the **Account.php** we have to create a function called **login()** to check the **username** and **pasword** that the user sent to the server. If they are valid values then we will return **true** which means **succesfully logged in**.

So add the following function as provided below.

~~~~

		public function login($un, $pw) {

			$pw = md5($pw);

			$query = mysqli_query($this->con, "SELECT * FROM users WHERE username='$un' AND password='$pw'");

			if(mysqli_num_rows($query) == 1) {
				return true;
			}
			else {
				array_push($this->errorArray, Constants::$loginFailed);
				return false;
			}

		}

~~~~

## Show Error

In the **register.php** which we are also using **login** form inside this file as well, we have to show the if the **username** and **password** is not true or both. So lets add the following line to the **loginForm** to make sure that the error is shown.

~~~~

		<form id="loginForm" action="register.php" method="POST">
			<h2>Login to your account</h2>
			<p>
				<?php echo $account->getError(Constants::$loginFailed); ?>
				<label for="loginUsername">Username</label>
				<input id="loginUsername" name="loginUsername" type="text" placeholder="e.g. bartSimpson" required>
			</p>
			<p>
				<label for="loginPassword">Password</label>
				<input id="loginPassword" name="loginPassword" type="password" placeholder="Your password" required>
			</p>

			<button type="submit" name="loginButton">LOG IN</button>
			
		</form>

~~~~