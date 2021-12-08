<?php 
include 'top.php';
$user = (isset($_GET['usr'])) ? htmlspecialchars($_GET['usr']) : '';
$selectUser = 'SELECT * FROM `tblUser` WHERE `pmkUsername` = ?';
$account = $thisDatabaseReader->select($selectUser, $user);
?>

<main>
    <form>
        
    </form>
</main>

<?php 
include '../footer.php';
?>