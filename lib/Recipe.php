<?php
class Recipe
{
    public function __construct($recipeName)
    {
        $this->recipeDatabaseReader = new Database('nsgibson_reader', 'r', 'NSGIBSON_cs148_final');
        $this->recipeDatabaseWriter = new Database('nsgibson_writer', 'w', 'NSGIBSON_cs148_final');
        $this->recipeName = $recipeName;

        $this->update();
    }

    //Setter Helper Functions
    public function setMain()
    {
        $selectRecipe = 'SELECT * FROM `tblRecipe` ';
        $selectRecipe .= 'WHERE `pmkRecipeName` = "' . $this->recipeName . '"';
        $this->recipeMainArray = $this->recipeDatabaseReader->select($selectRecipe);
    }

    public function setIngredients()
    {
        $selectIngredients = 'SELECT * FROM `tblIngredients` ';
        $selectIngredients .= 'JOIN `tblRecipeIngredient` ON `pmkIngredientId`=`fpkIngredientId` ';
        $selectIngredients .= 'JOIN `tblRecipe` ON `pmkRecipeName`=`fpkRecipeName` ';
        $selectIngredients .= 'WHERE `pmkRecipeName` = "' . $this->recipeName . '"';
        $this->recipeIngredients = $this->recipeDatabaseReader->select($selectIngredients);
    }

    public function setInstructions()
    {
        $selectInstructions = 'SELECT * FROM `tblInstruction` ';
        $selectInstructions .= 'JOIN `tblRecipeInstruction` ON `pmkInstructionId`=`fpkInstructionId` ';
        $selectInstructions .= 'JOIN `tblRecipe` ON `pmkRecipeName`=`fpkRecipeName` ';
        $selectInstructions .= 'WHERE `pmkRecipeName` = "' . $this->recipeName . '"';
        $this->recipeInstructions = $this->recipeDatabaseReader->select($selectInstructions);
    }

    public function setSaved()
    {
        $selectSaved = 'SELECT * FROM `tblUserRecipe` WHERE `fpkRecipeName` = "' . $this->recipeName . '"';
        $this->usersSaved = $this->recipeDatabaseReader->select($selectSaved);
    }

    public function update()
    {
        $this->setMain();
        $this->setIngredients();
        $this->setInstructions();
        $this->setSaved();
    }

    //Getter Functions
    public function getMain()
    {
        return $this->recipeMainArray;
    }

    public function getIngredients()
    {
        return $this->recipeIngredients;
    }

    public function getInstructions()
    {
        return $this->recipeInstructions;
    }

    //Delete Functions
    public function deleteIngredient($ingredient)
    {
        $values[0] = $ingredient['pmkIngredientId'];
        $deleteIngredient = 'DELETE FROM `tblIngredients` WHERE `pmkIngredientId`=?';
        $deleteReference = 'DELETE FROM `tblRecipeIngredient` WHERE `fpkIngredientId`=?';
        if (!$this->recipeDatabaseWriter->delete($deleteIngredient, $values) || !$this->recipeDatabaseWriter->delete($deleteReference, $values)) {
            return false;
        }
        return true;
    }

    public function deleteInstruction($instruction)
    {
        $values[0] = $instruction['pmkInstructionId'];
        $deleteInstruction = 'DELETE FROM `tblInstruction` WHERE `pmkInstructionId`=?';
        $deleteReference = 'DELETE FROM `tblRecipeInstruction` WHERE `fpkInstructionId`=?';
        if (!$this->recipeDatabaseWriter->delete($deleteInstruction, $values) || !$this->recipeDatabaseWriter->delete($deleteReference, $values)) {
            return false;
        }
        return true;
    }

    public function deleteRecipe()
    {
        $this->recipeDatabaseWriter->transactionStart();
        $fail = false;
        foreach ($this->recipeIngredients as $ingredient) {
            if (!$this->deleteIngredient($ingredient)) {
                $this->recipeDatabaseWriter->transactionFailed();
                $fail = true;
            }
        }
        foreach ($this->recipeInstructions as $instruction) {
            if (!$this->deleteInstruction($instruction)) {
                $this->recipeDatabaseWriter->transactionFailed();
                $fail = true;
            }
        }
        foreach ($this->usersSaved as $save) {
            $values[0] = $save['fpkUsernameSaved'];
            $deleteSave = 'DELETE FROM `tblUserRecipe` WHERE `fpkUsernameSaved`=?';
            if (!$this->recipeDatabaseWriter->delete($deleteSave, $values)) {
                $this->recipeDatabaseWriter->transactionFailed();
                $fail = true;
            }
        }
        foreach ($this->recipeMainArray as $recipe) {
            $values[0] = $recipe['pmkRecipeName'];
            $deleteRecipe = 'DELETE FROM `tblRecipe` WHERE `pmkRecipeName`=?';
            if (!$this->recipeDatabaseWriter->delete($deleteRecipe, $values)) {
                $this->recipeDatabaseWriter->transactionFailed();
                $fail = true;
            }
        }
        if (!$fail) {
            $this->recipeDatabaseWriter->transactionComplete();
        }
    }

    //Insert/Update Functions
    public function insertMain($main)
    {
        $recipeInsert = 'INSERT INTO `tblRecipe` SET ';
        $recipeInsert .= '`pmkRecipeName` = ?, ';
        $recipeInsert .= '`fldPicture` = ?, ';
        $recipeInsert .= '`fldRating` = ?, ';
        $recipeInsert .= '`fldTime` = ?, ';
        $recipeInsert .= '`fldDescription` = ?, ';
        $recipeInsert .= '`fpkUsername` = ?';
        $recipeValues[0] = $main['name'];
        $recipeValues[1] = $main['pictureLoc'];
        $recipeValues[2] = $main['rating'];
        $recipeValues[3] = $main['time'];
        $recipeValues[4] = $main['description'];
        $recipeValues[5] = $main['author'];
        if (!$this->recipeDatabaseWriter->insert($recipeInsert, $recipeValues)) {
            if (DEBUG) {
                print $this->recipeDatabaseWrite->displayQuery($recipeInsert, $recipeValues);
            }
            return false;
        }
        return true;
    }

    public function updateMain($main)
    {
        $recipeInsert = 'UPDATE `tblRecipe` SET ';
        $recipeInsert .= '`pmkRecipeName` = ?, ';
        $recipeInsert .= '`fldPicture` = ?, ';
        $recipeInsert .= '`fldRating` = ?, ';
        $recipeInsert .= '`fldTime` = ?, ';
        $recipeInsert .= '`fldDescription` = ? WHERE `pmkRecipeName` = ?';
        $recipeValues[0] = $main['name'];
        $recipeValues[1] = $main['pictureLoc'];
        $recipeValues[2] = $main['rating'];
        $recipeValues[3] = $main['time'];
        $recipeValues[4] = $main['description'];
        $recipeValues[5] = $this->recipeMainArray[0]['pmkRecipeName'];
        if (!$this->recipeDatabaseWriter->update($recipeInsert, $recipeValues)) {
            if (DEBUG) {
                print $this->recipeDatabaseWriter->displayQuery($recipeInsert, $recipeValues);
            }
            return false;
        }
        $this->recipeMainArray[0]['pmkRecipeName'] = $main['name'];
        return true;
    }

    public function insertIngredient($ingredient)
    {
        $ingredientInsert = 'INSERT INTO `tblIngredients` SET `fldName` = ?, `fldUnit` = ?';
        $ingredientValues[0] = $ingredient['fldName'];
        $ingredientValues[1] = $ingredient['fldUnit'];
        if (!$this->recipeDatabaseWriter->insert($ingredientInsert, $ingredientValues)) {
            if (DEBUG) {
                print $this->recipeDatabaseWrite->displayQuery($ingredientInsert, $ingredientValues);
            }
            return false;
        }
        $selectSQL = 'SELECT LAST_INSERT_ID()';
        $id = $this->recipeDatabaseWriter->select($selectSQL);
        $recipeIngredientInsert = 'INSERT INTO `tblRecipeIngredient` SET `fpkRecipeName` = ?, `fpkIngredientId` = ?, `fldAmount` = ?';
        $recipeIngredientValues[0] = $this->recipeMainArray[0]['pmkRecipeName'];
        $recipeIngredientValues[1] = (int) $id[0]['LAST_INSERT_ID()'];
        $recipeIngredientValues[2] = $ingredient['fldAmount'];
        if (!$this->recipeDatabaseWriter->insert($recipeIngredientInsert, $recipeIngredientValues)) {
            if (DEBUG) {
                print $this->recipeDatabaseWrite->displayQuery($recipeIngredientInsert, $recipeIngredientValues);
            }
            return false;
        }
        return true;
    }

    public function updateIngredient($ingredient, $ingredientId)
    {
        $ingredientUpdate = 'UPDATE `tblIngredients` SET `fldName` = ?, `fldUnit` = ? WHERE `pmkIngredientId`=?';
        $values[0] = $ingredient['fldName'];
        $values[1] = $ingredient['fldUnit'];
        $values[2] = $ingredientId;
        if (!$this->recipeDatabaseWriter->update($ingredientUpdate, $values)) {
            if (DEBUG) {
                print $this->recipeDatabaseWrite->displayQuery($ingredientUpdate, $values);
            }
            return false;
        }
        $recipeIngredientUpdate = 'UPDATE `tblRecipeIngredient` SET `fpkRecipeName` = ?, `fldAmount` = ? WHERE `fpkIngredientId`=?';
        $values2[0] = $this->recipeMainArray[0]['pmkRecipeName'];
        $values2[1] = $ingredient['fldAmount'];
        $values2[2] = $ingredientId;
        if (!$this->recipeDatabaseWriter->update($recipeIngredientUpdate, $values2)) {
            if (DEBUG) {
                print $this->recipeDatabaseWrite->displayQuery($recipeIngredientUpdate, $values2);
            }
            return false;
        }
        return true;
    }

    public function insertInstruction($instruction, $order)
    {
        $instructionInsert = 'INSERT INTO `tblInstruction` SET `fldInstructionDescription` = ?';
        $instructionValues[0] = $instruction['fldInstructionDescription'];
        if (!$this->recipeDatabaseWriter->insert($instructionInsert, $instructionValues)) {
            if (DEBUG) {
                print $this->recipeDatabaseWrite->displayQuery($instructionInsert, $instructionValues);
            }
            return false;
        }
        $selectSQL = 'SELECT LAST_INSERT_ID()';
        $id = $this->recipeDatabaseWriter->select($selectSQL);
        $recipeInstructionInsert = 'INSERT INTO `tblRecipeInstruction` SET `fpkRecipeName` = ?, `fpkInstructionId` = ?, `fldOrder` = ?';
        $recipeInstructionValues[0] = $this->recipeMainArray[0]['pmkRecipeName'];
        $recipeInstructionValues[1] = (int) $id[0]['LAST_INSERT_ID()'];
        $recipeInstructionValues[2] = (int) $order;
        if (!$this->recipeDatabaseWriter->insert($recipeInstructionInsert, $recipeInstructionValues)) {
            if (DEBUG) {
                print $this->recipeDatabaseWrite->displayQuery($recipeInstructionInsert, $recipeInstructionValues);
            }
            return false;
        }
        return true;
    }

    public function updateInstruction($instruction, $order, $instructionId)
    {
        $instructionUpdate = 'UPDATE `tblInstruction` SET `fldInstructionDescription` = ? WHERE `pmkInstructionId`=?';
        $instructionValues[0] = $instruction['fldInstructionDescription'];
        $instructionValues[1] = $instructionId;
        if (!$this->recipeDatabaseWriter->update($instructionUpdate, $instructionValues)) {
            if (DEBUG) {
                print $this->recipeDatabaseWrite->displayQuery($instructionUpdate, $instructionValues);
            }
            return false;
        }
        $recipeInstructionUpdate = 'UPDATE `tblRecipeInstruction` SET `fpkRecipeName` = ?, `fpkInstructionId` = ?, `fldOrder` = ? WHERE `fpkInstructionId`=?';
        $recipeInstructionValues[0] = $this->recipeMainArray[0]['pmkRecipeName'];
        $recipeInstructionValues[1] = (int) $instructionId;
        $recipeInstructionValues[2] = (int) $order;
        $recipeInstructionValues[3] = (int) $instructionId;
        if (!$this->recipeDatabaseWriter->update($recipeInstructionUpdate, $recipeInstructionValues)) {
            if (DEBUG) {
                print $this->recipeDatabaseWrite->displayQuery($recipeInstructionUpdate, $instructionValues);
            }
            return false;
        }
        return true;
    }

    public function editRecipe(array $main, array $ingredients, array $instructions)
    {
        $this->recipeDatabaseWriter->transactionStart();
        //Main
        $fail = false;
        if (!$this->updateMain($main)) {
            $fail = true;
        }
        $this->recipeMainArray[0]['pmkRecipeName'] = $main['name'];

        //Ingredients
        $counter = 0;
        foreach ($ingredients as $ingredient) {
            if ($counter < count($this->recipeIngredients)) {
                if (!$this->updateIngredient($ingredient, $this->recipeIngredients[$counter]['pmkIngredientId'])) {
                    $fail = true;
                }
                if (DEBUG) {
                    print '<p>' . $counter . 'Ingredient Updated</p>';
                }
            } else {
                if (!$this->insertIngredient($ingredient)) {
                    $fail = true;
                }
                if (DEBUG) {
                    print '<p>' . $counter . 'Ingredient Inserted</p>';
                }
            }
            $counter++;
        }
        //Counter - 1 since the counter will gain an extra after the last insert or update
        for ($i = $counter; $i < count($this->recipeIngredients); $i++) {
            $this->deleteIngredient($this->recipeIngredients[$i]);
            if (DEBUG) {
                print '<p>' . $counter . 'Ingredient Deleted</p>';
            }
        }

        //Instructions
        $counter = 0;
        foreach ($instructions as $instruction) {
            if ($counter < count($this->recipeInstructions)) {
                if (!$this->updateInstruction($instruction, $counter + 1, $this->recipeInstructions[$counter]['pmkInstructionId'])) {
                    $fail = true;
                }
                if (DEBUG) {
                    print '<p>' . $counter . 'Instruction Updated</p>';
                }
            } else {
                if (!$this->insertInstruction($instruction, $counter + 1)) {
                    $fail = true;
                }
                if (DEBUG) {
                    print '<p>' . $counter . 'Instruction Inserted</p>';
                }
            }
            $counter++;
        }
        //Counter - 1 since the counter will gain an extra after the last insert or update
        for ($i = $counter; $i < count($this->recipeInstructions); $i++) {
            $this->deleteInstruction($this->recipeInstructions[$i]);
            if (DEBUG) {
                print '<p>' . $counter . 'Instruction Deleted</p>';
            }
        }

        //Finish Transaction
        if ($fail) {
            $this->recipeDatabaseWriter->transactionFailed();
            $this->update();
            return false;
        } else {
            $this->recipeDatabaseWriter->transactionComplete();
            return true;
        }
    }

    public function insertRecipe($main, $ingredients, $instructions)
    {
        $this->recipeDatabaseWriter->transactionStart();
        $fail = false;
        //Main
        if (!$this->insertMain($main)) {
            $fail = true;
        }
        $this->recipeMainArray[0]['pmkRecipeName'] = $main['name'];
        //Ingredients
        foreach ($ingredients as $ingredient) {
            if (!$this->insertIngredient($ingredient)) {
                $fail = true;
            }
        }
        //Instructions
        $counter = 1;
        foreach ($instructions as $instruction) {
            if (!$this->insertInstruction($instruction, $counter)) {
                $fail = true;
            }
            $counter++;
        }
        if (DEBUG) {
            print '<p>' . $fail . '</p>';
        }
        //Finish Transaction
        if ($fail) {
            $this->recipeDatabaseWriter->transactionFailed();
            $this->update();
            return false;
        } else {
            $this->recipeDatabaseWriter->transactionComplete();
            return true;
        }
    }
}
