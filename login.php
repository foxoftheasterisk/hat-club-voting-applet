<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//if cookie is set, redirect to homepage
if(isset($_COOKIE["user"]))
{
    $resp_code = 400;
    //400: Bad request
    //trying to log in while already logged in seems like a bad request i'd say
    
    header("Location: homepage.php", true, $resp_code);
    exit();
}

require('databaseconnect.php');

$db = connectToDB();

$username = $_POST['username'];
$password = $_POST['password'];

$result = $db->query("SELECT password_hash FROM players WHERE name='{$username}'");

if($result->num_rows == 0)
{
    //zero names: check if lobby password, otherwise, default to incorrect
    $lobbypass = "1234";
    if($password == $lobbypass)
    {
        
        $resp_code = 307; 
        //307: temporary redirect, sending the same data by same method (that is, POST)
        //to a different page
        header("Location: newplayer.php", true, $resp_code);
        exit();
        
    }
    //if it isn't, we fall out and display the error.
}
else if($result->num_rows == 1)
{
    
    //the normal case! we've found a user by that name
    $hash = $result->fetch_array()[0];
    
    if(password_verify($password, $hash))
    {
        $duration = 30 * 84600; //84600 = 1 day of seconds, times 30 days
        setcookie("user", $username, $duration);
        
        //now redirect to the homepage
        $resp_code = 204;
        //204 = No content
        //this feels correct? successfully processed the request but is not returning any content (related to the request)
        //will have to see if it makes strange behaviour.
        header("Location: homepage.php", true, $resp_code);
        exit();
    }
    //if the password doesn't match, then we fall out and display the error.
    
}
else
{
    echo("This shouldn't be possible, but there's more than one user with that username.");
}



?>

<!DOCTYPE html>
<html lang="en-US">
    <head>
        <title>Login failed</title>
        
        <?php require('header_boilerplate.html'); ?>
        
    </head>
    <body>
        <div class="main">
        
            <img src="fishhat.png" alt="The Fish Hat" class="cap" />
            
            <div class="toplevel issue">
                <p>
                    Incorrect username or password!<br />
                    (To create new user, use our lobby password.)
                </p>
                <a href="login.html"><button class="medium action">Return to login</button></a>
            </div>
        </div>
    </body>
</html>