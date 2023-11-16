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

function displayMessage(string $messageTitle, string $messageBody, string $messageClass = "issue")
{
    $resp_code = 307;
    
    setcookie("message-title", $messageTitle);
    setcookie("message-body", $messageBody);
    setcookie("message-class", $messageClass);
    
    redirect("message.php");
}

function redirect(string $location, $resp_code = 307)
{
    //307 pretty much always works, for my purposes.
    //i was trying to get fancy with 4xx and 2xx codes but...
    //they don't work as redirects.
    //so.
    
    header("Location: {$location}", true, $resp_code);
    exit();
    //not sure if this will work, or it needs to be in the calling point.
    //regardless.
}

?>