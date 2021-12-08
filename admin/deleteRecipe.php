<?php
include 'top.php';
$user = (isset($_GET['usr'])) ? htmlspecialchars($_GET['usr']) : '';
$recipeName = (isset($_GET['rec'])) ? htmlspecialchars($_GET['rec']) : '';
$recipe = new Recipe($recipeName);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $delete = getData('delete');

    if ($delete) {
        $recipe->deleteRecipe();
        header("Location: displayUser.php?usr=" . $user, true, 303);
        exit();
    } else {
        header("Location: displayUser.php?usr=" . $user, true, 303);
        exit();
    }
}
?>

<main>
    <form method='Post'>
        <h1>Are you sure you want to delete <?php print $recipeName; ?></h1>
        <button name='delete' value='true'>Yes</button>
        <button name='delete' value='false'>No</button>
    </form>
</main>

<?php
include '../templates/footer.php';
?>