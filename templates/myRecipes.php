<?php 
include 'top.php';
if (!isset($_SESSION['username'])) {
    header("Location: login.php", true, 303);
}
$selectCreated = 'SELECT `pmkRecipeName`, `fldPicture`, `fldRating`, `fldTime`, `fldDescription` FROM `tblRecipe` WHERE `fpkUsername` = "' . $_SESSION['username'] . '"';
$userCreatedRecipes = $thisDatabaseReader->select($selectCreated);
$selectSaved = 'SELECT `pmkRecipeName`, `fldPicture`, `fldRating`, `fldTime`, `fldDescription` FROM `tblRecipe` '; 
$selectSaved .= 'JOIN `tblUserRecipe` ON `pmkRecipeName`=`fpkName` ';
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
    <h1>Created Recipes</h1>
    <section class='createdRecipes'>
        <?php 
        recipePreview($userCreatedRecipes);
        ?>
    </section>
    <h1>Saved Recipes</h1>
    <section class='savedRecipes'>
        <?php
        recipePreview($userSavedRecipes);
        ?>
    </section>
</main>
<?php 
include 'footer.php';
?>
