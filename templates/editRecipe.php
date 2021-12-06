<?php 
include 'top.php';
$recName = (isset($_GET['rec'])) ? htmlspecialchars($_GET['rec']) : 0;
$recipe = new Recipe($recName);

if (isset($_SESSION['username'])) {
    $authorCheck = 'SELECT * FROM `tblRecipe` WHERE `fpkUsername` = ? AND `pmkRecipeName` = ?';
    $authValues[0] = $_SESSION['username'];
    $authValues[1] = $recName;
    if (!count($thisDatabaseReader->select($authorCheck, $authValues)) > 0) {
        header("Location: index.php", true, 303);
    }
} else {
    header("Location: index.php", true, 303);
}
?>