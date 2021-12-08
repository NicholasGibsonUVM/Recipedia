<?php
include 'top.php';
?>
<main>
    <section class="introduction">
        <h1>Welcome to Recipedia a response to increasingly busy recipe website designs</h1>
        <p>Since We have no need to make money off of this recipe website their are no ads or banners to distract the user from veiwing and creating recipes</p>
    </section>
    <section class="featuredHeader">
        <h2>Featured Recipe</h2>
    </section>
    <section class="featuredRecipe">
        <?php
        $recipe = new Recipe("Beef and Broccoli");
        $main = $recipe->getMain();
        recipePreview($main);
        ?>
    </section>
</main>
<?php
include 'footer.php';
?>