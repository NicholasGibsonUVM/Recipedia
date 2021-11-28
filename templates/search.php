<?php 
include 'top.php';
$search = (isset($_GET['search'])) ? htmlspecialchars($_GET['search']) : 0;

$sqlSearch = 'SELECT `tblRecipe`.*, `tblRecipeIngredient`.`fpkIngredientName`, `tblRecipeIngredient`.`fldAmount`
FROM `tblRecipe`
JOIN `tblRecipeIngredient` ON `pmkRecipeName`=`fpkRecipeName` 
WHERE `fpkIngredientName` LIKE "%?%";';

if ($search != 0) {
    $values = array($search);
    $thisDatabaseReader->select($sqlSearch, $values);
}


?>