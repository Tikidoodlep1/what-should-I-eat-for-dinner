<?php
    if(isset($_SESSION["search_results"])) {
        $_SESSION["selected_ingredients"] = null;
        $_SESSION["search_results"] = null;
    }
?>
<!DOCTYPE html>

<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>What Should I Eat For Dinner</title>
    <!-- <link rel="stylesheet" href="css/main.css" /> -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        #suggestions {
            border: 1px solid #ccc;
            max-height: 150px;
            overflow-y: auto;
            display: none;
            position: absolute;
            background: white;
            z-index: 1000;
        }
        .suggestion {
            padding: 10px;
            cursor: pointer;
        }
        .suggestion:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <!-- NAV BANNER -->
	<header>
		<nav>
			<div class="banner">
				<h1 id="title">What Should I Eat For Dinner</h1>
				<p id="description1"> The following application is to be used to figure out what someone should make for dinner! <br> <br>
				Either search which ingredients you have in your home, or for an ingredient of interest to narrow recipes: <br> <br> </p>
			</div>
		</nav>
	</header>
	<main>
		<div id="page">
			<h2>Your Ingredients List: </h2>
			<h2>Total Items: <span id="counter">0</span></h2>
			<ul id="list">
			  <!-- <li>fresh figs <a href="#" class="delete">Delete This Item</a></li> -->
			</ul>
			<div class="button"><a href="#" id="add" class="add"></a></div> <!-- Add button without text -->
		  </div>
          <script src="js/ingredients.js"></script>
		  <script src="js/grocery.js"></script>
	</main>

	<!-- Test for search -->
	<br>
	<div>Search for an ingredient: <br> <br> </div>
	<input type="text" id="search" placeholder="Type here!" autocomplete="off">
    <div id="suggestions"></div>
	
    <div>
        <p>
            <button onclick="addNewIngredient(document.getElementById('search').value)" id="addButton">
                Add Ingredient
            </button>
            <button onclick="generateRecipes('Recipes', 'No ingredients were selected. Showing all recipes: ');" id="generateButton">
                Generate Recipes
            </button>
        </p>
    </div>
    
    <div>
        <p>Try searching for recipes by a dish element: </p>
        <div>
            <button onclick="generateRecipes('Beef Recipes', 'Here are recipes involving beef: ');" style="padding: 10px 20px; font-size: 16px;">
                Beef
            </button>
            <button onclick="generateRecipes('Chicken Recipes', 'Here are recipes involving chicken: ');" style="padding: 10px 20px; font-size: 16px;">
                Chicken
            </button>
            <button onclick="generateRecipes('Fruit Recipes', 'Here are recipes involving fruit: ');" style="padding: 10px 20px; font-size: 16px;">
                Fruit
            </button>
            <br>
            <button onclick="generateRecipes('Vegetable Recipes', 'Here are recipes involving vegetables: ');" style="padding: 10px 20px; font-size: 16px;">
                Vegetables
            </button>
            <button onclick="generateRecipes('Noodle Recipes', 'Here are recipes involving noodles: ');" style="padding: 10px 20px; font-size: 16px;">
                Noodles
            </button>
        </div>
    </div>

    <div id="output"></div>

    <form id="recipeForm" action="getRecipes.php" method="POST" style="display:none;">
        <input type="hidden" name="title" id="titleInput">
        <input type="hidden" name="description" id="descriptionInput">
        <input type="hidden" name="ingredients" id="ingredientsInput">
    </form>
    
    <script>
    function generateRecipes(title, description) {
    console.log("Generate Recipes called");

    const ingredients = selectedIngredients; // Ensure this is an array of selected ingredients

    if(ingredients && ingredients.length > 0) {
        // Set the values in the hidden form inputs
        document.getElementById('titleInput').value = title;
        document.getElementById('descriptionInput').value = description;

        // Prepare ingredients as a comma-separated string
        document.getElementById('ingredientsInput').value = ingredients.join(',');

        // Submit the form
        document.getElementById('recipeForm').submit();
    }
}
    </script>
</body>
</html>