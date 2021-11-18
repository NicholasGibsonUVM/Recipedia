<?php
function getData($field)
{
    if (!isset($_POST[$field])) {
        $data = "";
    } else {
        $data = htmlspecialchars(trim($_POST[$field]));
    }
    return $data;
} 

?>