<?php
    $dsn = 'mysql:host=localhost; dbname=mo_maresh'; // THE HOST WITH DATABASE NAME
    $user = 'root';
    $pass = '';
    $option = array (
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
    );

    try {
        $conn = new PDO($dsn, $user, $pass, $option);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // echo 'You are connected';
    }
    catch(PDOException $e) {
        echo 'The connection failed ' . $e; 
    }