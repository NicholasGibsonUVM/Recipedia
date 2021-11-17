<!DOCTYPE html>
<html lang="en">

<head>
      <meta charset='utf-8'>
      <meta name="viewport" content="width=device-width, initial-scale = 1.0">
      <meta name="author" content="Nicholas Gibson">
      <meta name="description" content="">
      <title>Recipedia</title>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <link rel="stylesheet" href="../css/main.css?version=<?php print time(); ?>" type="text/css">
      <link rel="stylesheet" href="../css/nav.css?version=<?php print time(); ?>" type="text/css">
</head>
<?php
include '../lib/constants.php';
print '<!-- make Database connections -->' . PHP_EOL;
require_once('../lib/Database.php');
require_once('../lib/Picture.php');
$thisDatabaseReader = new Database('nsgibson_reader', 'r', DATABASE_NAME);
$thisDatabaseWriter = new Database('nsgibson_writer', 'w', DATABASE_NAME);
print '<body class="' . PATH_PARTS['filename'] . '">' . PHP_EOL;
print '<!-- ***** START OF BODY ***** -->';
print PHP_EOL;
include 'header.php';
print PHP_EOL;
include 'nav.php';
print PHP_EOL;
?>