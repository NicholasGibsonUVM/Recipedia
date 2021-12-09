<?php
if (PATH_PARTS['filename'] == "search.php") {
    $search = (isset($_GET['search'])) ? htmlspecialchars($_GET['search']) : 0;
}
?>
<nav>
    <a class="first <?php
                    if (PATH_PARTS['filename'] == "index") {
                        print 'activePage';
                    }
                    ?>" href="index.php">Home</a>

    <div class="searchBar">
        <form action='search.php' method='GET'>
            <input type="text" placeholder="<?php if (isset($search) && $search != 0) {
                                                print $search;
                                            } else {
                                                print "Search...";
                                            } ?>" name="search">
            <button type="submit"><i class="fa fa-search"></i></button>
        </form>
    </div>

    <?php
    if (isset($_SESSION['username'])) {
        if (PATH_PARTS['filename'] == "myRecipes") {
            print '<a class="myRecipes" href="addRecipe.php">Add A Recipe!</a>';
        } else {
            print '<a class="myRecipes" href="myRecipes.php">My Recipes</a>' . PHP_EOL;
        }
        print '<a class="logout ';
        if (PATH_PARTS['filename'] == "logout") {
            print 'activePage';
        }
        print '" href="logout.php">Log Out</a>' . PHP_EOL;
    } else {
        print '<a class="signUp ';
        if (PATH_PARTS['filename'] == "signUp") {
            print 'activePage';
        }
        print '" href="signUp.php">Sign Up</a>' . PHP_EOL;
        print '<a class="login ';
        if (PATH_PARTS['filename'] == "login") {
            print 'activePage';
        }
        print '" href="login.php">Login</a>' . PHP_EOL;
    }
    ?>
</nav>