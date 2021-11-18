<?php
include 'top.php';

if(isset($_SESSION['username'])) {
	unset($_SESSION['username']);
}

header("Location: index.php", true, 303);
die;
?>