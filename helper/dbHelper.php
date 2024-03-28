<?php

/**
 * Establishes a database connection using PDO.
 * @return PDO Returns a new PDO object.
 */
function getConnection()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $db = "sistem_absensi";

 


    return new PDO("mysql:host=$servername;dbname=$db", $username, $password);
}