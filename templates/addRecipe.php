<?php
include 'top.php';

$updateName = (isset($_GET['rec'])) ? htmlspecialchars($_GET['rec']) : '';

if (strlen($updateName) == 0) {
$author = $_SESSION['username'];
$recipeName = '';
$recipeDescription = '';
$time = '';
$image = '';
$ingredient = array();
$instruction = array();
$ingredientAmount = 0;
$instructionAmount = 0;
} else {
    $updateRecipe = new Recipe($updateName);
    $main = $updateRecipe->getMain();
    $author = $main[0]['fpkUsername'];
    $recipeName = $main[0]['pmkRecipeName'];
    $recipeDescription = $main[0]['fldDescription'];
    $time = $main[0]['fldTime'];
    $ingredient = $updateRecipe->getIngredients();
    $instruction = $updateRecipe->getInstructions();
    $ingredientAmount = count($ingredient);
    $instructionAmount = count($instruction);
}

if (DEBUG) {
    print '<p>' . $recipeName . '</p><p>';
    print_r($_POST);
    print '</p>';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    $dataIsGood = true;
    $recipeName = getData('txtRecipeName');
    $recipeDescription = getData('txtRecipeDescription');
    $time = getData('txtRecipeTime');
    $ingredientAmount = getData('ingredientAmount');
    $instructionAmount = getData('instructionAmount');

    if (!is_numeric($ingredientAmount) || !is_numeric($instructionAmount)) {
        $dataIsGood = false;
    }
    if ($dataIsGood) {
        $ingredient = array();
        $instruction = array();
        if ($name = '') {
            $dataIsGood = false;
            print '<p><b>Please enter a name</b></p>';
        }
        if ($description = '') {
            $dataIsGood = false;
            print '<p><b>Please enter a description</b></p>';
        }
        if (!preg_match('/\d{1,2}:\d{2}/', $time)) {
            $dataIsGood = false;
            print '<p><b>Please enter time in the format hours:minutes, Ex. 2:30';
        }
        for ($i = 0; $i < $ingredientAmount; $i++) {
            $ingredient[$i]['fldName'] = getData('txtRecipeIngredientName' . strval($i + 1));
            $ingredient[$i]['fldAmount'] = getData('txtRecipeIngredientAmount' . strval($i + 1));
            $ingredient[$i]['fldUnit'] = getData('txtRecipeIngredientUnit' . strval($i + 1));
            if ($ingredient[$i]['fldName'] == '' || $ingredient[$i]['fldAmount'] == '' || $ingredient[$i]['fldUnit'] == '') {
                $dataIsGood = false;
            }
        }
        for ($i = 0; $i < $instructionAmount; $i++) {
            $instruction[$i]['fldInstructionDescription'] = getData('txtRecipeInstruction' . strval($i + 1));
            if ($instruction[$i]['fldInstructionDescription'] == '') {
                $dataIsGood = false;
            }
        }
        $target_dir = "../images/";
        $target_file = $target_dir . basename($_FILES["txtRecipeImage"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $pictureLoc = basename($_FILES["txtRecipeImage"]["name"]);
        if (DEBUG) {
            print '<p>';
            print_r($_FILES);
            print '</p>';
        }
    }

    if ($dataIsGood) {
        $main = array('name' => $recipeName, 'pictureLoc' => $pictureLoc, 'rating' => 0, 'time' => $time, 'description' => $recipeDescription, 'author' => $author);
        if (DEBUG) {
            print '<p>';
            print_r($main);
            print '</p>';
            print '<p>';
            print_r($ingredient);
            print '</p>';
            print '<p>';
            print_r($instruction);
            print '</p>';
        }
        if (strlen($updateName) == 0) {
        $recipe = new Recipe($recipeName);
        if (move_uploaded_file($_FILES["txtRecipeImage"]["tmp_name"], $target_file)) {
            if ($recipe->insertRecipe($main, $ingredient, $instruction)) {
                print '<h1>Submitted</h1>';
            } else {
                print '<h1>Failed</h1>';
            }
        }
    } else {
        if ($updateRecipe->editRecipe($main, $ingredient, $instruction)) {
            print '<h1>Updated</h1>';
        } else {
            print '<h1>Failed</h1>';
        }
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
                <input type='text' name='txtRecipeName' id='txtRecipeName' class='recipeName' value='<?php print $recipeName; ?>'>
            </fieldset>
            <fieldset class='recipeDescription'>
                <label for='txtRecipeDescription'>Description</label>
                <textarea name='txtRecipeDescription' id='txtRecipeDescription' rows="5"><?php print $recipeDescription; ?></textarea>
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
                    <input type='text' name='txtRecipeIngredientName1' id='1' class='recipeIngredientName' value="<?php if (count($ingredient) > 0) {
                                                                                                                        print $ingredient[0]['fldName'];
                                                                                                                    } ?>">
                </fieldset>
                <div class='ingredientUnit'>
                    <fieldset class='recipeIngredientAmount'>
                        <label for='txtRecipeIngredientAmount1'>Amount</label>
                        <input type='text' name='txtRecipeIngredientAmount1' id='1' class='recipeIngredientAmount' value="<?php if (count($ingredient) > 0) {
                                                                                                                                print $ingredient[0]['fldAmount'];
                                                                                                                            } ?>">
                    </fieldset>
                    <fieldset class='recipeIngredientUnit'>
                        <label for='txtRecipeIngredientUnit1'>Unit</label>
                        <input type='text' name='txtRecipeIngredientUnit1' id='1' class='recipeIngredientUnit' value=" <?php if (count($ingredient) > 0) {
                                                                                                                            print $ingredient[0]['fldUnit'];
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
                    <input type="text" name="txtRecipeIngredientName' . $count . '" id="' . $count . '" class="recipeIngredientName" value="' . $ingredient[$i]['fldName'] . '">
                </fieldset>
                <div class="ingredientUnit">
                    <fieldset class="recipeIngredientAmount">
                        <label for="txtRecipeIngredientAmount' . $count . '">Amount</label>
                        <input type="text" name="txtRecipeIngredientAmount' . $count . '" id="' . $count . '" class="recipeIngredientAmount" value="' . $ingredient[$i]['fldAmount'] . '">
                    </fieldset>
                    <fieldset class="recipeIngredientUnit">
                        <label for="txtRecipeIngredientUnit' . $count . '">Unit</label>
                        <input type="text" name="txtRecipeIngredientUnit' . $count . '" id="' . $count . '" class="recipeIngredientUnit" value="' . $ingredient[$i]['fldUnit'] . '">
                    </fieldset>
                </div>
                <button type="button" id="' . $count . '" onclick="deleteIngredient(this, \'ingredientAmount\')">Remove Ingredient</button>
            </div>';
            }
            ?>
        </div>

        <button type="button" onclick="addIngredient('ingredients', 'ingredientAmount')">Add Ingredient</button>
        <input type="hidden" id="ingredientAmount" name="ingredientAmount" value="<?php if (count($ingredient) > 0) {
                                                                                        print count($ingredient);
                                                                                    } else {
                                                                                        print 1;
                                                                                    } ?>">

        <h2>How Do You Make Your Recipe</h2>
        <!-- Instructions Section -->
        <div id='instructions'>
            <div id='instruction'>
                <fieldset class='recipeInstruction'>
                    <label for='txtRecipeInstruction1'>Step 1</label>
                    <input type='text' name='txtRecipeInstruction1' id='1' class='recipeInstruction' value="<?php if (count($instruction) > 0) {
                                                                                                                print $instruction[0]['fldInstructionDescription'];
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
                    <input type="text" name="txtRecipeInstruction' . $count . '" id="' . $count . '" class="recipeInstruction" value="' . $instruction[$i]['fldInstructionDescription'] . '">
                </fieldset>
                <button type="button" id="' . $count . '" onclick="deleteIngredient(this, \'instructionAmount\')">Remove Instruction</button>
            </div>';
            }
            ?>
        </div>
        <button type="button" onclick="addIngredient('instructions', 'instructionAmount')">Add Instruction</button>
        <input type="hidden" id="instructionAmount" name="instructionAmount" value="<?php if (count($instruction) > 0) {
                                                                                        print count($instruction);
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