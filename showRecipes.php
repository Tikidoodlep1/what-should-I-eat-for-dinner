
<?php
include_once("./databaseUtils.php");
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

//DB STUFF ONLY BELOW THIS LINE
$db = new DbUtils();
$db->SetErrorReporting(true);
//We should add two GenerateRecipes buttons - one to call QueryRecipesIncludingIngredients and one to call QueryRecipesWithIngredients.
$searchResults = $db->QueryRecipesIncludingIngredients($ingredients);
//var_dump($searchResults);
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
        <section id="selected-ingredients">
            <h2>Your Selected Ingredients:</h2>
            <ul>
                <?php if (!empty($ingredients)) { ?>
                    <?php foreach ($ingredients as $ingredient): ?>
                        <li><?php echo htmlspecialchars(trim($ingredient)); ?></li>
                    <?php endforeach; ?>
                <?php }else { ?>
                    <li>No ingredients were selected!</li>
                <?php } ?>
            </ul>
        </section>
        <section id="recipes-table">
            <table>
                <thead>
                    <th>Recipe</th>
                    <th>Ingredients</th>
                    <th>Instructions</th>
                </thead>
                <tbody>
                    <?php
                        for($i = 0; $i < count($searchResults["id"]); $i++) {
                    ?>
                        <tr>
                    <?php
                        $imgData = "<td><img loading='lazy' src='./img/" . $searchResults["img_name"][$i] . ".jpg'></td>";
                        $titleData = "<td class='recipe-title'>" . $searchResults["title"][$i] . "</td>";
                        $ingredientsData = "<td class='recipe-ingredients'>" . $searchResults["ingredients"][$i] . "</td>";
                        $instructionsData = "<td class='recipe-instructions'>" . $searchResults["instructions"][$i] . "</td>";
                        echo $imgData . $titleData . $ingredientsData . $instructionsData;
                    ?>
                        </tr>
                    <?php
                        }    
                    ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>



