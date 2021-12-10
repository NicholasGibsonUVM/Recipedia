<?php
include 'top.php';
?>
<main>
    <section class="introduction">
        <h1>Welcome to Recipedia a response to increasingly busy recipe website designs</h1>
        <p>Since I have no need to make money off of this recipe website their are no ads or banners to distract the user from veiwing and creating recipes</p>
    </section>
    <section class="featuredHeader">
        <h2>Featured Recipe's</h2>
    </section>
    <section class="featuredRecipe">
        <?php
        $recipe = new Recipe("Beef and Broccoli");
        $recipe2 = new Recipe("Spicy Rigatoni W/ Vodka Sauce");
        $main = $recipe->getMain();
        $main2 = $recipe2->getMain();
        recipePreview($main);
        recipePreview($main2);
        ?>
    </section>
</main>
<?php
include 'footer.php';
?>