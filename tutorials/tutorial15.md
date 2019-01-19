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

## Controlling The Volume

The functionality that we are going to add to our app is to check and change the volume of the music whenever user press to the volume bar, Then the music volume must be changed accordingly to the point on the volume bar.

Lets add the following line to the **nowPlayingBar.php** which we already used in the previous sections.

~~~~

	//This function will run whenever the page loaded completely
	$(document).ready(function() {
		currentPlaylist = <?php echo $jsonArray; ?>; //create an array
		audioElement = new Audio(); //create new instance (object) of Audio class
		setTrack(currentPlaylist[0], currentPlaylist, false) //send the first track id

		$(".playbackBar .progressBar").mousedown(function() {
			mouseDown = true;
		});

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
			console.log("Mouse Up");
			timeFromOffset(e,this);
		});
		
		//Already Existed Section
		//--------------------------------------------------------
		//New Section

		//For Controlling the Volume
		$(".volumeBar .progressBar").mousedown(function() {
			mouseDown = true;
		});

		//while moving the mouse over this volumeBar
		$(".volumeBar .progressBar").mousemove(function(e) {
			if(mouseDown == true) {
											 //$ = JQuery object, this -> .volumeBar .prgoressBar (clicked item) 
				var percentage = e.offsetX / $(this).width(); // returns 0 - 1 (0.2, 0.5 .. 1)

				//Needed to avoid the error
				if(percentage >= 0 && percentage <= 1)
					audioElement.audio.volume = percentage; //assign new percentage to the audioElements volume property
			}
		});

		//After clicked some point on this volume bar
		$(".volumeBar .progressBar").mouseup(function(e) {
											 //$ = JQuery object, this -> .volumeBar .prgoressBar (clicked item) 
				var percentage = e.offsetX / $(this).width();
				audioElement.audio.volume = percentage;

				//Needed to avoid the error
				if(percentage >= 0 && percentage <= 1)
					audioElement.audio.volume = percentage; //assign new percentage to the audioElements volume property

				/*
				 * Whenever volume property changes it will trigger VolumeChange event for audio element, will be added
				 * in the next section
				 */
		});


		$(document).mouseup(function() {
			mouseDown = false;
		})
	});

~~~~

## Updating the volume Progress Bar

Another functionality that we want to add our application to show the current percentage of the volumeBar accordingly to the clicked point on it which we have covered in the previous section.

Lets add the following lines (function or method) to the **script.js** file inside **assets/js/script.js** to change the volumeBar via **css** attribute.

~~~~

function updateVolumeProgressBar(audio) {
	//to change the volumeBar
	var volume = audio.volume * 100; //audio of the music is decimal number between 0 and 1
	//to get the percentage we multiply it with 100, and if it was 0.9 then we know that its 90% 
	$(".volumeBar .progress").css("width", volume + "%");
}

~~~~

But only this function is not enough until we call it. So lets add the following event handler function to the **Audio** class in the **script.js**.

~~~~

	//New event handler to update volumeBar ----- volumeChange is pre-defined function for audio elements
	this.audio.addEventListener("volumeChange", function() {
		updateVolumeProgressBar(this); //this refers to (current audio element)
	})

~~~~

In this point, you can see that whenever you click any point on the **volumeBar**, it automatically updates it, but initially we want **100%** volume. So, lets add the following line to the **nowPlayingBar.php**.

~~~~

	$(document).ready(function() {
		currentPlaylist = <?php echo $jsonArray; ?>; //create an array
		audioElement = new Audio(); //create new instance (object) of Audio class
		setTrack(currentPlaylist[0], currentPlaylist, false) //send the first track id
		updateVolumeProgressBar(audioElement.audio); //initially make sure that it is 100 (new line)
		
		....

~~~~

## Preventing controls from highlighting on mouse drag

Whenever you try to drag the mouse on **nowPlayingBarContainer** which is the container that includes all the music player, it highlights (blue coloured) the elements and it doesn't looks like good at some point. So lets prevent this by adding following lines to the **nowPlayingBar.php**

~~~~

	//This function will run whenever the page loaded completely
	$(document).ready(function() {
		currentPlaylist = <?php echo $jsonArray; ?>; //create an array with the IDs of the songs
		audioElement = new Audio(); //create new instance (object) of Audio class
		setTrack(currentPlaylist[0], currentPlaylist, false) //send the first track id
        updateVolumeProgressBar(audioElement.audio); //initially make sure that it is 100

        //To prevent the controls from highlighting on mouse drag
       	//hole container 			    any of those events
        $("#nowPlayingBarContainer").on("mousedown touchstart mousemove touchmove", function(e) {
        	e.preventDefault(); //means don't do your normal behaviour (it will not highlight)
        });

        ....

        //You can also write those mousedown, touchstart, mousemove, touchmove event handlers separately as like as the following.

~~~~

After adding this code, you will see that it will not highlighted anymore.

## Skipping to the Next Song

In this part, we need to implement a functionality to play the next song on the list. So, to do this we need to create a variable called **currentIndex** in the **script.js** to hold the current playing song index accordingly from the **playlist**.

~~~~

var currentIndex = 0; //firt song in the list

~~~~

Then we need to change this **currentIndex** variable in the **setTrack()** function that we've created before in the **nowPlayingBar.php** file accordingly to the song wanted to be played.

Add the following line to this function.

~~~~

	function setTrack(trackId, newPlaylist, play) {

		currentIndex = currentPlaylist.indexOf(trackId); //trackId = 30, index of the 30 is the 6 in the list
		pauseSong(); //stop the currentSong that was playing

		....

	}

~~~~

The next step is to write function that is going to be used to change the **song** according to the index.

~~~~

	function nextSong() {
		//last song int he list
		if(currentIndex == currentPlaylist.length - 1) {
			currentIndex = 0;
		} else {
			currentIndex++;
		}

		var trackToPlay = currentPlaylist[currentIndex]; //get the song id from the list (Ex: 30)
		setTrack(trackToPlay, currentPlaylist, true); //automatically will be started
	}

~~~~

Last thing that we have to do is assigning **nextSong()** function to the **Next Button** like as follows. So, whenever user clicks to this button then it will automatically will play the next song.

~~~~

	<button class="controlButton next" title="Next button" onclick="nextSong()">
		<img src="assets/images/icons/next.png" alt="Next">
	</button>

~~~~

## Song Repeat Mode

Sometimes, users likes to repeat the music that they are listening, so we need to implement a functionality for that, it is very very simple as like as the previous section.

Lets have a define a variable called **repeat** in the **script.js**.

~~~~

var repeat = false; //as default false

~~~~

Now, we need to another logic to our **nextSong()** function which we've created it in the previous step to make sure that if user clicked to the **repeat** button then whenever the current song is ended and the next song has to be started, this song is started again.

~~~~

	function nextSong() {

		if(repeat == true) {
			audioElement.setTime(0); //set the time of the song 0 which is the beginning of the music
			playSong();
			return;
		}

		//last song int he list
		if(currentIndex == currentPlaylist.length - 1) {
			currentIndex = 0;
		} else {
			currentIndex++;
		}

		var trackToPlay = currentPlaylist[currentIndex];
		setTrack(trackToPlay, currentPlaylist, true); //automatically will be started
	}

~~~~

## Repeat Button

In the previous step, we've added repeat logic to the **nextSong()** function, and in this step we've to create another function which will be called whenever user clicks to the **Repeat Button**.

Add the following function to the **nowPlayingBar.php** file.

~~~~

	function setRepeat() {
		repeat = !repeat; //true or false
		var imageName = repeat ? "repeat-active.png" : "repeat.png"; //image will be changed accordingly the status

		$(".controlButton.repeat img").attr("src", "assets/images/icons/" + imageName);
	}

~~~~

Now, we've created a function called **setRepeat()** and it changes the image of the **Repeat Button** and **repeat** mode as well.

To call this function we have to assign it to the **Repeat Button**.

~~~~

	<button class="controlButton repeat" title="Repeat button" onclick="setRepeat()">
		<img src="assets/images/icons/repeat.png" alt="Repeat">
	</button>

~~~~

## Playing the next song when current song end

To play the next song whene the current song is ended, we have to add an event listener in the **script.js** for the **Audio** class which we've already created before.

~~~~

	this.aduio.addEventListener("ended", function() {
		nextSong(); //the function in the **nowPlayingBar.php**
	});

~~~~

## Previous Song Button

To play the previous song whenever users wants to, we have to create a simple function in the **nowPlayingBar.php**.

Lets add the following function to this file.

~~~~

	function prevSong() {

		//If current song is the first song in the playlist just reset the time
		if(audioElement.audio.currentTime >= 3 || currentIndex == 0) {
			audioElement.setTime(0);
		} else {
			currentIndex = currentIndex - 1;
			setTrack(currentPlaylist[currentIndex], currentPlaylist, true); //call setTrack()
		}
	}

~~~~

Then, we have add this function to the **Prev Button** onclick method.

~~~~

	<button class="controlButton previous" title="Previous button" onclick="prevSong()">
		<img src="assets/images/icons/previous.png" alt="Previous">
	</button>

~~~~

## Mute Button

In this step, we are going to add another functionality to our application which is to allow the users to click to the **mute** button and the sound of the music will be gone.

Lets add the following function to the **nowPlayingBar.php** file.

~~~~

	function setMute() {
		audioElement.audio.muted = !audioElement.audio.muted; //muted is the pre-defined function
		var imageName = audioElement.audio.muted ? "volume-mute.png" : "volume.png";

		$(".controlButton.volume img").attr("src", "assets/images/icons/" + imageName);
	}	

~~~~

Then, assign the function to the **mute** button.

~~~~

	<button class="controlButton volume" title="Volume button" onclick="setMute()">
		<img src="assets/images/icons/volume.png" alt="Volume">
	</button>

~~~~

## Shuffle Button

In this step, we are going to add a functionlity to our app to allow the users to mix the songs of the currentPlayList.

Lets add a new variables in the **script.js**.

~~~~

var currentPlaylist = []; //new array variable to hold the shuffled list
...

var shuffle = false; //new variable for shuffle

~~~~

Firs of all we have to add a new logic to the **setTrack()** method which we've already created in the previous sections.

~~~~
	function setTrack(trackId, newPlaylist, play) {
		
		//currentPlayList holds the the list of the songs and still it is same whenever the album is also changed
		
		//That means user selected a new album, and it comes with a new playlist
		if(newPlaylist != currentPlaylist) {
			currentPlaylist = newPlaylist; //If we change anything here, it will be reflected to the shafflePlayList
			shufflePlayList = currentPlaylist.slice(); //slice means create copy but changes not reflected to the currentPlayList
			//or vice versa
			shuffleArray(shufflePlayList); //this is the function that we will be created
		}

		if(shuffle == true) {
			currentIndex = shufflePlayList.indexOf(trackId);
		} else {
			currentIndex = currentPlaylist.indexOf(trackId); //trackId = 30, index of the 30 is the 6 in the list
		}

		....
	}

~~~~

Lets have a create a function to shuffle the list.

~~~~

	//Whenever we call this, it will be changed the places of the songs in the sent array

	function shuffleArray(a) {
		var j, x, i;

		for (i = a.length; i; i--) {
			j = Math.floor[Math.random() * i];
			x = a [i - 1];
			a[i - 1] = a [j];
			a[j] = x;
		}
	}
~~~~

If the user clicks to the **Shuffle Button** we have to change the **songList** to the shuffled one. So let's have a create a new function and add the following lines to it.

~~~~

	function setShuffle() {
		shuffle = !shuffle;
		var imageName = shuffle ? "shuffle-active.png" : "shuffle.png";
		$(".controlButton.shuffle img").attr("src", "assets/images/icons/" + imageName);

		if(shuffle == true) {
			//Randomize playlist
			shuffleArray(shufflePlayList);
			currentIndex = shufflePlayList.indexOf(audioElement.currentlyPlaying.id);


			/*
			 * Assume that, we have an playlist like as follows
			 * Songs:
			 * a,b,c
			 * 
			 * b = currentPlaying song
			 * current Index is = 1
			 *
			 * whenever shuffle is activated and the shufflePlayList has been created
			 * we have to get the currently Playing song again because the index
			 * of the song probably has been changed in the list
			 * Ex: Songs
			 * c,a,b
			 * current Index is still = 1 that means after the song in the 1 ended
			 * then b will be started again but instead of that 
			 * we want to move on to the next song in the list which is the *c* 
			 * So we have to say
			 * current Index = indexOf(currentlyPlaying.id) //which becomes 2
			 * and the next song index will become 0 which refers *c* and it will be started to run
			 *
			 *
			 */

		} else {
			//shuffle has been deactivated
			//go back to regular playList
			currentIndex = currentPlaylist.indexOf(audioElement.currentlyPlaying.id);
		}
	}

~~~~

After that step, we have to make a little changes in the **nowPlayingBar.php** like to hold the songs in the list called **newPlayList** instead of **currentPlayList** and because of that we are going to call the **setTrack** method, it will automatically will create **currentPlayList** and **shufflePlayList** for us.

~~~~

	//This function will run whenever the page loaded completely
	$(document).ready(function() {
		var newPlaylist = <?php echo $jsonArray; ?>; //create an array with the IDs of the songs
		audioElement = new Audio(); //create new instance (object) of Audio class
		setTrack(newPlaylist[0], newPlaylist, false) //send the first track id
        updateVolumeProgressBar(audioElement.audio); //initially make sure that it is 100

~~~~

The last thing we have to do is to assign the **setShuffle()** function to the **Shuffle Button**.

~~~~

	<button class="controlButton shuffle" title="Shuffle button" onclick="setShuffle()">
		<img src="assets/images/icons/shuffle.png" alt="Shuffle">
	</button>

~~~~

## Playing songs by clicking on the song's play button

As you know, whenever user clicks to the new album on the list, actually new songs are loaded but the problem is the song list is the still previous one and the application continue to the list accordingly the old one.

So, we have to hold the new list items on another array whenever user clicks to any of the song of the album, application should continue on the new album's song list.

Lets have an add a new array in the **script.js** to hold the new album songs.

~~~~

...

var tempPlayList = [];

...

~~~~

In the next step we have to add a script to the **album.php** to hold the current albums songs in the **tempPlayList** array which we just created.

Change the logic of the **album.php** to the following one.

~~~~
		...

		$i = 1;
		foreach($songIdArray as $songId) {

			$albumSong = new Song($con, $songId);
			$albumArtist = $albumSong->getArtist();
			
			echo "<li class='tracklistRow'>
					<div class='trackCount'>
						<img class='play' src='assets/images/icons/play-white.png' onclick='setTrack(\"" . $albumSong->getId() . "\", tempPlayList, true)'> 
						<span class='trackNumber'>$i</span>
					</div>


					<div class='trackInfo'>
						<span class='trackName'>" . $albumSong->getTitle() . "</span>
						<span class='artistName'>" . $albumArtist->getName() . "</span>
					</div>

					<div class='trackOptions'>
						<img class='optionsButton' src='assets/images/icons/more.png'>
					</div>

					<div class='trackDuration'>
						<span class='duration'>" . $albumSong->getDuration() . "</span>
					</div>


				</li>";

			$i = $i + 1;
		}

		?>
		
		<!-- To hold the songs of the current album -->
		<script>
			var tempSongIds = '<?php echo json_encode($songIdArray); ?>'; //converted php array to the json format
			tempPlayList = JSON.parse(tempSongIds); //json format to the javaScript array
		</script>
		
		...
~~~~

The another thing that we have to do is to add a **getId** function to the **Song.php**.

~~~~

	public function getId() {
		return $this->id;
	}

~~~~
