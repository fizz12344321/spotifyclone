# Overview of our MySQL table Structure

In this tutorial, we are going to see the table definition and columns in our database. To create those table go to **localhost/phpMyAdmin**.

| users      | Type				  |
| ---------- | ------------------ |
| id         | int - PRIMARY KEY  |
| username   | varchar(25) 		  |
| firstName  | varchar(50)        |
| email      | varchar(200)       |
| password   | varchar(32)        |
| signUpDate | datetime           |
| profilePic | varchar(500)       |

| artists    | Type				  |
| ---------- | ------------------ |
| id         | int - PRIMARY KEY  |
| name       | varchar(50) 		  |

| genres     | Type				  |
| ---------- | ------------------ |
| id         | int - PRIMARY KEY  |
| name       | varchar(50) 		  |

| songs       | Type			   |
| ----------  | ------------------ |
| id          | int - PRIMARY KEY  |
| title       | varchar(250) 	   |
| artist      | int(11)            |
| album       | int(11)            |
| genre       | int(11)            |
| duration    | varchar(8)         |
| path        | varchar(500)       | 
| albumOrders | int(11)            |
| plays       | int(11)            | 

| albums       | Type				|
| ----------   | ------------------ |
| id           | int - PRIMARY KEY  |
| title        | varchar(255) 		|
| artist       | int(11)            |
| genre        | int(11)            |
| artworkPath  | varchar(500)       |

