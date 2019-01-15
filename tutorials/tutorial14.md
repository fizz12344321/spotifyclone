# Album Page

In this tutorial, we are going to learn how to create **Artist** and **Album** class to use them in the **album page**.

These classes will be used to show the album information line **album title, album artist** and etc.

## Album Page

First of all, we have to create a page or file called **album.php** in the project folder. Then add the following lines to this page (file).

~~~~

<?php include("includes/header.php"); 

	if(isset($_GET['id'])) {
		$albumId = $_GET['id'];
	}
	else {
		header("Location: index.php");
	}
	?>


<?php include("includes/footer.php"); ?>

~~~~

First of all we are checking if the **id** is provided for this page like **album.php?id = 1** and if it is not provided then we have to redirect the user to the **index.php**.


## Artist Class

First of all we have to create an **Artist.php** file in the **includes/classes** and we are going to use this file as **Artist** class.

So, create the file and add the following lines to this file.

~~~~

<?php
	class Artist {

		private $con;
		private $id;

		public function __construct($con, $id) {
			$this->con = $con;
			$this->id = $id;
		}

		public function getName() {
			$artistQuery = mysqli_query($this->con, "SELECT name FROM artists WHERE id='$this->id'");
			$artist = mysqli_fetch_array($artistQuery);
			return $artist['name'];
		}
	}
?>

~~~~

Basically, this class will get two parameters as **connection and the id**, So according to those parameters we are going to be able to send a query to the database with a **artist id** and get the information of the **artist**.

## Album Class

Now, we have to create **Album** class to make sure that we can get the album informations. So create this file in the classes and add the following lines.

~~~~

<?php
	class Album {

		private $con;
		private $id;
		private $title;
		private $artistId;
		private $genre;
		private $artworkPath;

		public function __construct($con, $id) {
			$this->con = $con;
			$this->id = $id;

			$query = mysqli_query($this->con, "SELECT * FROM albums WHERE id='$this->id'");
			$album = mysqli_fetch_array($query);
			
			/* Those values converted to the array */
			$this->title = $album['title'];
			$this->artistId = $album['artist'];
			$this->genre = $album['genre'];
			$this->artworkPath = $album['artworkPath'];


		}

		public function getTitle() {
			return $this->title;
		}

		public function getArtist() {
			return new Artist($this->con, $this->artistId); //creates new artist
		}

		public function getGenre() {
			return $this->genre;
		}

		public function getArtworkPath() {
			return $this->artworkPath;
		}
	}
?>

~~~~

This classes gets two parameter as well as like **artist** class. The first parameter is the **connection** and the second one is **album id**.

According to the **album id** we get all the information from the database using the following query **$query = mysqli_query($this->con, "SELECT * FROM albums WHERE id='$this->id'");** and we are storing those information in the different variables by converting them to the array.

## Use Classes

Now lets make a small example of how to use those clases. In the **album.php** which we are going to use as **album page** add the following lines.

~~~~

<?php include("includes/header.php"); 

	if(isset($_GET['id'])) {
		$albumId = $_GET['id'];
	}
	else {
		header("Location: index.php");
	}

	$album = new Album($con, $albumId); //create Album instance (Class object)
	$artist = $album->getArtist();

	echo $album->getTitle(). '<br>';
	echo $artist->getName();
?>


<?php include("includes/footer.php"); ?>

~~~~

Basically, As I explained before we are checking here if the id is provided in this URL as like as **album.php?id=1** and if it was provided then we can find the album informations by creating an **Album** class instance means (an object from **Album** class).

Then to find the **artist** of this album is very simple, because we've already created a function in the album which finds the artist information.

So by calling **$artist = $album->getArtist()** we can find the artist of the album.

To find the album and artist name we've used the following lines.

~~~~

	echo $album->getTitle(). '<br>';
	echo $artist->getName();

~~~~

## Caution

To make sure that, the all classes are working without an error we have to **include** those classes in to the **header.php** file which is found in the **includes** folder.

So, to include those classes go to the **header.php** and add the following liñes to the top of the page and uder the **include("includes/config.php")**.

~~~~

<?php
include("includes/config.php");
include("includes/classes/Artist.php");
include("includes/classes/Album.php");

~~~~

## Header Section

For header section we want to show the album name and the artist name who've been the own the music. So lets add the following lines to the **album.php** file.

~~~~

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

~~~~

In the **leftSection** we are going to show the album image and in the **rightSection** we are going to show the album **title, artistName and also the number of the songs as well**.

To show the number of the songs we have to create another function in the **includes/classes/Album.php** file which we've been used it as a **Album** class.

## Number Of Songs

In the **includes/classes/Album.php** file add the following lines for to get the number of songs in the album.

~~~~

	public function getNumberOfSongs() {
		$query = mysqli_query($this->con, "SELECT id FROM songs WHERE album='$this->id'");
		return mysqli_num_rows($query);
	}

~~~~

In this function we could used **SELECT COUNT(id) FROM songs WHERE album='$this->id'** but it does not required acutally, because we've returned the **mysqli_num_rows($query)** which counts all the rows and returns the number. So that means If actually there 5 songs in the database then it returns **5**.

## Song Class

Now we have to create a **Song.php** file in the **includes/classes/Song.php** which we'll be used it as **Song** class to get the **song** informations.

~~~~

<?php
	class Song {

		private $con;
		private $id;
		private $mysqliData;
		private $title;
		private $artistId;
		private $albumId;
		private $genre;
		private $duration;
		private $path;

		public function __construct($con, $id) {
			$this->con = $con;
			$this->id = $id;

			$query = mysqli_query($this->con, "SELECT * FROM songs WHERE id='$this->id'");
			$this->mysqliData = mysqli_fetch_array($query);
			$this->title = $this->mysqliData['title'];
			$this->artistId = $this->mysqliData['artist'];
			$this->albumId = $this->mysqliData['album'];
			$this->genre = $this->mysqliData['genre'];
			$this->duration = $this->mysqliData['duration'];
			$this->path = $this->mysqliData['path'];
		}

		public function getTitle() {
			return $this->title;
		}

		public function getArtist() {
			return new Artist($this->con, $this->artistId);
		}

		public function getAlbum() {
			return new Album($this->con, $this->albumId);
		}

		public function getPath() {
			return $this->path;
		}

		public function getDuration() {
			return $this->duration;
		}

		public function getMysqliData() {
			return $this->mysqliData;
		}

		public function getGenre() {
			return $this->genre;
		}

	}
?>

~~~~

## Caution

To make sure that, the all classes are working without an error we have to **include** those classes in to the **header.php** file which is found in the **includes** folder.

So, to include those classes go to the **header.php** and add the following liñes to the top of the page and uder the **include("includes/config.php")**.

~~~~

<?php
include("includes/config.php");
include("includes/classes/Artist.php");
include("includes/classes/Album.php");
include("includes/classes/Song.php");

~~~~

## Getting Songs On Album

Now, we want to get the all songs that this album has with their informations. So lets create function in the **includes/classes/Album.php** as **getSongsIds()** which we'll be used to get the all songs ids.

So lets add the following function into the **Album** class.

~~~~

	public function getSongIds() {

		$query = mysqli_query($this->con, "SELECT id FROM songs WHERE album='$this->id' ORDER BY albumOrder ASC");

		$array = array();

		while($row = mysqli_fetch_array($query)) {
			array_push($array, $row['id']);
		}

		return $array;

	}

~~~~

## Show the Songs On Album

To show the songs of the album, add the following lines to the **album.php**.

~~~~

<div class="tracklistContainer">
	<ul class="tracklist">
		
		<?php
		$songIdArray = $album->getSongIds();

		$i = 1;
		foreach($songIdArray as $songId) {
			echo "<li class='tracklistRow'>
					<div class='trackCount'>
						<img class='play' src='assets/images/icons/play-white.png'>
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

			?>

	</ul>
</div>

~~~~

## Styles

Everything is fine up to here, Now we could show all the album information with the songs. So lets style them a little.

The following file includes all the styles - [Link](tutorials/AuthPages/Styled_V4)
