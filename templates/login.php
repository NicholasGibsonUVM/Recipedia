<?php
include 'top.php';

$txtUsername = '';
$txtPassword = '';

if (DEBUG) {
    print_r($_POST);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dataIsGood = true;
    $txtUsername = getData('txtUsername');
    $txtPassword = getData('txtPassword');

    $sqlUsername = 'SELECT `pmkUsername`, `fldPassword` FROM `tblUser` WHERE `pmkUsername` = ?';
    $valueUsername = array($txtUsername);
    $credentials = $thisDatabaseReader->select($sqlUsername, $valueUsername);

    if (DEBUG) {
        print $thisDatabaseReader->displayQuery($sqlUsername, $valueUsername);
    }
    if (empty($credentials) || $txtPassword != $credentials[0]['fldPassword']) {
        $dataIsGood = false;
        print '<p>Credential are Incorrect</p>';
    }
    if ($txtPassword == '') {
        $dataIsGood = false;
        print '<p>Must Enter Password</p>';
    }
    if ($txtUsername == '') {
        $dataIsGood = false;
        print '<p>Must Enter Username</p>';
    }

    if ($dataIsGood) {
        $_SESSION['username'] = $txtUsername;
        header("Location: index.php", true, 303);
        die();
    }
}
?>
<main>
    <link rel="stylesheet" href="../css/signup.css?version=<?php print time(); ?>" type="text/css">
    <h1>Login</h1>
    <form class="login" method="post">
        <fieldset class='username'>
            <label for='txtUsername'>Username</label>
            <input type='text' name='txtUsername' id='txtUsername' class='username' placeholder="Username">
        </fieldset>
        <fieldset class='password'>
            <label for='txtPassword'>Password</label>
            <input type='text' name='txtPassword' id='txtPassword' class='password' placeholder="Password">
        </fieldset>
        <fieldset class='submit'>
            <button type='submit' name='submit' id='submit' value='submit'>Submit</button>
        </fieldset>
    </form>
</main>