<?php
include 'top.php';

$key = (isset($_GET['h'])) ? ($_GET['h']) : 0;
$username = (isset($_GET['u'])) ? htmlspecialchars($_GET['u']) : 0;
$confirmed = false;

$userInfoSelect = 'SELECT `fldTimeJoined` FROM `tblUser` WHERE `pmkUsername`=?';
$userInfo = $thisDatabaseReader->select($userInfoSelect, array($username));
if (count($userInfo) > 0) {
    if (password_verify($userInfo[0]['fldTimeJoined'], $key)) {
        $confirmAccount = 'UPDATE `tblUser` SET `fldConfirmed` = 1 WHERE `pmkUsername`=?';
        $thisDatabaseWriter->update($confirmAccount, array($username));
        $confirmed = true;
    }
}

?>
<main>
    <?php
    if ($confirmed) {
        print '<h1>Your Account Is Confirmed</h1>';
    } else {
        print '<h1>Please Check Your Email For The Confirmation Link</h1>';
    }
    ?>
</main>
<?php 
include 'footer.php';
?>