<?php
include 'top.php';

$author = $_SESSION['username'];
$name = '';
$description = '';
$time = '';
$image = '';
$ingredientNameArray = array();
$ingredientAmountArray = array();
$ingredientUnitArray = array();
$instructionDescriptionArray = array();
$ingredientAmount = 0;
$instructionAmount = 0;

if (DEBUG) {
    print_r($_POST);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dataIsGood = true;
    $name = getData('txtRecipeName');
    $description = getData('txtRecipeDescription');
    $time = getData('txtRecipeTime');
    $ingredientAmount = getData('ingredientAmount');
    $instructionAmount = getData('instructionAmount');
    if (!is_numeric($ingredientAmount) || !is_numeric($instructionAmount)) {
        $dataIsGood = false;
    }
    if ($dataIsGood) {
        for ($i = 0; $i < $ingredientAmount; $i++) {
            $ingredientNameArray[$i] = getData('txtRecipeIngredientName' . strval($i + 1));
            $ingredientAmountArray[$i] = getData('txtRecipeIngredientAmount' . strval($i + 1));
            $ingredientUnitArray[$i] = getData('txtRecipeIngredientUnit' . strval($i + 1));
            if ($ingredientNameArray[$i] == '' || $ingredientAmountArray[$i] == '' || $ingredientUnitArray == '') {
                $dataIsGood = false;
            }
        }
        for ($i = 0; $i < $instructionAmount; $i++) {
            $instructionDescriptionArray[$i] = getData('txtRecipeInstruction' . strval($i + 1));
            if ($instructionDescriptionArray[$i] == '') {
                $dataIsGood = false;
            }
        }
        $target_dir = "../images/";
        $target_file = $target_dir . basename($_FILES["txtRecipeImage"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $pictureLoc = basename($_FILES["txtRecipeImage"]["name"]);
        if (DEBUG) {
            print_r($_FILES);
        }
    }
    if ($dataIsGood) {
        $dataSubmited = true;
        $recipeInsert = 'INSERT INTO `tblRecipe` SET ';
        $recipeInsert .= '`pmkRecipeName` = ?, ';
        $recipeInsert .= '`fldPicture` = ?, ';
        $recipeInsert .= '`fldRating` = ?, ';
        $recipeInsert .= '`fldTime` = ?, ';
        $recipeInsert .= '`fldDescription` = ?, ';
        $recipeInsert .= '`fpkUsername` = ?';

        $recipeValues[0] = $name;
        $recipeValues[1] = $pictureLoc;
        $recipeValues[2] = 0;
        $recipeValues[3] = $time;
        $recipeValues[4] = $description;
        $recipeValues[5] = $author;

        $thisDatabaseWriter->transactionStart();

        if (move_uploaded_file($_FILES["txtRecipeImage"]["tmp_name"], $target_file)) {
            if ($thisDatabaseWriter->insert($recipeInsert, $recipeValues)) {
                for ($i = 0; $i < $ingredientAmount; $i++) {
                    $ingredientInsert = 'INSERT INTO `tblIngredients` SET ';
                    $ingredientInsert .= '`fldName` = ?, ';
                    $ingredientInsert .= '`fldUnit` = ?';
                    $ingredientValues[0] = $ingredientNameArray[$i];
                    $ingredientValues[1] = $ingredientUnitArray[$i];
                    if (!$thisDatabaseWriter->insert($ingredientInsert, $ingredientValues)) {
                        print $thisDatabaseReader->displayQuery($ingredientInsert, $ingredientValues);
                        print "<p>Failed at ingredient insert</p>";
                        $dataSubmited = false;
                    }
                    $selectSQL = 'SELECT LAST_INSERT_ID()';
                    $id = $thisDatabaseWriter->select($selectSQL);
                    if (DEBUG) {
                        print_r($id);
                    }
                    $recipeIngredientInsert = 'INSERT INTO `tblRecipeIngredient` SET ';
                    $recipeIngredientInsert .= '`fpkRecipeName` = ?, ';
                    $recipeIngredientInsert .= '`fpkIngredientId` = ?, ';
                    $recipeIngredientInsert .= '`fldAmount` = ?';
                    $recipeIngredientValues[0] = $name;
                    $recipeIngredientValues[1] = (int) $id[0]['LAST_INSERT_ID()'];
                    $recipeIngredientValues[2] = $ingredientAmountArray[$i];
                    if (!$thisDatabaseWriter->insert($recipeIngredientInsert, $recipeIngredientValues)) {
                        print $thisDatabaseReader->displayQuery($recipeIngredientInsert, $recipeIngredientValues);
                        print "<p>Failed at recipeIngredient insert</p>";
                        $dataSubmited = false;
                    }
                }
                if ($dataSubmited == true) {
                    for ($i = 0; $i < $instructionAmount; $i++) {
                        $instructionInsert = 'INSERT INTO `tblInstruction` SET `fldInstructionDescription` = ?';
                        $instructionValues[0] = $instructionDescriptionArray[$i];
                        if (!$thisDatabaseWriter->insert($instructionInsert, $instructionValues)) {
                            print $thisDatabaseWriter->displayQuery($instructionInsert, $instructionValues);
                            print "<p>Failed at Instruction Insert</p>";
                            $dataSubmited = false;
                        }
                        $selectSQL = 'SELECT LAST_INSERT_ID()';
                        $id = $thisDatabaseWriter->select($selectSQL);
                        if (DEBUG) {
                            print_r($id);
                        }
                        $recipeInstructionInsert = 'INSERT INTO `tblRecipeInstruction` SET ';
                        $recipeInstructionInsert .= '`fpkRecipeName` = ?, ';
                        $recipeInstructionInsert .= '`fpkInstructionId` = ?, ';
                        $recipeInstructionInsert .= '`fldOrder` = ?';
                        $recipeInstructionValues[0] = $name;
                        $recipeInstructionValues[1] = (int) $id[0]['LAST_INSERT_ID()'];
                        $recipeInstructionValues[2] = (int) $i + 1;
                        if (!$thisDatabaseWriter->insert($recipeInstructionInsert, $recipeInstructionValues)) {
                            print $thisDatabaseReader->displayQuery($recipeInstructionInsert, $recipeInstructionValues);
                            print "<p>Failed at recipeInstruction Insert";
                            $dataSubmited = false;
                        }
                    }
                }
            } else {
                $dataSubmited = false;
                if (DEBUG) {
                    print '<p> Failed at first insert </p>';
                    print $thisDatabaseReader->displayQuery($recipeInsert, $recipeValues);
                    print '<p>' . $target_file . '</p>';
                }
            }
        } else {
            $dataSubmited = false;
            if (DEBUG) {
                print '<p> Failed at moving picture </p>';
            }
        }
        if ($dataSubmited) {
            $thisDatabaseWriter->transactionComplete();
            if (!DEBUG) {
                header("Location: recipeAdded.php", true, 303);
                exit();
            } else {
                print '<p>Data Submitted</p>';
            }
        } else {
            $thisDatabaseWriter->transactionFailed();
        }
    }
}

?>
<main class='form'>
    <h1>Add A Recipe!</h1>
    <form class='addRecipe' method='post' enctype="multipart/form-data">

        <!-- Recipe Section -->
        <section class='recipe'>
            <fieldset class='recipeName'>
                <label for='txtRecipeName'>Name</label>
                <input type='text' name='txtRecipeName' id='txtRecipeName' class='recipeName' value='<?php print $name; ?>'>
            </fieldset>
            <fieldset class='recipeDescription'>
                <label for='txtRecipeDescription'>Description</label>
                <textarea name='txtRecipeDescription' id='txtRecipeDescription' rows="5"><?php print $description; ?></textarea>
            </fieldset>
            <fieldset class='recipeTime'>
                <label for='txtRecipeTime'>Time Required</label>
                <input type='text' name='txtRecipeTime' id='txtRecipeTime' class='recipeTime' placeholder="Hours:Minutes" value='<?php print $time; ?>'>
            </fieldset>
            <fieldset class="recipeImage">
                <label for="txtRecipeImage">Image</label>
                <input type="file" name="txtRecipeImage" id="txtRecipeImage" accept="image/*" class=recipeImage>
            </fieldset>
        </section>

        <h2>What Are The Ingrdients?</h2>
        <!-- Ingredients Section -->
        <div id='ingredients'>
            <div id='ingredient'>
                <fieldset class='recipeIngredientName'>
                    <label for='txtRecipeIngredientName1'>Ingredient Name</label>
                    <input type='text' name='txtRecipeIngredientName1' id='1' class='recipeIngredientName' value="<?php if (count($ingredientNameArray) > 0) {
                                                                                                                        print $ingredientNameArray[0];
                                                                                                                    } ?>">
                </fieldset>
                <div class='ingredientUnit'>
                    <fieldset class='recipeIngredientAmount'>
                        <label for='txtRecipeIngredientAmount1'>Amount</label>
                        <input type='text' name='txtRecipeIngredientAmount1' id='1' class='recipeIngredientAmount' value="<?php if (count($ingredientAmountArray) > 0) {
                                                                                                                                print $ingredientAmountArray[0];
                                                                                                                            } ?>">
                    </fieldset>
                    <fieldset class='recipeIngredientUnit'>
                        <label for='txtRecipeIngredientUnit1'>Unit</label>
                        <input type='text' name='txtRecipeIngredientUnit1' id='1' class='recipeIngredientUnit' value=" <?php if (count($ingredientUnitArray) > 0) {
                                                                                                                            print $ingredientUnitArray[0];
                                                                                                                        } ?>">
                    </fieldset>
                </div>
                <button type="button" id="1" onclick="deleteIngredient(this, 'ingredientAmount')">Remove Ingredient</button>
            </div>

            <?php
            for ($i = 1; $i < $ingredientAmount; $i++) {
                $count = $i + 1;
                print '<div id="ingredient">
                <fieldset class="recipeIngredientName">
                    <label for="txtRecipeIngredientName' . $count . '">Ingredient Name</label>
                    <input type="text" name="txtRecipeIngredientName' . $count . '" id="' . $count . '" class="recipeIngredientName" value="' . $ingredientNameArray[$i] . '">
                </fieldset>
                <div class="ingredientUnit">
                    <fieldset class="recipeIngredientAmount">
                        <label for="txtRecipeIngredientAmount' . $count . '">Amount</label>
                        <input type="text" name="txtRecipeIngredientAmount' . $count . '" id="' . $count . '" class="recipeIngredientAmount" value="' . $ingredientAmountArray[$i] . '">
                    </fieldset>
                    <fieldset class="recipeIngredientUnit">
                        <label for="txtRecipeIngredientUnit' . $count . '">Unit</label>
                        <input type="text" name="txtRecipeIngredientUnit' . $count . '" id="' . $count . '" class="recipeIngredientUnit" value="' . $ingredientUnitArray[$i] . '">
                    </fieldset>
                </div>
                <button type="button" id="' . $count . '" onclick="deleteIngredient(this, \'ingredientAmount\')">Remove Ingredient</button>
            </div>';
            }
            ?>
        </div>

        <button type="button" onclick="addIngredient('ingredients', 'ingredientAmount')">Add Ingredient</button>
        <input type="hidden" id="ingredientAmount" name="ingredientAmount" value="<?php if (count($ingredientNameArray) > 0) {
                                                                                        print count($ingredientNameArray);
                                                                                    } else {
                                                                                        print 1;
                                                                                    } ?>">

        <h2>How Do You Make Your Recipe</h2>
        <!-- Instructions Section -->
        <div id='instructions'>
            <div id='instruction'>
                <fieldset class='recipeInstruction'>
                    <label for='txtRecipeInstruction1'>Step 1</label>
                    <input type='text' name='txtRecipeInstruction1' id='1' class='recipeInstruction' value="<?php if (count($instructionDescriptionArray) > 0) {
                                                                                                                print $instructionDescriptionArray[0];
                                                                                                            } ?>">
                </fieldset>
                <button type="button" id="1" onclick="deleteIngredient(this, 'instructionAmount')">Remove Instruction</button>
            </div>
            <?php
            for ($i = 1; $i < $instructionAmount; $i++) {
                $count = $i + 1;
                print '<div id="instruction">
                <fieldset class="recipeInstruction">
                    <label for="txtRecipeInstruction' . $count . '">Step ' . $count . '</label>
                    <input type="text" name="txtRecipeInstruction' . $count . '" id="' . $count . '" class="recipeInstruction" value="' . $instructionDescriptionArray[$i] . '">
                </fieldset>
                <button type="button" id="' . $count . '" onclick="deleteIngredient(this, \'instructionAmount\')">Remove Instruction</button>
            </div>';
            }
            ?>
        </div>
        <button type="button" onclick="addIngredient('instructions', 'instructionAmount')">Add Instruction</button>
        <input type="hidden" id="instructionAmount" name="instructionAmount" value="<?php if (count($instructionDescriptionArray) > 0) {
                                                                                        print count($instructionDescriptionArray);
                                                                                    } else {
                                                                                        print 1;
                                                                                    } ?>">
        <input type="submit">
    </form>
</main>
<script>
    function addIngredient(parentName, counterName) {
        var parent = document.getElementById(parentName);
        var first = parent.firstElementChild;
        var ing = parent.lastElementChild;
        var clone = first.cloneNode(true);
        var inputArray = ing.getElementsByTagName("input");
        var idNumber = inputArray[0].id;
        parent.appendChild(clone);
        ing = parent.lastElementChild;
        inputArray = ing.getElementsByTagName("input");
        labelArray = ing.getElementsByTagName("label");
        var removeIdArray = ing.getElementsByTagName("button");
        var removeId = removeIdArray[0];
        removeId.id = (parseInt(idNumber) + 1);
        for (let i = 0; i < inputArray.length; i++) {
            inputArray[i].id = parseInt(idNumber) + 1;
            var temp = inputArray[i].name.slice(0, -1) + (parseInt(idNumber) + 1);
            var temp2 = labelArray[i].htmlFor.slice(0, -1) + (parseInt(idNumber) + 1);
            inputArray[i].name = temp;
            labelArray[i].htmlFor = temp2;
            inputArray[i].value = '';
        }
        var increment = document.getElementById(counterName)
        increment.value = parseInt(increment.value) + 1;
        if (parentName == 'instructions') {
            labelArray[0].innerText = 'Step ' + (parseInt(idNumber) + 1);
        }
    }

    function deleteIngredient(button, counterName) {
        var del = button.parentElement;
        var old = button.parentElement;
        var totalElements = parseInt(document.getElementById(counterName).value)
        if (!(totalElements == 1)) {
            var offset = button.id;
            for (let i = offset; i < totalElements; i++) {
                var parent = old.nextElementSibling;
                inputArray = parent.getElementsByTagName("input");
                labelArray = parent.getElementsByTagName("label");
                for (let j = 0; j < inputArray.length; j++) {
                    inputArray[j].id = i;
                    var temp = inputArray[j].name.slice(0, -1) + i;
                    var temp2 = labelArray[j].htmlFor.slice(0, -1) + i;
                    if (counterName == 'instructionAmount') {
                        labelArray[j].innerText = 'Step ' + i;
                    }
                    inputArray[j].name = temp;
                    labelArray[j].htmlFor = temp2;
                }
                parent.getElementsByTagName("button")[0].id = i;
                old = parent;
            }
            var increment = document.getElementById(counterName)
            increment.value = parseInt(increment.value) - 1;
            del.remove();
        }
    }
</script>