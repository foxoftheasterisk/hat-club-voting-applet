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
    else
    {
        $resp_code = 400;
        //400 = bad request
        //logging in to an account that doesn't exist = bad!
        
        $setcookie("message-title", "Login failed!");
        $setcookie("message-body", '<p>
                    Incorrect username or password!<br />
                    (To create new user, use our lobby password.)
                </p>
                <a href="login.html"><button class="medium action">Return to login</button></a>');
        //(yes, we know that this is an incorrect username, not incorrect password,
        //but it's bad form to let people know that, because then they could trawl for existent usernames.
        //not that i expect any sort of attack on this site, but, best practices.)
        
        //although, technically, this is an incorrect password, in a way.
        
        header("Location: message.php", true, $resp_code);
        exit();
    }
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
    else
    {
        $resp_code = 400;
        //400 = bad request
        //incorrect password is less bad than some...
        //...well, maybe not, as it might indicate an attempt to break in!
        
        $setcookie("message-title", "Login failed!");
        $setcookie("message-body", '<p>
                    Incorrect username or password!<br />
                    (To create new user, use our lobby password.)
                </p>
                <a href="login.html"><button class="medium action">Return to login</button></a>');
        //exactly the same message as for incorrect username
        
        header("Location: message.php", true, $resp_code);
        exit();
    }
}
else
{
    $resp_code = 500;
    //500 = internal server error
    //having a duplicate user certainly would qualify
    
    $setcookie("message-title", "Login failed!");
    $setcookie("message-body", '<p>
                This shouldn\'t be possible, but there\'s more than one user with that username.<br />
                Contact the administrator.
            </p>
            <a href="login.html"><button class="medium action">Return to login</button></a>');
    //if this ever happens... well.
    
    header("Location: message.php", true, $resp_code);
    exit();
    
    echo("This shouldn't be possible, but there's more than one user with that username.");
}



?>