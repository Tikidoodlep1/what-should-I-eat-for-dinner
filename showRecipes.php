<?php
session_start();

if(isset($_SESSION["search_results"])) {
    $title = $_SESSION["title"];
    $description = $_SESSION["description"];
    $ingredients = $_SESSION["selected_ingredients"];
    $searchResults = $_SESSION["search_results"];
}else {
    header("Location: /programs/WhatShouldIEatForDinnerApp/index.php");
    session_abort();
    exit();
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
                    <th>Recipe Image</th>
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