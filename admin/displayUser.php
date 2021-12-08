<?php 
include 'top.php';
$user = (isset($_GET['usr'])) ? htmlspecialchars($_GET['usr']) : '';
$selectRecipes = 'SELECT `pmkRecipeName` FROM `tblRecipe` WHERE `fpkUsername`="' . $user . '"';
$recipes = $thisDatabaseReader->select($selectRecipes);
$recipeArray = array();
$counter = 0;
foreach ($recipes as $recipe) {
    $recipe = new Recipe($recipe['pmkRecipeName']);
    $recipeMainArray = $recipe->getMain();
    $recipeArray[$counter] = $recipeMainArray;
    $counter++;
}
?>

<main>
    <h1><?php print $user; ?>'s Recipes</h1>
    <section class=returnedRecipes>
        <?php 
        recipePreviewAdmin($recipeArray, $user);
        ?>
    </section>

</main>


<?php 
include '../templates/footer.php';
?>