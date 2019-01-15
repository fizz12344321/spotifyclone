# Connection To Our Database From PHP

In this tutorial, we are going to learn how to connect database from **php**.

**Caution:** This is not the most secure method. There are other secure methods as well.

## Config.php

Now we have to create a **config.php** file to use the database configuration. So create this file in the includes folder and add the following lines.

~~~~

	ob_start(); //output buffer

	//this is useful when you want to store the times to the database
	$timezone = date_default_timezone_set("Europe/Istanbul");

	$con = mysqli_connect("localhost", "root", "123456", "slotify");

	/* Params: server, username, password, tablename */

	if(mysqli_connect_errno()) {
		echo "Failed to connect: " . mysqli_connect_errno();
	}

~~~~

## Integrate the config file

We need to integrate this **config** file in the **register.php** to use it. So lets add the following line to the top.

~~~~

	include("includes/config.php");

~~~~

Also to the **$account = new Account()** add the following parameter as well.

**$account = new Account($con)** The **$con** is coming from the **config.php** file.

## Add the parameter to the Account Constructor

To create a new account we need to use the connection that we've already created in the **config.php**. So lets add the following lines to the **Account.php**.

~~~~

	private $con; //new Line
	private $errorArray;

	public function __construct($con) {
		$this->con = $con; //new Line
		$this->errorArray = array();
	}

~~~~
