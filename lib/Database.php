<?php
class DataBase
{
    public $pdo = '';

    const DB_DEBUG = false;
    public function __construct($dataBaseUser, $whichDataBasePassword, $dataBaseName)
    {
        $this->pdo = null;
        include 'pass.php';
        $DataBasePassword = '';

        switch ($whichDataBasePassword) {
            case 'r':
                $DataBasePassword = $dbReader;
                break;
            case 'w':
                $DataBasePassword = $dbWriter;
                break;
        }

        $query = NULL;
        $dsn = 'mysql:host=webdb.uvm.edu;dbname=';

        if (self::DB_DEBUG) {
            echo "<p>Try connecting with phpMyAdmin with these credentials.</p>";
            echo '<p>Username: ' . $dataBaseUser;
            echo '<p>DSN: ' . $dsn . $dataBaseName;
            echo '<p>Password: ' . $DataBasePassword;
        }

        try {
            $this->pdo = new PDO($dsn . $dataBaseName, $dataBaseUser, $DataBasePassword);

            if (!$this->pdo) {
                if (self::DB_DEBUG) echo '<p>You are NOT connected to the database!</p>';
                return 0;
            } else {
                if (self::DB_DEBUG) echo '<p>You are connected to the database!</p>';
                return $this->pdo;
            }
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            if (self::DB_DEBUG) echo "<p>An error occured while connecting to the database: $error_message </p>";
        }
    }

    public function select($query, $values = '')
    {
        $statement = $this->pdo->prepare($query);

        if (is_array($values)) {
            $statement->execute($values);
        } else {
            $statement->execute();
        }

        $recordSet = $statement->fetchAll(PDO::FETCH_ASSOC);

        $statement->closeCursor();

        return $recordSet;
    }

    public function insert($query, $values = '')
    {
        try {
            $statement = $this->pdo->prepare($query);
            if (substr($query, 0, 6) != "INSERT") {
                return false;
            }
            if (is_array($values)) {
                if ($statement->execute($values)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    public function update($query, $values = '')
    {
        try {
            $statement = $this->pdo->prepare($query);
            if (substr($query, 0, 6) != "UPDATE") {
                return false;
            }
            if (is_array($values)) {
                if ($statement->execute($values)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    public function delete($query, $values = '')
    {
        try {
            $statement = $this->pdo->prepare($query);
            if (substr($query, 0, 6) != "DELETE") {
                return false;
            }
            if (is_array($values)) {
                if ($statement->execute($values)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    public function displayQuery($query, $values = '')
    {
        if (DEBUG) {
            if (is_array($values)) {
                $needle = '?';
                $haystack = $query;
                foreach ($values as $value) {
                    $pos = strpos($haystack, $needle);
                    if ($pos !== false) {

                        $haystack = substr_replace($haystack, '"' . $value . '"', $pos, strlen($needle));
                    }
                }
                $query = $haystack;
            }
            return '<p>' . $query . '</p>' . PHP_EOL;
        }
    }

    public function transactionStart() {
        $this->pdo->beginTransaction();
    }

    public function transactionComplete() {
        $this->pdo->commit();
    }

    public function transactionFailed() {
        $this->pdo->rollBack();
    }

    public function insertId() {
        return $this->pdo->lastInsertId();
    }
}
