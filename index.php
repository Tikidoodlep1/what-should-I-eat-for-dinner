<!DOCTYPE html>

<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <?php
    	$title = basename($_SERVER['PHP_SELF'], ".php");
    	if($title === "index") {
    		$title = "What Should I Eat For Dinner";
    	}
    	echo "<title>$title</title>";
    ?>
    <link rel="stylesheet" href="./main_style.css">
</head>
<body>
	<!-- NAV BANNER -->
	<header>
		<div class="banner">
			<h1 id="title">What Should I Eat For Dinner</h1>
			<nav>
				<ul>
					<!-- Insert different pages here for searching, saved searches, saves recipes, etc if we implement them -->
				</ul>
			</nav>
		</div>
	</header>
	<main>
		<!-- Checkboxes, body stuff -->
		<div id="page">
			<h2>Your Ingredients List: </h2>
			<h2>Total Items: <span id="counter">0</span></h2>
			<ul id="list">
			  <!-- <li>fresh figs <a href="#" class="delete">Delete This Item</a></li> -->
			</ul>
			<div class="button"><a href="#" id="add" class="add"></a></div> <!-- Add button without text -->
		</div>
		<div>Search for an ingredient: <br> <br> </div>
		<input type="text" id="search" placeholder="Type here!" autocomplete="off">
	</main>
</body>
</html>