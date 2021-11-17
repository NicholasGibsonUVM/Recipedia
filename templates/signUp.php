<?php
include 'top.php';
?>

<main>
    <link rel="stylesheet" href="../css/signup.css?version=<?php print time(); ?>" type="text/css">
    <form class="signUp" method="post" action="../validation/signUp.php">
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
    </form>
</main>