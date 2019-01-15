# Create The Users Table

In this tutorial, we are going to see the table definition and columns of the **users**. To create this table go to **localhost/phpMyAdmin**.

| users      | Type				  |
| ---------- | ------------------ |
| id         | int - PRIMARY KEY  |
| username   | varchar(25) 		  |
| firstName  | varchar(50)        |
| email      | varchar(200)       |
| password   | varchar(32)        |
| signUpDate | datetime           |
| profilePic | varchar(500)       | 

In the **password** field we've used **32** characters lengths because later we will encrypt the password to insert to the database and the password will become 32 characters lengths.