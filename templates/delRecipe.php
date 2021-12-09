<?php 
include 'top.php';
$recName = (isset($_GET['rec'])) ? htmlspecialchars($_GET['rec']) : 0;
$recipe = new Recipe($recName);

if (isset($_SESSION['username'])) {
    $authorCheck = 'SELECT * FROM `tblRecipe` WHERE `fpkUsername` = ? AND `pmkRecipeName` = ?';
    $authValues[0] = $_SESSION['username'];
    $authValues[1] = $recName;
    if (!count($thisDatabaseReader->select($authorCheck, $authValues)) > 0) {
        header("Location: displayRecipe.php?name=" . $recName, true, 303);
    }
} else {
    header("Location: displayRecipe.php?name=" . $recName, true, 303);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $delete = getData('delete');

    if ($delete == 'true') {
        $recipe->deleteRecipe();
        header("Location: myRecipes.php", true, 303);
        exit();
    } else {
        header("Location: displayRecipe.php?name=" . $recName, true, 303);
        exit();
    }
}
?>
<main>
    <form method='Post'>
        <h1>Are you sure you want to delete <?php print $recName; ?></h1>
        <button name='delete' value='true'>Yes</button>
        <button name='delete' value='false'>No</button>
    </form> 
</main>
<?php 
include 'footer.php';
?>
