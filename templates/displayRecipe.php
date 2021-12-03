<?php 
include 'top.php';
$search = (isset($_GET['name'])) ? htmlspecialchars($_GET['name']) : 0;
$selectRecipe = 'SELECT `pmkRecipeName`, `fldPicture`, `fldRating`, `fldTime`, `fldDescription` FROM `tblRecipe` ';
$selectRecipe .= 'WHERE `pmkRecipeName` = "' . $search . '"';
$recipeMainArray = $thisDatabaseReader->select($selectRecipe);

$selectIngredients = 'SELECT `fldName`, `fldUnit`, `fldAmount` FROM `tblIngredients` ';
$selectIngredients .= 'JOIN `tblRecipeIngredient` ON `pmkIngredientId`=`fpkIngredientId` ';
$selectIngredients .= 'JOIN `tblRecipe` ON `pmkRecipeName`=`fpkRecipeName` ';
$selectIngredients .= 'WHERE `pmkRecipeName` = "' . $search . '"';
$recipeIngredients = $thisDatabaseReader->select($selectIngredients);

$selectInstructions = 'SELECT `fldInstructionDescription`, `fldOrder` FROM `tblInstruction` ';
$selectInstructions .= 'JOIN `tblRecipeInstruction` ON `pmkInstructionId`=`fpkInstructionId` ';
$selectInstructions .= 'JOIN `tblRecipe` ON `pmkRecipeName`=`fpkRecipeName` ';
$selectInstructions .= 'WHERE `pmkRecipeName` = "' . $search . '"';
$recipeInstructions = $thisDatabaseReader->select($selectInstructions);

if (DEBUG) {
    print '<p>';
    print_r($recipeMainArray);
    print '</p>';
    print '<p>';
    print_r($recipeIngredients);
    print '</p>';
    print '<p>';
    print_r($recipeInstructions);
    print '</p>';
    print $thisDatabaseReader->displayQuery($selectRecipe);
    print $thisDatabaseReader->displayQuery($selectIngredients);
    print $thisDatabaseReader->displayQuery($selectInstructions);
}
?>
<main>
    <?php
        recipe($recipeMainArray, $recipeIngredients, $recipeInstructions);
    ?>
</main>