<?php
    session_start();
    $host = 'localhost';
    $connection = new MongoDB\Driver\Manager("mongodb://localhost:27017");

    $user = 'root';
    $pass = '';
    $mySql_db = 'testdb';
    $mySql_db = mysqli_connect('localhost',$user,$pass,$mySql_db);
    if ($mySql_db->connect_error) {
        die("Connection failed: " . $mySql_db->connect_error);
    }
?>