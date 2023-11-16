<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('utils.php');

//if cookie is set, redirect to homepage
if(isset($_COOKIE["user"]))
{
    redirect("homepage.php");
}

$db = connectToDB();

$username = $_POST['username'];
$password = $_POST['password'];

//right. input sanitization. glad i was reminded in a non-destructive way.
$username = $db->real_escape_string($username);
$result = $db->query("SELECT password_hash FROM players WHERE name='{$username}'");

if($result->num_rows == 0)
{
    
    //so this isn't exposed in source...
    $lobby = parse_ini_file("lobbypass.ini");
    
    if($password == $lobby['pass'])
    {
        redirect("newplayer.php");
    }
    else
    {
        $message = '<p>
                        Incorrect username or password!<br />
                        (To create new user, use our lobby password.)
                    </p>
                    <a href="login.html"><button class="medium action">Return to login</button></a>';
        //(yes, we know that this is an incorrect username, not incorrect password,
        //but it's bad form to let people know that, because then they could trawl for existent usernames.
        //not that i expect any sort of attack on this site, but, best practices.)
        
        //although, technically, this is an incorrect password, in a way.
        
        displayMessage("Login failed!", $message);
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
        
        redirect("homepage.php");
    }
    else
    {
        
        $message = '<p>
                        Incorrect username or password!<br />
                        (To create new user, use our lobby password.)
                    </p>
                    <a href="login.html"><button class="medium action">Return to login</button></a>';
        //exactly the same message as for incorrect username
        
        displayMessage("Login failed!", $message);
    }
}
else
{
    $message = '<p>
                    This shouldn\'t be possible, but there\'s more than one user with that username.<br />
                    Contact the administrator.
                </p>
                <a href="login.html"><button class="medium action">Return to login</button></a>';
    //if this ever happens... well.
    
    displayMessage("Login failed!?", $message);
}

echo("How did you get here??");

?>

