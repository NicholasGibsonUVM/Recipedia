<?php
include 'top.php';
$user = (isset($_GET['usr'])) ? htmlspecialchars($_GET['usr']) : '';
$selectUser = 'SELECT * FROM `tblUser` WHERE `pmkUsername` = ?';
$account = $thisDatabaseReader->select($selectUser, array($user));

$txtUsername = $user;
$txtEmail = $account[0]['fldEmail'];
$chkConfirmed = $account[0]['fldConfirmed'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $txtUsername = getData('txtUsername');
    $txtEmail = getData('txtEmail');
    if (isset($_POST['chkConfirmed'])) {
        $chkConfirmed = (int) getData('chkConfimed');
    } else {
        $chkConfirmed = 0;
    }
    $dataIsGood = true;

    if ($txtUsername == '') {
        print '<p>Must enter a username</p>';
        $dataIsGood = false;
    }
    if ($txtEmail == '') {
        print '<p>Must enter an email</p>';
        $dataIsGood = false;
    }
    if ($chkConfirmed != 0 && $chkConfirmed != 1) {
        print '<p>Something went wrong refresh and try again</p>';
        $dataIsGood = false;
    }

    if ($dataIsGood) {
        $dataSubmited = true;
        $thisDatabaseWriter->transactionStart();
        if ($txtUsername != $user) {
            $values = array($txtUsername, $user);
            $updateMadeRecipes = 'UPDATE `tblRecipe` SET `fpkUsername` = ? WHERE `fpkUsername` = ?';
            if (!$thisDatabaseWriter->update($updateMadeRecipes, $values)) {
                $dataSubmited = false;
                print $thisDatabaseWriter->displayQuery($updateMadeRecipes, $values);
            }
            $updateSavedRecipes = 'UPDATE `tblUserRecipe` SET `fpkUsernameSaved` = ? WHERE `fpkUsernameSaved` = ?';
            if (!$thisDatabaseWriter->update($updateSavedRecipes, $values)) {
                $dataSubmited = false;
                print $thisDatabaseWriter->displayQuery($updateSavedRecipes, $values);
            }
        }
        $updateUser = 'UPDATE `tblUser` SET `pmkUsername` = ?, `fldEmail` = ?, `fldConfirmed` = ? WHERE `pmkUsername` = ?';
        $values = array($txtUsername, $txtEmail, (int)$chkConfirmed, $user);
        if (!$thisDatabaseWriter->update($updateUser, $values)) {
            $dataSubmited = false;
            print $thisDatabaseWriter->displayQuery($updateUser, $values);
        }
        
        if ($dataSubmited) {
            $thisDatabaseWriter->transactionComplete();
            header("Location: users.php", true, 303);
            exit();
        } else {
            $thisDatabaseWriter->transactionFailed();
            print '<h1>Not Submitted</h1>';
        }
    }
}
?>

<main class="form">
    <form method="POST">
        <fieldset class='username'>
            <label for='txtUsername'>Username</label>
            <input type='text' name='txtUsername' id='txtUsername' class='username' value='<?php print $txtUsername; ?>'>
        </fieldset>
        <fieldset class='email'>
            <label for='txtEmail'>Email</label>
            <input type='text' name='txtEmail' id='txtEmail' class='email' value='<?php print $txtEmail; ?>'>
        </fieldset>
        <fieldset class='confirmed'>
            <label for='chkConfirmed'>confirmed</label>
            <input type='checkbox' name='chkConfirmed' id='chkConfirmed' class='confirmed' value='1' checked='<?php if ((int) $chkConfirmed == 1) {
                                                                                                                    print 'true';
                                                                                                                } else {
                                                                                                                    print 'false';
                                                                                                                } ?>'>
        </fieldset>
        <input type='submit'>
    </form>
</main>

<?php
include '../templates/footer.php';
?>