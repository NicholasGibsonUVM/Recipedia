<?php
include 'top.php';

if(isset($_SESSION['username'])) {
	unset($_SESSION['username']);
}

header("Location: https://nsgibson.w3.uvm.edu/cs148/Recipedia/templates/index.php", true, 303);
die;
?>