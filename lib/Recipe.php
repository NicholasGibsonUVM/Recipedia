<?php
class Recipe
{
    //Ask about making these static
    public function __construct($recipeName)
    {
        $this->recipeDatabaseReader = new Database('nsgibson_reader', 'r', 'NSGIBSON_cs148_final');
        $this->recipeDatabaseWriter = new Database('nsgibson_writer', 'w', 'NSGIBSON_cs148_final');
        $selectRecipe = 'SELECT * FROM `tblRecipe` ';
        $selectRecipe .= 'WHERE `pmkRecipeName` = "' . $recipeName . '"';
        $this->recipeMainArray = $this->recipeDatabaseReader->select($selectRecipe);

        $selectIngredients = 'SELECT * FROM `tblIngredients` ';
        $selectIngredients .= 'JOIN `tblRecipeIngredient` ON `pmkIngredientId`=`fpkIngredientId` ';
        $selectIngredients .= 'JOIN `tblRecipe` ON `pmkRecipeName`=`fpkRecipeName` ';
        $selectIngredients .= 'WHERE `pmkRecipeName` = "' . $recipeName . '"';
        $this->recipeIngredients = $this->recipeDatabaseReader->select($selectIngredients);

        $selectInstructions = 'SELECT * FROM `tblInstruction` ';
        $selectInstructions .= 'JOIN `tblRecipeInstruction` ON `pmkInstructionId`=`fpkInstructionId` ';
        $selectInstructions .= 'JOIN `tblRecipe` ON `pmkRecipeName`=`fpkRecipeName` ';
        $selectInstructions .= 'WHERE `pmkRecipeName` = "' . $recipeName . '"';
        $this->recipeInstructions = $this->recipeDatabaseReader->select($selectInstructions);

        $selectSaved = 'SELECT * FROM `tblUserRecipe` WHERE `fpkRecipeName` = "' . $recipeName . '"';
        $this->usersSaved = $this->recipeDatabaseReader->select($selectSaved);
    }

    public function getMain() {
        return $this->recipeMainArray;
    }

    public function getIngredients() {
        return $this->recipeIngredients;
    }

    public function getInstructions() {
        return $this->recipeInstructions;
    }

    public function deleteRecipe() {
        foreach ($this->recipeIngredients as $ingredient) {
            $values[0] = $ingredient['pmkIngredientId'];
            $deleteIngredient = 'DELETE FROM `tblIngredients` WHERE `pmkIngredientId`=?';
            $deleteReference = 'DELETE FROM `tblRecipeIngredient` WHERE `fpkIngredientId`=?';
            $this->recipeDatabaseWriter->delete($deleteIngredient, $values);
            $this->recipeDatabaseWriter->delete($deleteReference, $values);
        }
        foreach ($this->recipeInstructions as $instruction) {
            $values[0] = $instruction['pmkInstructionId'];
            $deleteInstruction = 'DELETE FROM `tblInstruction` WHERE `pmkInstructionId`=?';
            $deleteReference = 'DELETE FROM `tblRecipeInstruction` WHERE `fpkInstructionId`=?';
            $this->recipeDatabaseWriter->delete($deleteInstruction, $values);
            $this->recipeDatabaseWriter->delete($deleteReference, $values);
        }
        foreach ($this->usersSaved as $save) {
            $values[0] = $save['fpkUsernameSaved'];
            $deleteSave = 'DELETE FROM `tblUserRecipe` WHERE `fpkUsernameSaved`=?';
            $this->recipeDatabaseWriter->delete($deleteSave, $values);
        }
        foreach ($this->recipeMainArray as $recipe) {
            $values[0] = $recipe['pmkRecipeName'];
            $deleteRecipe = 'DELETE FROM `tblRecipe` WHERE `pmkRecipeName`=?';
            $this->recipeDatabaseWriter->delete($deleteRecipe, $values);
        }
    }
}
