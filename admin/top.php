<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
      <meta charset='utf-8'>
      <meta name="viewport" content="width=device-width, initial-scale = 1.0">
      <meta name="author" content="Nicholas Gibson">
      <meta name="description" content="">
      <title>Recipedia</title>
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <link rel="stylesheet" href="../css/main.css?version=<?php print time(); ?>" type="text/css">
      <link rel="stylesheet" href="../css/header.css?version=<?php print time(); ?>" type="text/css">
      <link rel="stylesheet" href="../css/nav.css?version=<?php print time(); ?>" type="text/css">
      <link rel="stylesheet" href="../css/form.css?version=<?php print time(); ?>" type="text/css">
      <link rel="stylesheet" href="../css/recipePreview.css?version=<?php print time(); ?>" type="text/css">
      <link rel="stylesheet" href="../css/recipe.css?version=<?php print time(); ?>" type="text/css">
      <link rel="stylesheet" href="../css/admin.css?version=<?php print time(); ?>" type="text/css">
</head>
<?php
include '../lib/constants.php';
print '<!-- make Database connections -->' . PHP_EOL;
require_once('../lib/Database.php');
require_once('../lib/Recipe.php');
require_once('../lib/Picture.php');
require_once('../lib/functions.php');
$thisDatabaseReader = new Database('nsgibson_reader', 'r', DATABASE_NAME);
$thisDatabaseWriter = new Database('nsgibson_writer', 'w', DATABASE_NAME);

$netId = htmlentities($_SERVER["REMOTE_USER"], ENT_QUOTES, "UTF-8");
$sql = 'SELECT * FROM tblAdmin';
$admins = $thisDatabaseReader->select($sql);
$isAdmin = false;
foreach ($admins as $admin) {
      if ($netId == $admin['pmkAdminId']) {
            $isAdmin = true;
      }
}
if ($isAdmin == false) {
      header("Location: ../templates/index.php", true, 303);
      exit();
}

print '<body class="' . PATH_PARTS['filename'] . '">' . PHP_EOL;
print '<!-- ***** START OF BODY ***** -->';
print PHP_EOL;
include 'header.php';
print PHP_EOL;
include 'nav.php';
print PHP_EOL;
?>