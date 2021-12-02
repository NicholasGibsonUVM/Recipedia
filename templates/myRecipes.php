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
        foreach ($userCreatedRecipes as $userCreatedRecipe) {
            print '<section class="createdRecipe">';
            print '<a href="displayRecipe?name=' . $userCreatedRecipe['pmkRecipeName'] . '">';
            print '<figure>' . PHP_EOL;
            print ' <img src="../images/' . $userCreatedRecipe['fldPicture'] . '" alt="' . $userCreatedRecipe['pmkRecipeName'] . '">';
            print '</figure>';
            print '<h1>' . $userCreatedRecipe['pmkRecipeName'] . '</h1>';
            print '<h2>' . $userCreatedRecipe['fldTime'] . '</h2>';
            print '<p>' . $userCreatedRecipe['fldDescription'] . '</p>';
            print '</a>';
            print '</section>';
        }
        ?>
    </section>
    <section class='savedRecipes'>

    </section>
</main>