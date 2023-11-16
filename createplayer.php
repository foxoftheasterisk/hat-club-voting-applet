<?php

//error checking
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

$username = $_POST['name'];
$password = $_POST['password'];
$shortun = $_POST['shortname'];

$escun = $db->real_escape_string($username);
$escsht = $db->real_escape_string($shortun);

if($password != $_POST['password2'])
{
    //no password match
    //this should be caught by javascript, but in case it isn't...
    $message = '<p>
                    Passwords do not match!
                </p>
                <div class="flexrow">
                    <a href="login.html"><button class="medium action">Return to login</button></a>
                    <a href="newplayer.html"><button class="medium action">Try again</button></a>
                </div> ';
                
    setcookie("message-title", "Account creation failed");
    setcookie("message-body", $message);
    setcookie("message-class", "issue");

    header("Location: message.php", true, $resp_code);
    exit();
}

$result = $db->query("SELECT password_hash FROM players WHERE name='{$escun}'");
if($result->num_rows != 0)
{
    //user already exists
    $message = '<p>
                    User already exists by the name ' . $username . '!
                </p>
                <div class="flexrow">
                    <a href="login.html"><button class="medium action">Return to login</button></a>
                    <a href="newplayer.php"><button class="medium action">Choose different username</button></a>
                </div> ';
    
    setcookie("message-title", "Account creation failed");
    setcookie("message-body", $message);
    setcookie("message-class", "issue");
    
    header("Location: message.php", true, $resp_code);
    exit();
}

//now, the same thing for the shortened version
$result = $db->query("SELECT password_hash FROM players WHERE short_name='{$escsht}'");
if($result->num_rows != 0)
{
    //nickname taken
    
    $message = '<p>
                    User already exists with the nickname ' . $shortun . '!
                </p>
                <div class="flexrow">
                    <a href="login.html"><button class="medium action">Return to login</button></a>
                    <a href="newplayer.php"><button class="medium action">Choose different nickname</button></a>
                </div> ';
    
    setcookie("message-title", "Account creation failed");
    setcookie("message-body", $message);
    setcookie("message-class", "issue");
    
    header("Location: message.php", true, $resp_code);
    exit();
}

//we've performed all checks, now to actually make the account

$hash = password_hash($password, PASSWORD_DEFAULT);

$eshash = $db->real_escape_string($hash);

$result = $db->query("INSERT INTO players (name, short_name, password_hash) VALUES ('{$escun}', '{$escsht}', '{$eshash}')");

if($result === TRUE)
{
    //success!
    
    $message = '<p>
                    Account created!
                </p>
                <a href="login.html"><button class="medium action">Return to login</button></a>';
                
    $statuscode = 200;
    //200 = OK
    //i thought 204 might work but no, it just doesn't redirect.
    //i guess because it's "no content".
    
    displayMessage("Account creation success!", $message, "primary", $statuscode);
}

?>

