<?php 
include 'top.php';
$user = (isset($_GET['usr'])) ? htmlspecialchars($_GET['usr']) : '';
$recipeName = (isset($_GET['rec'])) ? htmlspecialchars($_GET['rec']) : '';
$recipe = new Recipe($recipeName);
$recipeMainArray = $recipe->getMain();
$recipeIngredients = $recipe->getIngredients();
$recipeInstructions = $recipe->getInstructions();
?>

<main>

</main>


<?php 
include '../templates/footer.php';
?>