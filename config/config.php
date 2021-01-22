<?php
    DEFINE('HOST', 'localhost');
    DEFINE('USERNAME', 'root');
    DEFINE('PASSWORD', '');
    DEFINE('DATABASE', 'authentication_demo');

    // set up DSN
     // SET DSN
    $dsn = 'mysql:host='.HOST . ';dbname='.DATABASE;

    try {
        $conn = new PDO($dsn, USERNAME, PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Fetch object
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // for LIMITS

    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }

    session_start();


?>