<?php

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'mutuc';
$port = 3306;

$connection = mysqli_connect($host, $user, $password, $database, $port);

if (mysqli_connect_error()) {

    echo "Error: Unable to Connect to mySQL <br>";
    echo "Message: ".mysqli_connect_error(). "<br>";
}


// to check database connection
//else {
    //echo "Successfully Connected to your Database";
//}

?>