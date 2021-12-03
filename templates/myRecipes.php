<?php 
include 'top.php';
$selectCreated = 'SELECT `pmkRecipeName`, `fldPicture`, `fldRating`, `fldTime`, `fldDescription` FROM `tblRecipe` WHERE `fpkUsername` = "' . $_SESSION['username'] . '"';
$userCreatedRecipes = $thisDatabaseReader->select($selectCreated);
$selectSaved = 'SELECT `fldPicture`, `fldRating`, `fldTime`, `fldDescription` FROM `tblRecipe` '; 
$selectSaved .= 'JOIN `tblUserRecipe` ON `pmkRecipeName`=`fpkRecipeName` ';
$selectSaved .= 'WHERE `fpkUsernameSaved` = "' . $_SESSION['username'] . '"';
$userSavedRecipes = $thisDatabaseReader->select($selectSaved);
if (DEBUG) {
    print_r($userCreatedRecipes);
    print_r($userSavedRecipes);
    print $thisDatabaseReader->displayQuery($selectCreated);
    print $thisDatabaseReader->displayQuery($selectSaved);
}
?>
<main>
    <a href='addRecipe.php'>Add A Recipe!</a>
    <section class='createdRecipes'>
        <?php 
        recipePreview($userCreatedRecipes);
        ?>
    </section>
    <section class='savedRecipes'>
        <?php
        recipePreview($userSavedRecipes);
        ?>
    </section>
</main>