# Creating the Account class

In this tutorial, we are going to create a class called **Constants** to store the error messages that we are going to show the user if there is an error. This is kind of error message

First go to the **includes** folder and create a folder if not existed called **classes**.

## Constants Classes

Now lets create a php file called **Constants.php** inside the classes folder also Make sure that first letter of the file is **Upper Case**.

Having done so, add the following lines to this file.

~~~~

class Constants {

	public static $passwordsDoNoMatch = "Your passwords don't match";
	public static $passwordNotAlphanumeric = "Your password can only contain numbers and letters";
	public static $passwordCharacters = "Your password must be between 5 and 30 characters";
	public static $emailInvalid = "Email is invalid";
	public static $emailsDoNotMatch = "Your emails don't match";
	public static $lastNameCharacters = "Your last name must be between 2 and 25 characters";
	public static $firstNameCharacters = "Your first name must be between 2 and 25 characters";
	public static $usernameCharacters = "Your username must be between 5 and 25 characters";
}

~~~~

We are going to use those **static** variables to show the related messages to the user.