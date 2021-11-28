<nav>
    <a class="first <?php
                    if (PATH_PARTS['filename'] == "index") {
                        print 'activePage';
                    }
                    ?>" href="index.php">Home</a>

    <div class="searchBar">
        <form action='search.php' method='GET'>
            <input type="text" placeholder="Search.." name="search">
            <button type="submit"><i class="fa fa-search"></i></button>
        </form>
    </div>

    <?php
    if (isset($_SESSION['username'])) {
        print '<a class="myRecipes ';
        if (PATH_PARTS['filename'] == "myRecipes") {
            print 'activePage';
        }
        print '" href="myRecipes.php">My Recipes</a>' . PHP_EOL;
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