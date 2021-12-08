<?php 
include 'top.php';
$user = (isset($_GET['usr'])) ? htmlspecialchars($_GET['usr']) : '';
$selectRecipes = 'SELECT `pmkRecipeName` FROM `tblRecipe` WHERE `fpkUsername` = ?';
$createdRecipes = $thisDatabaseReader->select($selectRecipes, array($user));

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $delete = getData('delete');

    if ($delete) {
        foreach ($createdRecipes as $recipe) {
            $tempRec = new Recipe($recipe['pmkRecipeName']);
            $tempRec->deleteRecipe();
        }
        $deleteSaves = 'DELETE FROM `tblUserRecipe` WHERE `fpkUsernameSaved` = ?';
        $thisDatabaseWriter->delete($deleteSaves, array($user));
        $deleteUser = 'DELETE FROM `tblUser` WHERE `pmkUsername` = ?';
        $thisDatabaseWriter->delete($deleteUser, array($user));
        header("Location: users.php", true, 303);
        exit();
    } else {
        header("Location: users.php", true, 303);
        exit();
    }
}
?>

<main>
<form method='Post'>
        <h1>Are you sure you want to delete <?php print $user; ?></h1>
        <button name='delete' value='true'>Yes</button>
        <button name='delete' value='false'>No</button>
    </form> 
</main>

<?php 
include '../templates/footer.php';
?>