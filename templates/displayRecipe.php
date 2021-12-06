<?php
include 'top.php';
$search = (isset($_GET['name'])) ? htmlspecialchars($_GET['name']) : 0;
$recipe = new Recipe($search);
$recipeMainArray = $recipe->getMain();
$recipeIngredients = $recipe->getIngredients();
$recipeInstructions = $recipe->getInstructions();

if (DEBUG) {
    print '<p>';
    print_r($recipeMainArray);
    print '</p>';
    print '<p>';
    print_r($recipeIngredients);
    print '</p>';
    print '<p>';
    print_r($recipeInstructions);
    print '</p>';
    print $thisDatabaseReader->displayQuery($selectRecipe);
    print $thisDatabaseReader->displayQuery($selectIngredients);
    print $thisDatabaseReader->displayQuery($selectInstructions);
}

$saved = false;
$author = false;
$save = false;
$edit = false;
$delete = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $save = getData('save');
    $change = getData('change');

    if (DEBUG) {
        print $save;
    }

    if (isset($_SESSION['username'])) {
        $saveValues[0] = $_SESSION['username'];
        $saveValues[1] = $search;
        if ($save == "true") {
            $saveInsert = 'INSERT INTO `tblUserRecipe` SET ';
            $saveInsert .= '`fpkUsernameSaved` = ?, ';
            $saveInsert .= '`fpkName` = ?';
            if (!$saved) {
                $thisDatabaseWriter->insert($saveInsert, $saveValues);
            }
        } else if ($save == "false") {
            $saveDrop = 'DELETE FROM `tblUserRecipe` WHERE `fpkUsernameSaved` = ? AND `fpkName` = ?';
            $thisDatabaseWriter->delete($saveDrop, $saveValues);
            if (DEBUG) {
                print $thisDatabaseWriter->displayQuery($saveDrop, $saveValues);
            }
        } else if ($change == "Edit") {
            header("Location: editRecipe.php?rec=" . $search, true, 303);
            exit();
        } else if ($change == "Delete") {
            header("Location: delRecipe.php?rec=" . $search, true, 303);
            exit();
        }
    } else {
        header("Location: login.php", true, 303);
        exit();
    }
}

if (isset($_SESSION['username'])) {
    $saveValues[0] = $_SESSION['username'];
    $saveValues[1] = $search;
    $selectCheck = 'SELECT * FROM `tblUserRecipe` WHERE `fpkUsernameSaved` = ? AND `fpkName` = ?';
    $authorCheck = 'SELECT * FROM `tblRecipe` WHERE `fpkUsername` = ? AND `pmkRecipeName` = ?';
    if (count($thisDatabaseReader->select($selectCheck, $saveValues)) > 0) {
        $saved = true;
    }
    if (count($thisDatabaseReader->select($authorCheck, $saveValues)) > 0) {
        $author = true;
    }
}

?>
<main>
    <?php
    recipe($recipeMainArray, $recipeIngredients, $recipeInstructions);
    if (!$author) {
        if (!$saved) {
            print '<form method="post">';
            print '<input type="hidden" name="save" value="true">';
            print '<button>Save Recipe</button>';
            print '</form>';
        } else {
            print '<form method="post">';
            print '<input type="hidden" name="save" value="false">';
            print '<button>Unsave Recipe</button>';
            print '</form>';
        }
    } else {
        print '<form method="post">';
        print '<input type="submit" name="change" value="Edit">';
        print '<input type="submit" name="change" value="Delete">';
        print '</form>';
    }
    ?>
</main>