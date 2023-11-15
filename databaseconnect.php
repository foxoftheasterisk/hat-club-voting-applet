<?php
function connectToDB(string $filename = '/run/secrets/sql-credentials.ini') : mysqli {
    $credentials = parse_ini_file($filename);

    $connection = new mysqli($credentials['url'], $credentials['user'], $credentials['pass']);
    
    if ($connection->connect_error) {
        echo('Connection failed. Error: ' . $conn->connect_error);
        die('Connection failed. Error: ' . $conn->connect_error);
    }
    
    $connection->select_db($credentials['user']);
    
    return $connection;
}
?>