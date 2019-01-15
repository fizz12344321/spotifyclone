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
	<script src="assets/js/script.js"></script> //we've already added this script file to header.php
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