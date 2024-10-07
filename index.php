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
	</main>
</body>
</html>
