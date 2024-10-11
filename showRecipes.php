
<?php
/*
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
*/

// Retrieve data from POST request
$title = $_POST['title'] ?? 'Recipes';
$description = $_POST['description'] ?? 'Here are your selected ingredients:';
$ingredientsString = $_POST['ingredients'] ?? '';

// converting the ingredients string back into an array
$ingredients = !empty($ingredientsString) ? explode(',', $ingredientsString) : [];

?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($title); ?></title>
</head>
<body>
    <header>
        <h1><?php echo htmlspecialchars($title); ?></h1>
        <p><?php echo htmlspecialchars($description); ?></p>
    </header>
    <main>
        <h2>Your Selected Ingredients:</h2>
        <ul>
            <?php if (!empty($ingredients)): ?>
                <?php foreach ($ingredients as $ingredient): ?>
                    <li><?php echo htmlspecialchars(trim($ingredient)); ?></li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>No ingredients were selected!</li>
            <?php endif; ?>
        </ul>
    </main>
</body>
</html>



