<?php
function getData($field)
{
    if (!isset($_POST[$field])) {
        $data = "";
    } else {
        $data = htmlspecialchars(trim($_POST[$field]));
    }
    return $data;
} 

function recipePreview($recipeArray) {
    foreach ($recipeArray as $recipe) {
        print '<section class="recipePreview">';
        print '<a href="displayRecipe.php?name=' . $recipe['pmkRecipeName'] . '">';
        print '<figure class="recipePicture">' . PHP_EOL;
        print ' <img src="../images/' . $recipe['fldPicture'] . '" alt="' . $recipe['pmkRecipeName'] . '">';
        print '</figure>';
        print '<h1>' . $recipe['pmkRecipeName'] . '</h1>';
        print '<h2>' . timeToString($recipe['fldTime']) . '</h2>';
        print '<p>' . $recipe['fldDescription'] . '</p>';
        print '</a>';
        print '</section>';
    }
}

function recipe($recipeArray, $ingredientArray, $instructionArray) {
    print '<div class=recipeDisplay>';
    foreach ($recipeArray as $recipe) {
        print '<section class="recipeTop">';
        print '<h1>' . $recipe['pmkRecipeName'] . '</h1>';
        print '<figure class="recipePicture">' . PHP_EOL;
        print ' <img src="../images/' . $recipe['fldPicture'] . '" alt="' . $recipe['pmkRecipeName'] . '">';
        print '</figure>';
        print '<p>' . $recipe['fldDescription'] . '</p>';
        print '<h2>' . timeToString($recipe['fldTime']) . '</h2>';
        print '</section>';
    }
    print '<h3>Ingredients</h3>';
    print '<section class="ingredients">';
    foreach ($ingredientArray as $ingredient) {
        print '<section class="recipeIngredient">';
        print '<p>' . $ingredient['fldName'] . ':   ' . $ingredient['fldAmount'] . ' ' . $ingredient['fldUnit'] . '</p>';
        print '</section>';
    }
    print '</section>';
    print '<h3>Instructions</h3>';
    print '<section class="instructions">';
    foreach ($instructionArray as $instruction) {
        print '<section class="recipeInstruction">';
        print '<p><b>' . $instruction['fldOrder'] . '.</b>   ' . $instruction['fldInstructionDescription'] . '</p>';
        print '</section>';
    }
    print '</section>';
    print '</div>';
}

function timeToString($time) {
    $hours = (int) substr($time, 0, 2);
    $minutes = (int) substr($time, 3, 2);
    $timeString = "Time Required: " . strVal($hours) . " Hours " . strval($minutes) . " Minutes";
    return $timeString;
}
?>