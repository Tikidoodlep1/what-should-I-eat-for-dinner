<?php
include_once("./databaseUtils.php");

// Retrieve data from POST request
$title = $_POST['title'] ?? 'Recipes';
$description = $_POST['description'] ?? 'Here are your selected ingredients:';
$ingredientsString = $_POST['ingredients'] ?? '';

// converting the ingredients string back into an array
$ingredients = !empty($ingredientsString) ? explode(',', $ingredientsString) : [];

if(isset($_SESSION["search_results"])) {
    $title = $_SESSION["title"];
    $description = $_SESSION["description"];
    $ingredients = $_SESSION["selected_ingredients"];
    $searchResults = $_SESSION["search_results"];
}else {
    //Use sessions to prevent requerying the db
    session_cache_expire(30); //cache expires in 30 mins, should be plenty of time
    session_start();
    $db = new DbUtils();
    $db->SetErrorReporting(false);
    //We should add two GenerateRecipes buttons - one to call QueryRecipesIncludingIngredients and one to call QueryRecipesWithIngredients.
    $searchResults = $db->QueryRecipesIncludingIngredients($ingredients);
    $_SESSION["title"] = $title;
    $_SESSION["description"] = $description;
    $_SESSION["selected_ingredients"] = $ingredients;
    $_SESSION["search_results"] = $searchResults;
    session_write_close(); //Make session read-only
}

if (headers_sent($file, $line)) {
    die("Headers already sent in $file on line $line");
}

header("Location: /programs/WhatShouldIEatForDinnerApp/showRecipes.php");
exit();

?>