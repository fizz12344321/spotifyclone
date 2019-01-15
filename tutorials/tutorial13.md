# Getting Albums From Database

In this tutorial, we are going to learn how to get the albums from the database and show them in the **index.php** page.

## Show the albums

To get and show the albums from the database we have to write a query. So lets add the following lines to the **index.php** file.

~~~~

	<?php
		$albumQuery = mysqli_query($con, "SELECT * FROM albums ORDER BY RAND() LIMIT 10");

		while($row = mysqli_fetch_array($albumQuery)) {
			



			echo "<div class='gridViewItem'>
					<a href='album.php?id=" . $row['id'] . "'> //whenever clicked this it will be go to the album page
						<img src='" . $row['artworkPath'] . "'>

						<div class='gridViewInfo'>"
							. $row['title'] .
						"</div>
					</a>

				</div>";



		}
	?>

~~~~

**$row = mysqli_fetch_array($albumQuery))** means in every iteration this loop will create an array of the row. As you know there could be a more than one row returned in the database like as follows.

1 Bacon and Eggs 2 4 assets/images/artwork/clearday.jpg
2 Pizza head 5 10 assets/images/artwork/energy.jpg
3 Summer Hits 3 1 assets/images/artwork/goinghigher.jpg
4 The movie soundtrack 2 9 assets/images/artwork/funkyelement.jpg
5 Best of the Worst 1 3 assets/images/artwork/popdance.jpg

and whenever its iterated it will create an array which holds the information of the row like **row 1** then **row 2** and so forth.

Then we've printed those valuses with the **echo** in the php.
