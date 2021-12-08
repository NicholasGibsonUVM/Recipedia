<?php
include 'top.php';

$txtEmail = '';
$txtUsername = '';
$txtPassword = '';
$txtConfirmPassword = '';

if (DEBUG) {
    print_r($_POST);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dataIsGood = true;
    $txtEmail = filter_var(getData('txtEmail'), FILTER_SANITIZE_EMAIL);
    $txtUsername = getData('txtUsername');
    $txtPassword = getData('txtPassword');
    $txtConfirmPassword = getData('txtConfirmPassword');
    $Password = password_hash($txtPassword, PASSWORD_DEFAULT);
    $ConfirmPassword = password_hash($txtConfirmPassword, PASSWORD_DEFAULT);

    $sqlUsername = 'SELECT `pmkUsername` FROM `tblUser` WHERE `pmkUsername` = ?';
    $valueUsername = array($txtUsername);

    $sqlEmail = 'SELECT `fldEmail` FROM `tblUser` WHERE `fldEmail` = ?';
    $valueEmail = array($txtEmail);
    if (DEBUG) {
        print $thisDatabaseReader->displayQuery($sqlUsername, $valueUsername);
        print $thisDatabaseReader->displayQuery($sqlEmail, $valueEmail);
    }
    if ($txtPassword != $txtConfirmPassword) {
        $dataIsGood = false;
        print '<p>Passwords Don\'t match</p>';
    }
    if ($txtPassword == '') {
        $dataIsGood = false;
        print '<p>Must Enter Password</p>';
    }
    if ($txtUsername == '') {
        $dataIsGood = false;
        print '<p>Must Enter Username</p>';
    } else {
        $check = $thisDatabaseReader->select($sqlUsername, $valueUsername);
        if (count($check, COUNT_RECURSIVE) == 2) {
            $dataIsGood = false;
            print '<p>Username Already in Use</p>';
        }
    }
    if ($txtEmail == '') {
        $dataIsGood = false;
        print '<p>Must Enter Email</p>';
    } else {
        $check = $thisDatabaseReader->select($sqlEmail, $valueEmail);
        if (count($check, COUNT_RECURSIVE) == 2) {
            $dataIsGood = false;
            print '<p>Email Already in Use</p>';
        }
    }

    if ($dataIsGood) {
        $sqlInsert = 'INSERT INTO `tblUser`(`pmkUsername`, `fldEmail`, `fldPassword`) VALUES (?,?,?)';
        $values = array($txtUsername, $txtEmail, $Password);
        if ($thisDatabaseWriter->insert($sqlInsert, $values)) {
            $userInfoSelect = 'SELECT `fldTimeJoined` FROM `tblUser` WHERE `pmkUsername`=?';
            $userInfo = $thisDatabaseReader->select($userInfoSelect, array($txtUsername));
            $key1 = password_hash($userInfo[0]['fldTimeJoined'], PASSWORD_DEFAULT);
            $headers = "From: Recipedia\r\n";
            $headers .= "Reply-To: nsgibson@uvm.edu\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html";
            $message = "<h1>Thank You For Signing Up For Recipedia</h1>";
            $message .= "<h2><a href=\"https:" . BASE_PATH . "confirm.php?h=" . $key1 . "&u=" . $txtUsername . "\"Click Here To Confirm</a></h2>";
            $message .= "<p>If the link above doesn't work please copy and paste the link below into your browser</p>";
            $message .= "<p>https:" . BASE_PATH . "confirm.php?h=" . $key1 . "&u=" . $txtUsername . "</p>";
            $mail = mail($txtEmail, "Recipedia Confirmation", $message, $headers);
            header("Location: https://nsgibson.w3.uvm.edu/cs148/Recipedia/templates/confirm.php", true, 303);
            die();
        } else {
            print '<p>Something went wrong</p>';
            if (DEBUG) {
                print $thisDatabaseReader->displayQuery($sqlInsert, $values);
            }
        }
    }
}
?>

<!-- Add Error Messages In -->
<main>
    <link rel="stylesheet" href="../css/signup.css?version=<?php print time(); ?>" type="text/css">
    <h1>Sign Up</h1>
    <form class="signUp" method="post">
        <fieldset class='email'>
            <label for='txtEmail'>Email</label>
            <input type='text' name='txtEmail' id='txtEmail' class='email' placeholder="Email">
        </fieldset>
        <fieldset class='username'>
            <label for='txtUsername'>Username</label>
            <input type='text' name='txtUsername' id='txtUsername' class='username' placeholder="Username">
        </fieldset>
        <fieldset class='password'>
            <label for='txtPassword'>Password</label>
            <input type='text' name='txtPassword' id='txtPassword' class='password' placeholder="Password">
        </fieldset>
        <fieldset class='confirmPassword'>
            <label for='txtConfirmPassword'>Confirm Password</label>
            <input type='text' name='txtConfirmPassword' id='txtConfirmPassword' class='confirmPassword' placeholder="Confirm Password">
        </fieldset>
        <fieldset class='submit'>
            <button type='submit' name='submit' id='submit' value='submit'>Submit</button>
        </fieldset>
    </form>
</main>
<?php 
include 'footer.php';
?>
