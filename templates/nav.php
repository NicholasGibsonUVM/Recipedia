<nav>
    <a class="first <?php
                    if (PATH_PARTS['filename'] == "index") {
                        print 'activePage';
                    }
                    ?>" href="index.php">Home</a>

    <div class="searchBar">
        <form action="/search.php">
            <input type="text" placeholder="Search.." name="search">
            <button type="submit"><i class="fa fa-search"></i></button>
        </form>
    </div>

    <a class="signUp <?php
                        if (PATH_PARTS['filename'] == "signUp") {
                            print 'activePage';
                        }
                        ?>" href="signUp.php">Sign Up</a>

    <a class="login <?php
                    if (PATH_PARTS['filename'] == "login") {
                        print 'activePage';
                    }
                    ?>" href="login.php">Login</a>
</nav>