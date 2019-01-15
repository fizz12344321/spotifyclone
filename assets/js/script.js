var currentPlaylist = [];
var audioElement;
var mouseDown = false;

function formatTime(seconds) {
	var time = Math.round(seconds); //total times
	var minutes = Math.floor(time / 60); //Rounds down and like 526 seconds to 5 minute
	var seconds = time - (minutes * 60); //like 327- 300 = 27 seconds

	var extraZero = (seconds < 10) ? "0" : "";

	return minutes + ":" + extraZero + seconds;
}

function updateTimeProgressBar(audio) {
	$(".progressTime.current").text(formatTime(audio.currentTime));
	$(".progressTime.remaining").text(formatTime(audio.duration - audio.currentTime));

	//to change the progressBar
	var progress = audio.currentTime / audio.duration * 100; //calculate the percentage
	$(".playbackBar .progress").css("width", progress + "%");
}

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

	//To update Progress Bar
	this.audio.addEventListener("timeupdate", function() {
		//this duration initially is null and when it has been setted up
		if(this.duration) {
			updateTimeProgressBar(this);
		}
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

	this.setTime = function(seconds) {
		this.audio.currentTime = seconds;
	}

}