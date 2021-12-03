<?php
include 'top.php';
$search = (isset($_GET['name'])) ? htmlspecialchars($_GET['name']) : 0;
$selectRecipe = 'SELECT `pmkRecipeName`, `fldPicture`, `fldRating`, `fldTime`, `fldDescription` FROM `tblRecipe` ';
$selectRecipe .= 'WHERE `pmkRecipeName` = "' . $search . '"';
$recipeMainArray = $thisDatabaseReader->select($selectRecipe);

$selectIngredients = 'SELECT `fldName`, `fldUnit`, `fldAmount` FROM `tblIngredients` ';
$selectIngredients .= 'JOIN `tblRecipeIngredient` ON `pmkIngredientId`=`fpkIngredientId` ';
$selectIngredients .= 'JOIN `tblRecipe` ON `pmkRecipeName`=`fpkRecipeName` ';
$selectIngredients .= 'WHERE `pmkRecipeName` = "' . $search . '"';
$recipeIngredients = $thisDatabaseReader->select($selectIngredients);

$selectInstructions = 'SELECT `fldInstructionDescription`, `fldOrder` FROM `tblInstruction` ';
$selectInstructions .= 'JOIN `tblRecipeInstruction` ON `pmkInstructionId`=`fpkInstructionId` ';
$selectInstructions .= 'JOIN `tblRecipe` ON `pmkRecipeName`=`fpkRecipeName` ';
$selectInstructions .= 'WHERE `pmkRecipeName` = "' . $search . '"';
$recipeInstructions = $thisDatabaseReader->select($selectInstructions);

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $save = getData('save');
    if (DEBUG) {
        print $save;
    }

    if (isset($_SESSION['username'])) {
        $saveValues[0] = $_SESSION['username'];
        $saveValues[1] = $search;
        if ($save == "false") {
            $saveInsert = 'INSERT INTO `tblUserRecipe` SET ';
            $saveInsert .= '`fpkUsernameSaved` = ?, ';
            $saveInsert .= '`fpkName` = ?';
            if (!$saved) {
                $thisDatabaseWriter->insert($saveInsert, $saveValues);
            }
        } else {
            $saveDrop = 'DELETE FROM `tblUserRecipe` WHERE `fpkUsernameSaved` = ? AND `fpkName` = ?';
            $thisDatabaseWriter->delete($saveDrop, $saveValues);
            if (DEBUG) {
                print $thisDatabaseWriter->displayQuery($saveDrop, $saveValues);
            }
        }
    } else {
        header("Location: login.php", true, 303);
        exit();
    }
}

if (isset($_SESSION['username'])) {
    $saveValues[0] = $_SESSION['username'];
    $saveValues[1] = $search;
    $authorValues[0] = $_SESSION['username'];
    $selectCheck = 'SELECT * FROM `tblUserRecipe` WHERE `fpkUsernameSaved` = ? AND `fpkName` = ?';
    $authorCheck = 'SELECT * FROM `tblRecipe` WHERE `fpkUsername` = ?';
    if (count($thisDatabaseReader->select($selectCheck, $saveValues)) > 0) {
        $saved = true;
    }
    if (count($thisDatabaseReader->select($authorCheck, $authorValues)) > 0) {
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
            print '<input type="hidden" name="save" value="false">';
            print '<button>Save Recipe</button>';
            print '</form>';
        } else {
            print '<form method="post">';
            print '<input type="hidden" name="save" value="true">';
            print '<button>Unsave Recipe</button>';
            print '</form>';
        }
    }
    ?>
</main>