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