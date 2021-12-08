<nav>
    <a class="first <?php
                    if (PATH_PARTS['filename'] == "users") {
                        print 'activePage';
                    }
                    ?>" href="users.php">Users</a>
    <a class="login" href="../templates/index.php">Exit</a>
</nav>