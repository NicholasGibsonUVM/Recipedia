<?php 
include 'top.php';
$search = (isset($_GET['search'])) ? htmlspecialchars($_GET['search']) : 0;

$sqlSearch = 'SELECT `tblRecipe`.* FROM `tblRecipe` ';
$sqlSearch .= 'JOIN `tblRecipeIngredient` ON `pmkRecipeName`=`fpkRecipeName` ';
$sqlSearch .= 'JOIN `tblIngredients` ON `pmkIngredientId`=`fpkIngredientId` ';
$sqlSearch .= 'WHERE `fldName` LIKE ? OR `pmkRecipeName` LIKE ? ';
$sqlSearch .= 'GROUP BY `pmkRecipeName` ';
$values = array("%" . $search . "%", "%" . $search . "%");
$results = $thisDatabaseReader->select($sqlSearch, $values);

if (DEBUG) {
    print_r($results);
    print $thisDatabaseReader->displayQuery($sqlSearch, $values);
}
?>
<main>
    <section class='returnedRecipes'>
        <?php
        recipePreview($results);
        ?>
    </section>
</main>
<?php 
include 'footer.php';
?>
