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

	function setTrack(trackId, newPlaylist, play) {

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

					<button class="controlButton shuffle" title="Shuffle button">
						<img src="assets/images/icons/shuffle.png" alt="Shuffle">
					</button>

					<button class="controlButton previous" title="Previous button">
						<img src="assets/images/icons/previous.png" alt="Previous">
					</button>

					<button class="controlButton play" title="Play button" onclick="playSong()">
						<img src="assets/images/icons/play.png" alt="Play">
					</button>

					<button class="controlButton pause" title="Pause button" onclick="pauseSong()" style="display: none;">
						<img src="assets/images/icons/pause.png" alt="Pause">
					</button>

					<button class="controlButton next" title="Next button">
						<img src="assets/images/icons/next.png" alt="Next">
					</button>

					<button class="controlButton repeat" title="Repeat button">
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

				<button class="controlButton volume" title="Volume button">
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