<?php
include 'top.php';

?>
<main>
    <form class='addRecipe' method='post'>
        <!-- Recipe Section -->
        <fieldset class='recipeName'>
            <label for='txtRecipeName'>Name</label>
            <input type='text' name='txtRecipeName' id='txtRecipeName' class='recipeName'>
        </fieldset>
        <fieldset class="recipeImage">
            <label for="txtRecipeImage">Image</label>
            <input type="file" name="txtRecipeImage" id="txtRecipeImage" accept="image/*">
        </fieldset>
        <fieldset class='recipeDescription'>
            <label for='txtRecipeDescription'>Description</label>
            <textarea name='txtRecipeDescription' id='txtRecipeDescription'></textarea>
        </fieldset>
        <fieldset class='recipeTime'>
            <label for='txtRecipeTime'>Time Required</label>
            <input type='text' name='txtRecipeTime' id='txtRecipeTime' class='recipeTime'>
        </fieldset>

        <!-- Ingredients Section -->
        <div id='ingredients'>
            <div id='ingredient'>
                <fieldset class='recipeIngredientName'>
                    <label for='txtRecipeIngredientName1'>Ingredient Name</label>
                    <input type='text' name='txtRecipeIngredientName1' id='1' class='recipeIngredientName'>
                </fieldset>
                <fieldset class='recipeIngredientAmount'>
                    <label for='txtRecipeIngredientAmount1'>Amount</label>
                    <input type='text' name='txtRecipeIngredientAmount1' id='1' class='recipeIngredientAmount'>
                </fieldset>
                <button type="button" id="1" onclick="deleteIngredient(this)">Remove Ingredient</button>
            </div>
        </div>
        <button type="button" onclick="addIngredient()">Add Ingredient</button>
        <input type="hidden" id="ingredientAmount" name="ingredientAmount" value="1">
    </form>
</main>
<script>
    function addIngredient() {
        var parent = document.getElementById('ingredients');
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
            inputArray[i].innerText = '';
        }
        var increment = document.getElementById('ingredientAmount')
        increment.value = parseInt(increment.value) + 1;
    }

    function deleteIngredient(button) {
        var del = button.parentElement;
        var old = button.parentElement;
        var totalElements = parseInt(document.getElementById('ingredientAmount').value)
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
                    inputArray[j].name = temp;
                    labelArray[j].htmlFor = temp2;
                }
                parent.getElementsByTagName("button")[0].id = i;
                old = parent;
            }
            var increment = document.getElementById('ingredientAmount')
            increment.value = parseInt(increment.value) - 1;
            del.remove();
        }
    }
</script>