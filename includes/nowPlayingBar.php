<?php 

	$songQuery = mysqli_query($con, "SELECT id FROM songs ORDER BY RAND() LIMIT 10");

	$resultArray = array();

	while($row = mysqli_fetch_array($songQuery)) { //turning each row to the **array**
		array_push($resultArray, $row['id']); //adding element to the $resultArray;
	}

	$jsonArray = json_encode($resultArray); //lets turn the array to the json array. So in the javascript we can use this array.
?>

<script>
	
	//This function will run whenever the page loaded completely
	$(document).ready(function() {
		var newPlaylist = <?php echo $jsonArray; ?>; //create an array with the IDs of the songs
		audioElement = new Audio(); //create new instance (object) of Audio class
		setTrack(newPlaylist[0], newPlaylist, false) //send the first track id
        updateVolumeProgressBar(audioElement.audio); //initially make sure that it is 100

        //To prevent the controls from highlighting on mouse drag
       	//hole container 			    any of those events
        $("#nowPlayingBarContainer").on("mousedown touchstart mousemove touchmove", function(e) {
        	e.preventDefault(); //means don't do your normal behaviour (it will not highlight)
        });

        //You can also write those mousedown, touchstart, mousemove, touchmove event handlers separately as like as the following.

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



		//For Controlling the Volume
		$(".volumeBar .progressBar").mousedown(function() {
			mouseDown = true;
		});

		//while moving the mouse over this volumeBar
		$(".volumeBar .progressBar").mousemove(function(e) {
			if(mouseDown == true) {
											 //$ = JQuery object, this -> .volumeBar .prgoressBar (clicked item) 
				var percentage = e.offsetX / $(this).width(); // returns 0 - 1

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
					audioElement.audio.volume = percentage;
		});


		$(document).mouseup(function() {
			mouseDown = false;
		})
	});

	function timeFromOffset(mouse, progressBar) {
		console.log("Mouse OffSetX is: " + mouse.offsetX);
		var percentage = mouse.offsetX / $(progressBar).width() * 100; 
								   //width of the progress bar
		var seconds = audioElement.audio.duration * (percentage / 100);
		audioElement.setTime(seconds);
	}

	function prevSong() {

		//If current song is the first song in the playlist just reset the time
		if(audioElement.audio.currentTime >= 3 || currentIndex == 0) {
			audioElement.setTime(0);
		} else {
			currentIndex = currentIndex - 1;
			setTrack(currentPlaylist[currentIndex], currentPlaylist, true);
		}
	}

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

		var trackToPlay = shuffle ? shufflePlayList[currentIndex] : currentPlaylist[currentIndex];
		setTrack(trackToPlay, currentPlaylist, true); //automatically will be started
	}

	function setRepeat() {
		repeat = !repeat;
		var imageName = repeat ? "repeat-active.png" : "repeat.png";

		$(".controlButton.repeat img").attr("src", "assets/images/icons/" + imageName);
	}

	function setMute() {
		audioElement.audio.muted = !audioElement.audio.muted;
		var imageName = audioElement.audio.muted ? "volume-mute.png" : "volume.png";
		$(".controlButton.volume img").attr("src", "assets/images/icons/" + imageName);
	}

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
			 * we want to move on to the next song in the lsit which is the *c* 
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

	function shuffleArray(a) {
		var j, x, i;

		for (i = a.length; i; i--) {
			j = Math.floor[Math.random() * i];
			x = a [i - 1];
			a[i - 1] = a [j];
			a[j] = x;
		}
	}	

	function setTrack(trackId, newPlaylist, play) {

		//That means user selected a new album, and it comes with a new playlist
		if(newPlaylist != currentPlaylist) {
			currentPlaylist = newPlaylist; //If we change anything here, it will be reflected to the shafflePList
			shufflePlayList = currentPlaylist.slice(); //slice means create copy but changes not reflected
			shuffleArray(shufflePlayList);
		}

		if(shuffle == true) {
			currentIndex = shufflePlayList.indexOf(trackId);
		} else {
			currentIndex = currentPlaylist.indexOf(trackId); //trackId = 30, index of the 30 is the 6 in the list
		}
		pauseSong();

		//Ajax call
		$.post("includes/handlers/ajax/getSongJson.php", { songId: trackId }, function(data) {

			//console.log(data);
			//to parse the data
			var track = JSON.parse(data);

			$(".trackName span").text(track.title); //to Put the Song name automatically

			audioElement.setTrack(track);

			$.post("includes/handlers/ajax/getArtistJson.php", { artistId: track.artist }, function(data) {
				var artist = JSON.parse(data);

				$(".artistName span").text(artist.name);
			});

			$.post("includes/handlers/ajax/getAlbumJson.php", { albumId: track.album }, function(data) {
				var album = JSON.parse(data);

				$(".albumLink img").attr("src",album.artworkPath);
			});


			playSong();
		});

		//Currently not work because as default we are sending false
		if(play == true) {
			audioElement.play();
		}
	}

	function playSong() {

		console.log(audioElement.currentlyPlaying.id);

		//If the song has been played initially
		if(audioElement.audio.currentTime == 0) {
			$.post("includes/handlers/ajax/updatePlayCount.php", { songId: audioElement.currentlyPlaying.id }, function(data) {
				console.log(data);
			});
		}

		$(".controlButton.play").hide(); //to hide play button
		$(".controlButton.pause").show(); //to show pause button
		audioElement.play();
	}

	function pauseSong() {
		$(".controlButton.play").show(); //to show play button
		$(".controlButton.pause").hide(); //to hide pause button
		audioElement.pause();
	}

</script>

<div id="nowPlayingBarContainer">

	<div id="nowPlayingBar">

		<div id="nowPlayingLeft">
			<div class="content">
				<span class="albumLink">
					<img src="https://i.ytimg.com/vi/rb8Y38eilRM/maxresdefault.jpg" class="albumArtwork">
				</span>

				<div class="trackInfo">

					<span class="trackName">
						<span>Happy Birthday</span>
					</span>

					<span class="artistName">
						<span>Reece Kenney</span>
					</span>

				</div>



			</div>
		</div>

		<div id="nowPlayingCenter">

			<div class="content playerControls">

				<div class="buttons">

					<button class="controlButton shuffle" title="Shuffle button" onclick="setShuffle()">
						<img src="assets/images/icons/shuffle.png" alt="Shuffle">
					</button>

					<button class="controlButton previous" title="Previous button" onclick="prevSong()">
						<img src="assets/images/icons/previous.png" alt="Previous">
					</button>

					<button class="controlButton play" title="Play button" onclick="playSong()">
						<img src="assets/images/icons/play.png" alt="Play">
					</button>

					<button class="controlButton pause" title="Pause button" onclick="pauseSong()" style="display: none;">
						<img src="assets/images/icons/pause.png" alt="Pause">
					</button>

					<button class="controlButton next" title="Next button" onclick="nextSong()">
						<img src="assets/images/icons/next.png" alt="Next">
					</button>

					<button class="controlButton repeat" title="Repeat button" onclick="setRepeat()">
						<img src="assets/images/icons/repeat.png" alt="Repeat">
					</button>

				</div>


				<div class="playbackBar">

					<span class="progressTime current">0.00</span>

					<div class="progressBar">
						<div class="progressBarBg">
							<div class="progress"></div>
						</div>
					</div>

					<span class="progressTime remaining">0.00</span>


				</div>


			</div>


		</div>

		<div id="nowPlayingRight">
			<div class="volumeBar">

				<button class="controlButton volume" title="Volume button" onclick="setMute()">
					<img src="assets/images/icons/volume.png" alt="Volume">
				</button>

				<div class="progressBar">
					<div class="progressBarBg">
						<div class="progress"></div>
					</div>
				</div>

			</div>
		</div>




	</div>

</div>