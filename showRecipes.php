<?php
// Get title and description from the URL
$title = isset($_GET['title']) ? $_GET['title'] : 'Recipes:';
$description = isset($_GET['description']) ? $_GET['description'] : 'Based on the ingredients selected, here are the following recipes: ';

echo "<title> $title </title>";

echo "<h1>$title</h1>";
if (isset($_GET['ingredient'])) { // change to post
    // Collect all ingredients from the URL
    $ingredients = $_GET['ingredient'];
    echo "You searched for: " . implode(", ", $ingredients);
}
echo "<p>$description</p>";
?>


