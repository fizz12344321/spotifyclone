## Music Player

In this tutorial, we are going to learn how to program the **Music Player** for our application.

## Introduction to HTML5 Audio

In this tutorial, we are going to learn how to play a music using just **JavaScript**.

### Audio Class

Now, we have to create a JavaScript class called **Audio**. So, Lets create a file in the **assets/js** as **script.js** which we will be used to write our scripts on this file.

Then add the following lines to this file.

~~~~

function Audio() {

	this.currentlyPlaying;
	this.audio = document.createElement('audio');

	this.setTrack = function(src) {
		this.audio.src = src; //to put the path of the track to the **audio** variable.
	}
}

~~~~

Basically, this is how we define class in the JavaScript.

**this.currentlyPlaying** actually refers to **public $currentlyPlaying** in the php. Thats how we define variables in the class in the JavaScript.

### Example Usage

First of all add the path of this **script.js** to the **header.php** file.

In the header.php file go to **head** tags and add the following lines.

~~~~

<head>
	<title>Welcome to Slotify!</title>

	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<script src="assets/js/script.js"></script> <!-- we've already added this script file to header.php -->
</head>

~~~~

To understand how does this **Audio** class is working follow the examples as defined below.

1. Go to anywhere in the **header.php** and add the lines below.

~~~~

<script>

	var audioElement = new Audio(); //Create an instance (object) from the Audio class
	audioElement.setTrack("assets/music/bensound-acousticbreeze.mp3");
	audioElement.audio.play(); //play is defined in the HTML5

</script>

~~~~

You'll be heared that the music is playing.

## Creating Initial Playlist with 10 Random Songs

For application, lets create a playlist which will be consist of 10 random songs from the database. For this purpose lets add the following lines to the top of the **includes/nowPlayingBar.php** file.

~~~~

<?php 

	$songQuery = mysqli_query($con, "SELECT id FROM songs ORDER BY RAND() LIMIT 10");

	$resultArray = array();

	while($row = mysqli_fetch_array($songQuery)) { //turning each row to the **array**
		array_push($resultArray, $row['id']); //adding element to the $resultArray;
	}

	$jsonArray = json_encode($resultArray); //lets turn the array to the json. So in the javascript we can use this array.
?>

~~~~

And to see the Result of this query lets run the following **javascript** in this file as well.

~~~~

<script>

	console.log(<?php echo $jsonArray ?>);

</script>

~~~~

## Including JQuery

Now, we need to add the JQuery which is the JavaScript library to our application. We need this because we want to use some built-in functions in this library. Actually JavaScript can do anything that **JQuery** can do, but we need to write much more code than the JQuery.

To include the JQuery add the following line to the **header.php** in the **head** tag before the **script.js** path.

~~~~

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

~~~~

## Add Play Function to the Audio Class in JavaScript

As you know, we've already created a **Audio** class in the **script.js** file. But the problem whenever we want to play to the music we have to add **audioElement.audio.play()** but we don't write **.audio** and we got an error. To fix this problem add the following function to the **Audio** class in **script.js**.

~~~~

	this.play = function() {
		this.audio.play();
	}

~~~~

So whenever we write **audioElement.play()** it will run this function for us and automatically will put the **audio.play()** for us.

## Set Track Function

As you know, before we've created initial playlist with 10 random songs. Now we are going to create a function to run the track.

So first go to **assets/js/script.js** file and add the following lines.

~~~~
var currentPlaylist = []; //this is the way to define empty array in JavaScript
var audioElement;
~~~~

Basically, you can think those variables as an **Global** variables which means can accessible from anywhere.

Then we have to create a function in the **nowPlayingBar.php** file that we've already created our playlist.

In the file add the following lines after the **php** tags.

~~~~

<script>
	
	//This function will run whenever the page loaded completely
	$(document).ready(function() {
		currentPlaylist = <?php echo $jsonArray; ?>; //create an array
		audioElement = new Audio(); //create new instance (object) of Audio class
		setTrack(currentPlaylist[0], currentPlaylist, false) //send the first track id
	});

	function setTrack(trackId, newPlaylist, play) {
		
		audioElement.setTrack("assets/music/bensound-clearday.mp3");

		if(play == true) {
			audioElement.play();
		}
	}

</script>

~~~~

## Playing and Pausing the Song via Buttons

Lets create a **pause()** function in the **script.js** for the **Audio** class which will be pause the song.

~~~~

this.pause = function() {
	this.audio.pause();
}

~~~~

Then we have to create other **play()** and **pause()** functions in the **nowPlayingBar.php** file to assign those functions to the buttons. So lets add the following lines to the this file.

~~~~

function playSong() {
	$(".controlButton.play").hide(); //to hide play button
	$(".controlButton.pause").show(); //to show pause button
	this.audioElement.play();
}

function pauseSong() {
	$(".controlButton.play").show(); //to show play button
	$(".controlButton.pause").hide(); //to hide pause button
	this.audioEelement.pause();
}

~~~~

So, now we can assign those functions to the buttons.

### Add to the Buttons

For to add those functions to the buttons we are going to use **onclick** method on the buttons as follows.

~~~~


	<button class="controlButton play" title="Play button" onclick="playSong()">
		<img src="assets/images/icons/play.png" alt="Play">
	</button>

	<button class="controlButton pause" title="Pause button" onclick="pauseSong()" style="display: none;">
		<img src="assets/images/icons/pause.png" alt="Pause">
	</button>

~~~~

## Introduction to Ajax Calls

In our application, we want to use the **Ajax** call to get the song **path** because, we could use normal php for this but the php is executed before even the page is loaded so it will cause to the problems. So, Instead of PHP we are going to use **Ajax** calls.

Lets add the following lines to the **setTrack()** function in the **nowPlayingBar.php** to get the song path.

~~~~

	function setTrack(trackId, newPlaylist, play) {

		//Ajax call
		$.post("includes/handlers/ajax/getSongJson.php", { songId: trackId }, function(data) {
			console.log(data);
		});
	}

~~~~

Now, as you can see we are trying to send a request to the  **includes/handlers/ajax/getSongJson.php** file which means **getSongJson.php** url in our application with **trackId**.

We have sent trackId to this **url** with the format of **JSON** as like as below.

~~~~

{
	songId: 5
}

~~~~

and we have **function** which returns the result as **function(data)**, So whenever any result or any **echo** has been sent through the url then it will store it in the **data** and we can see what is it just by adding **console.log(data)**.

Okey everything fine up to here but we did not created this file yet. So lets go to the **includes/hanlders** folder and create another folder called **ajax** then create a file inside this **ajax** folder called as **getSongJson.php**.

Into the **getSongJson.php** file add the following lines.

~~~~

<?php

echo "Hello World";

?>

~~~~

Now when you refesh the page you will be seen that **Hello World** will be appeared in the console.

## Getting Song via Ajax Call

In the previous section we've already created the **getSongJson.php** file to the **includes/handlers/ajax** folder. So now lets add the following lines to this file.

~~~~

<?php

include("../../config.php"); //to get the database connection

if(isset($_POST['songId'])) {
	$songId = $_POST['songId'];

	$query = mysqli_query($con, "SELECT * FROM songs where id='$songId'");

	$resultArray = mysqli_fetch_array($query); //to return it to the array

	echo json_encode($resultArray); //to show the array as JSON array
}

?>

~~~~

After then, Lets add the following lines to the **setTrack()** function which is found in the **nowPlayingBar.php** file.

~~~~

	//Ajax call
	$.post("includes/handlers/ajax/getSongJson.php", { songId: trackId }, function(data) {

		//to parse the data because the returned data in a string format
		var track = JSON.parse(data);
		
		$(".trackName span").text(track.title); //to Put the Song name automatically

		audioElement.setTrack(track.path);
		audioElement.play();
	});

~~~~

## Getting The Artist Via Ajax Call

As we've done before, we have to create another **php** file in the **ajax** folder called **getArtistJson.php** and add the following lines to this file.

~~~~

<?php

include("../../config.php"); //to get the database connection

if(isset($_POST['artistId'])) {
	$artistId = $_POST['artistId'];

	$query = mysqli_query($con, "SELECT * FROM artists where id='$artistId'");

	$resultArray = mysqli_fetch_array($query); //to return it to the array

	echo json_encode($resultArray); //to show the array as JSON aRRAY
}

?>

~~~~

And to get the artist information add the following lines to the **setTrack()** function in the **nowPlayingBar.php** file.

~~~~

	function setTrack(trackId, newPlaylist, play) {

		//Ajax call
		$.post("includes/handlers/ajax/getSongJson.php", { songId: trackId }, function(data) {

			//console.log(data);
			//to parse the data
			var track = JSON.parse(data);

			$(".trackName span").text(track.title); //to Put the Song name automatically

			audioElement.setTrack(track.path);
			audioElement.play();

			$.post("includes/handlers/ajax/getArtistJson.php", { artistId: track.artist }, function(data) {
				var artist = JSON.parse(data);

				$(".artistName span").text(artist.name);
			});
		});
	}
~~~~

## Getting The Album Via Ajax Call

As we've done before, we have to create another **php** file in the **ajax** folder called **getAlbumJson.php** and add the following lines to this file.

~~~~

<?php

include("../../config.php"); //to get the database connection

if(isset($_POST['albumId'])) {
	$albumId = $_POST['albumId'];

	$query = mysqli_query($con, "SELECT * FROM albums where id='$albumId'");

	$resultArray = mysqli_fetch_array($query); //to return it to the array

	echo json_encode($resultArray); //to show the array as JSON aRRAY
}

?>

~~~~

And to get the album information add the following lines to the **setTrack()** function in the **nowPlayingBar.php** file.

~~~~

	function setTrack(trackId, newPlaylist, play) {

		//Ajax call
		$.post("includes/handlers/ajax/getSongJson.php", { songId: trackId }, function(data) {

			//console.log(data);
			//to parse the data
			var track = JSON.parse(data);

			$(".trackName span").text(track.title); //to Put the Song name automatically

			audioElement.setTrack(track.path);
			audioElement.play();

			$.post("includes/handlers/ajax/getArtistJson.php", { artistId: track.artist }, function(data) {
				var artist = JSON.parse(data);

				$(".artistName span").text(artist.name);
			});

			$.post("includes/handlers/ajax/getAlbumJson.php", { albumId: track.album }, function(data) {
				var album = JSON.parse(data);

				$(".albumLink img").attr("src",album.artworkPath);
			});
		});
	}
~~~~

## Updating The Plays Count When A Song is Played

Now, we want to update the count of the song whenever its been played. So we can analyze how many times the song is been played by the listeners.

So lets add the following lines to the **play()** function in the **nowPlayingBar.php** file.

~~~~

function playSong() {

	if(audioElement.audio.currentTime == 0) {
		$.post("includes/handlers/ajax/updatePlayCount.php", { songId: audioElement.currentlyPlaying.id });
	}

	$(".controlButton.play").hide(); //to hide play button
	$(".controlButton.pause").show(); //to show pause button
	audioElement.play();	
}

~~~~

Whenever the mp3 or any music file is been attached to this **audioElement** it automatically gets **currentTime** attribute and it holds the playing time of the music. So whenever it is 0 that means a user has been pressed to the click button to play this music, So at that time we can update the song count in the database.

So we've been used here another **ajax** call, which calls the file called **updatePlayCount.php**. Of course this file is been stored in the **includes/handlers/ajax** folder as well.

As you understand, we have to create **updatePlayCount.php** file inside **includes/handlers/ajax** folder and add those lines.

~~~~

<?php

include("../../config.php"); //to get the database connection

if(isset($_POST['songId'])) {
	$songId = $_POST['songId'];

	$query = mysqli_query($con, "UPDATE songs SET play = play + 1 where id='$songId'");
}

?>

~~~~

So whenever **songId** has been sent to this **url** then it will increment the played count by 1.

But the problem is **How can we find the audio id in the play() button?** Because of that we've been changed the **setTrack()** function in the **Auido** class to as follows.

~~~~

	this.setTrack = function(track) {
		this.currentlyPlaying = track;
		this.audio.src = track.path;
	}

~~~~

So we will get the song as **track** and we have been assigned it to the **currentlyPlaying** variable inside the class.

But another problem is how to get the **track data?**, To solve this problem in the **setTrack()** function inside the **nowPlayingBar.php** file make some changes as follows.

~~~~

	audioElement.setTrack(track);

~~~~

## Displaying The Time Remaning Label

In the **script.js** file which we've been already used it as **Audio** class in JavaScript add the following lines to the **Audio** class.

~~~~

function Audio() {

	this.currentlyPlaying;
	this.audio = document.createElement('audio');

	//Whenever this event is started then this function will run
	this.audio.addEventListener("canplay", function() {
		//this refers to the object that the event was called on (this audio object)
		var duration = formatTime(this.duration);
		$(".progressTime.remaining").text(duration);
		//console.log("The duration is: " + duration);
	});

	this.setTrack = function(track) {
		this.currentlyPlaying = track;
		this.audio.src = track.path;
	}

	this.play = function() {
		console.log("here");
		this.audio.play();
	}

	this.pause = function() {
		this.audio.pause();
	}

}

~~~~

As you can see we've added a **addEventListener()** function to this object and in this object we've used another function called **formatTime()** to format the duration of the song. Because as default the duration is in the milliseconds format.


## Formatting the Time Remaining

So lets create the following function in the top of the **Audio** class as follows.

~~~~

function formatTime(seconds) {
	var time = Math.round(seconds); //total times
	var minutes = Math.floor(time / 60); //Rounds down and like 526 seconds to 5 minute
	var seconds = time - (minutes * 60); //like 327- 300 = 27 seconds

	var extraZero = (seconds < 10) ? "0" : "";

	return minutes + ":" + extraZero + seconds;
}

~~~~

So now, whenever this function is called then it will conver the sent seconds to the **minue:seconds** format.

## Updating the Progressbar as The Song Plays

Now in the **script.js** file as I've told before many times, we are using this file as **Audio** class. Add the following function to underneath of the **canplay** event that we've created couple of tutorials ago.

~~~~

	//To update Progress Bar
	this.audio.addEventListener("timeupdate", function() {
		//this duration initially is null and when it has been setted up
		if(this.duration) {
			updateTimeProgressBar(this);
		}
	});

~~~~

Basically you can think this **event** as an loop an whenever the song's **timeupdate** event is called then it will update the progressBar with the times as well.

So as you understand we need to create another function to update the values. So lets create the following function underneath the **formatTime()** function.

~~~~

function updateTimeProgressBar(audio) {
	$(".progressTime.current").text(formatTime(audio.currentTime));
	$(".progressTime.remaining").text(formatTime(audio.duration - audio.currentTime));

	//to change the progressBar
	var progress = audio.currentTime / audio.duration * 100; //calculate the percentage
	$(".playbackBar .progress").css("width", progress + "%");
}

~~~~

## Dragging The Progress Bar On Click

Now, we want to add another functionality to our app. The functionality is whenever user press to the progress bar like into the duration **3.42** of the music, Then the music must start from this duration or move to this one.

So lets add the following line to the **script.js** which we've already used it for **Audio** class file.

~~~~

var mousedown = false;

~~~~

Add this line to the underneath of the **var audioElement**.

The another thing that we have to do is to add the following lines to the **nowPlayingBar.php** file.

~~~~

	//This function will run whenever the page loaded completely
	$(document).ready(function() {
		currentPlaylist = <?php echo $jsonArray; ?>; //create an array
		audioElement = new Audio(); //create new instance (object) of Audio class
		setTrack(currentPlaylist[0], currentPlaylist, false) //send the first track id
		
		//If user clicks to anywhere in the progressBar then it will change mouseDown to true
		$(".playbackBar .progressBar").mousedown(function() {
			mouseDown = true;
		});
		
		//If user has changed the position of the mouse on the progress bar and if mouseDown is true
		$(".playbackBar .progressBar").mousemove(function(e) {
			if(mouseDown == true) {
				//Set time of song, depending on position of mouse
				timeFromOffset(e, this);
				//e refers the mouse
				//this refers to the object that called this event (mousemove)
				//So this is refers to the .playbackBar .progressBar
			}
		});
		
		$(".playbackBar .progressBar").mouseup(function(e) {
			timeFromOffset(e,this);
		});
		
		//When the mouse up then it will convert mouseDown to the false;
		$(document).mouseup(function() {
			mouseDown = false;
		})
	});

~~~~

But as you can see we need to create another function called **timeFromOffset()** to make sure that the music's currentPlaying time is changed to the new one.

~~~~

	function timeFromOffset(mouse, progressBar) {
		var percentage = mouse.offsetX / $(progressBar).width() * 100; 
								   //width of the progress bar
		var seconds = audioElement.audio.duration * (percentage / 100);
		audioElement.setTime(seconds);
	}

~~~~

Also, we need to create another function in the **Audio** class. So lets add the following lines to the **Audio** class as well which means **script.js** file.

~~~~

	this.setTime = function(seconds) {
		this.audio.currentTime = seconds;
	}

~~~~




6B4TT-2HMTH-UQ7TU-KL8DM-UMY85





