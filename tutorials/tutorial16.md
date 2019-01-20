## Seamless page transitions

In this tutorial, we are going to learn how to program the **Seamless Page Transitions** for our application.

## UserLoggedIn JavaScript variable

As you know, we are holding the logged in user's informations in the **php** variable but we want to it to store in the javascript variable to not require to call the **php** for each page.

Lets create a variable in the **script.js** as named as **userLoggedIn**.

~~~~

var userLoggedIn;

~~~~

To hold it in the **javascript** variable lets add the following lines to the **header.php**.

~~~~

if(isset($_SESSION['userLoggedIn'])) {
	$userLoggedIn = $_SESSION['userLoggedIn'];
	echo "<script>userLoggedIn = '$userLoggedIn'</script>";
}

~~~~

Also we need to create a variable in

## Change pages dynamically

To change the pages dynamically we can use **load** function that comes in the **jquery** and we can put another page to any container that we've created.

So, lets add the following function to the **script.js**.

~~~~

function openPage(url) {

	//If ? is missing in the url, it will cause problems
	//because we need index.php?id.... that kind of things 
	if(url.indexOf("?") == -1) {
		url = url + "?";
	}
	console.log(url);
	//encodeURI means it will convert any unknown character on the URL to known one which means www.spotify.me/index.php?user%20name;
	//It automatically puts %20 for spaces or different values for different things
	var encodedUrl = encodeURI(url +  "&userLoggedIn=" +  userLoggedIn);
	$("#mainContent").load(encodedUrl);

	//Assume that you've clicked to the album 5 and url is looks like http://spotify.me/album.php?id=5
	//Normally it goes to the **album.php** and shows it, but we want to show that page in the our main page
	//So we have used $("#mainContent").load(encodedUrl) basically, it will get the page and put it to the
	//container that we have created called mainContent in the **header.php**
}

~~~~

Also we need to make a little change on the **navBarContainer.php** to make sure that whenever user clicks to the **logo** then in the **mainContent** the **index.php** page is loaded.

~~~~

<div id="navBarContainer">
	<nav class="navBar">
		
		<!-- Changes -->
		<span class="logo" onclick="openPage('index.php')">
			<img src="assets/images/icons/logo.png">
		</span>


		<div class="group">

			<div class="navItem">
				<a href="search.php" class="navItemLink">Search
					<img src="assets/images/icons/search.png" class="icon" alt="Search">
				</a>
			</div>

		</div>

		<div class="group">
			<div class="navItem">
				<a href="browse.php" class="navItemLink">Browse</a>
			</div>

			<div class="navItem">
				<a href="yourMusic.php" class="navItemLink">Your Music</a>
			</div>

			<div class="navItem">
				<a href="profile.php" class="navItemLink">Reece Kenney</a>
			</div>
		</div>




	</nav>
</div>

~~~~

## Knowing if a page was loaded from ajax or not

In the previous section we have implemented changing page dynamically functionlity to your application, but the problem is whenever we change the page, it tries to load the components twice and because of that the music is still continue to play and also new music also tries to be play, the another problem is because of that the components like **header.php** and **footer.php** are loaded twice the design of the page is go bad.

To prevent these kind of problems we have to check whether the request is coming from ajax call or directly by the user.

So, lets have a create a file called **includedFiles.php** in the **includes** folder and add the following lines on it.

~~~~

<?php

//If the request sent via ajax then this code will be executed (Every ajax request include HTTP_X_REQUESTED_WITH)
if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {

	//Loads all of those files again 
	include("includes/config.php");
	include("includes/classes/Artist.php");
	include("includes/classes/Album.php");
	include("includes/classes/Song.php");
} else {

	//If user manually writes some url then header and footer must be includes
	include("includes/header.php");
	include("includes/footer.php");

	//Then we have to sent an ajax request to load the rest of the page
	$url = $_SERVER['REQUEST_URI'];
	echo "<script>openPage('$url')</script>"
	exit();
}

~~~~

The another step is to remove **header** and **footer** from the **index.php** because we are going to add them dynamically whenever the request is comes.

~~~~

<?php include("includes/includedFiles.php"); ?>

<h1 class="pageHeadingBig">You Might Also Like</h1>

<div class="gridViewContainer">

	<?php
		$albumQuery = mysqli_query($con, "SELECT * FROM albums ORDER BY RAND() LIMIT 10");

		while($row = mysqli_fetch_array($albumQuery)) {
			



			echo "<div class='gridViewItem'>
					<a href='album.php?id=" . $row['id'] . "'>
						<img src='" . $row['artworkPath'] . "'>

						<div class='gridViewInfo'>"
							. $row['title'] .
						"</div>
					</a>

				</div>";



		}
	?>

</div>

~~~~

## Replacing all links with our dynamic links

In the previous step we've added a functionality to change the pages dynamically and it works just for **index.php** and album pages, but we want to add this functionality to all the pages.

To add this functionality to all pages, we have to change their links. So, lets have an add the following lines to the **navBarContainer.php**. 

~~~~

<div id="navBarContainer">
	<nav class="navBar">
		
		<!-- Because of that span does not have link feature we have to add it role="link" and tabIndex="0" (which means whenever user clicks to the **tab** button it becomes on this one) <a></a> components as default has this feature -->
		<span role="link" tabIndex="0" onclick="openPage('index.php')" class="logo" >
			<img src="assets/images/icons/logo.png">
		</span>


		<div class="group">

			<div class="navItem">
				<span role="link" tabIndex="0" onclick="openPage('search.php')" class="navItemLink">Search
					<img src="assets/images/icons/search.png" class="icon" alt="Search">
				</a>
			</div>

		</div>

		<div class="group">
			<div class="navItem">
				<span role="link" tabIndex="0" onclick="openPage('browse.php')" class="navItemLink">Browse</span>
			</div>

			<div class="navItem">
				<span role="link" tabIndex="0" onclick="openPage('yourMusic.php')" class="navItemLink">Your Music</span>
			</div>

			<div class="navItem">
				<span role="link" tabIndex="0" onclick="openPage('profile.php')" class="navItemLink">Reece Kenney</span>
			</div>
		</div>




	</nav>
</div>

~~~~

Also, we need to make a little changes on the **album.php** as well to make sure that **header** and **footer** is loaded once, and the solution is to remove the **include(includes/header.php)** and **include(includes/footer.php)**.

~~~~

<?php include("includes/includedFiles.php"); ?>

	if(isset($_GET['id'])) {
		$albumId = $_GET['id'];
	}
	else {
		header("Location: index.php");
	}

	$album = new Album($con, $albumId); //create Album instance (Class object)
	$artist = $album->getArtist();

	//echo $album->getTitle(). '<br>';
	//echo $artist->getName();
?>

<div class="entityInfo">

	<div class="leftSection">
		<img src="<?php echo $album->getArtworkPath(); ?>">
	</div>

	<div class="rightSection">
		<h2><?php echo $album->getTitle(); ?></h2>
		<p>By <?php echo $artist->getName(); ?></p>
		<p><?php echo $album->getNumberOfSongs(); ?> songs</p>

	</div>

</div>

<div class="tracklistContainer">
	<ul class="tracklist">
		
		<?php
		$songIdArray = $album->getSongIds();

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

	</ul>
</div>

~~~~

Also, we need to change the link style of the **index.php** as well as like follows.

~~~~

<?php include("includes/includedFiles.php"); ?>

<h1 class="pageHeadingBig">You Might Also Like</h1>

<div class="gridViewContainer">

	<?php
		$albumQuery = mysqli_query($con, "SELECT * FROM albums ORDER BY RAND() LIMIT 10");

		while($row = mysqli_fetch_array($albumQuery)) {
			



			echo "<div class='gridViewItem'>
					<span role='link' tabIndex='0' onclick='openPage(\"album.php?id=" . $row['id'] . "\")'>
						<img src='" . $row['artworkPath'] . "'>

						<div class='gridViewInfo'>"
							. $row['title'] .
						"</div>
					</span>

				</div>";



		}
	?>

</div>

~~~~

## Small play button bug

Whenever user clicks to an album and goes to the album songs list page, if they click to the play button the music starts but the play button is not changing in the nowPlayinBar.

So, lets have a change the following lines in **nowPlayinBar.php** file.

~~~~

	//Currently not work because as default we are sending false
	if(play == true) {
		// audioElement.play(); remove this
		playSong();
	}

~~~~

## Changing the URL when we load a new page

In the previous steps we have added a functionality to change the pages dynamically but we are not showing the **url** of the page that we actually loaded. To add this functionality we have to add the following lines to the **script.js** and **openPage()** function.

~~~~

function openPage(url) {

	if(url.indexOf("?") == -1) {
		url = url + "?";
	}

	var encodedUrl = encodeURI(url +  "&userLoggedIn=" +  userLoggedIn);
	$("#mainContent").load(encodedUrl);
	$("body").scrollTop(0); //to make sure that the page is scrolled to the top of the page again
	history.pushState(null, null, url); //Just puts the url of the page to the address bar
}

~~~~

## Browse page

In our application, we have **Browse** button but it does not work actually because we don't have **browse** page. So, lets have an create a php file called **browse.php** and add the copy the all the content of the **index.php** to this one.

~~~~

<?php include("includes/includedFiles.php"); ?>

<h1 class="pageHeadingBig">You Might Also Like</h1>

<div class="gridViewContainer">

	<?php
		$albumQuery = mysqli_query($con, "SELECT * FROM albums ORDER BY RAND() LIMIT 10");

		while($row = mysqli_fetch_array($albumQuery)) {
			



			echo "<div class='gridViewItem'>
					<span role='link' tabIndex='0' onclick='openPage(\"album.php?id=" . $row['id'] . "\")'>
						<img src='" . $row['artworkPath'] . "'>

						<div class='gridViewInfo'>"
							. $row['title'] .
						"</div>
					</span>

				</div>";



		}
	?>

</div>

~~~~

Then make sure that, you have changed the **index.php** content to the following lines.

~~~~

<?php include("includes/includedFiles.php"); ?>

<script>openPage("browse.php")</script>

~~~~