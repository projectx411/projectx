<?php


require_once 'functions.php';



createTable("users","forename VARCHAR(32) NOT NULL,
                                lastname VARCHAR(32) NOT NULL,
                                username VARCHAR(32) NOT NULL UNIQUE,
                                password VARCHAR(32) NOT NULL");

//add_user("Shantanu","Chutiya","sChutiya","dumbass");

$res = queryMysql("SELECT * FROM users");

if($res == false) echo "No entries exist";

for ($row_no = $res->num_rows - 1; $row_no >= 0; $row_no--) {
    $res->data_seek($row_no);
    $row = $res->fetch_assoc();
    echo " Name = " . $row['forename'] . " " . $row['lastname'] . "<br />";
}

function add_user_wrapper($fn, $sn, $un, $pw)
{
    add_user($fn, $sn, $un, $pw, $conn);
}



?>