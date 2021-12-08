<?php 
include 'top.php';
$getUsers = 'SELECT `pmkUsername`, `fldEmail`, COUNT(`pmkRecipeName`) as `totalRecipes` FROM `tblUser` LEFT JOIN `tblRecipe` ON `pmkUsername`=`fpkUsername` GROUP BY `pmkUsername`';
$users = $thisDatabaseReader->select($getUsers);

?>

<main>
    <section class="users">
    <?php 
    foreach ($users as $user) {
        print '<section class="user">';
        print '<a href="displayUser.php?usr=' . $user['pmkUsername'] . '">';
        print '<h1>Username: ' . $user['pmkUsername'] . '</h1>';
        print '<h2>Email: ' . $user['fldEmail'] . '</h2>';
        print '<h2>Total Recipes: ' . $user['totalRecipes'] . '</h2>';
        print '</a>';
        print '<a href="editUser.php?usr=' . $user['pmkUsername'] . '"><h2>Edit ' . $user['pmkUsername'] . '</h2></a>';
        print '<a href="deleteUser.php?usr=' . $user['pmkUsername'] . '"><h2>Delete ' . $user['pmkUsername'] . '</h2></a>';
        print '</section>';
    }
    ?>
    </section>
</main>

<?php 
include '../templates/footer.php';
?>