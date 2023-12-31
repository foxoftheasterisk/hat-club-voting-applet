<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('utils.php');
if(!isset($_COOKIE['user']))
{
    redirect("login.php");
}

$db = connectToDB();

//this sanitization would only matter if the cookie's been tampered with
//still, doesn't hurt to have.
$user = $_COOKIE['user'];
$user = $db->real_escape_string($user);

$result = $db->query("SELECT COUNT(*) FROM players WHERE id='{$user}';");
$row = $result->fetch_array();
if($row[0] != 1)
{
    if($row[0] == 0)
    {
        $message = "User not found!";
        $button = array("href"=>"homepage.php", "text"=>"Return to homepage");
        message("Editing gamestatus failed", $message, $button);
    }
    else
    {
        $message = "Duplicate user found! Contact your administrator.";
        $button = array("href"=>"homepage.php", "text"=>"Return to homepage");
        message("Editing gamestatus failed", $message, $button);
    }
    die();
}

if(!isset($_POST['game']))
{
    $message = "No game selected to edit.";
    $button = array("href"=>"homepage.php", "text"=>"Return to homepage");
    message("Editing gamestatus failed", $message, $button);
}

$gameid = $db->real_escape_string($_POST["game"]);

$gamedata = $db->query("SELECT nominated_by FROM games WHERE id='{$gameid}'");

if($gamedata->num_rows != 1)
{
    if($gamedata->num_rows == 0)
    {
        $message = "Game does not exist!";
        $button = array("href"=>"homepage.php", "text"=>"Return to homepage");
        message("Editing gamestatus failed", $message, $button);
    }
    else
    {
        $message = "Duplicate game found! Contact your administrator.";
        $button = array("href"=>"homepage.php", "text"=>"Return to homepage");
        message("Editing gamestatus failed", $message, $button);
    }
    die();
}
$gamedata = $gamedata->fetch_assoc();

$result = $db->query("SELECT COUNT(*) FROM game_status WHERE game_id='{$gameid}' AND player_id='{$user}';");

if($result->fetch_array()[0] == 0)
{
    redirect("newgamestatus.php");
}

$second = false;

if(isset($_POST['second']))
{
    if($_POST['second'] == "yes")
    {
        $second = true;
    }
}

if($second && ($gamedata["nominated_by"] == $user))
{
    $message = "Cannot second your own nomination!";
    $button = array("href"=>"homepage.php", "text"=>"Return to homepage");
    message("Editing gamestatus failed", $message, $button);
}

if(!isset($_POST["willing"]))
{
    $message = "Willingness status not set!";
    $button = array("href"=>"homepage.php", "text"=>"Return to homepage");
    message("Editing gamestatus failed", $message, $button);
}

$willing = $_POST["willing"];

if(!($willing == "good" || $willing == "veto" || $willing == "tech"))
{
    $message = "Willingness set to illegal value!";
    $button = array("href"=>"homepage.php", "text"=>"Return to homepage");
    message("Editing gamestatus failed", $message, $button);
}

$willing = $db->real_escape_string($willing);

$owned = 0;

if(isset($_POST["owned"]))
{
    if($_POST["owned"] == "yes")
    {
        $owned = 1;
    }
}

$query = "UPDATE game_status
          SET owned = '{$owned}', status = '{$willing}'
          WHERE player_id='{$user}' AND game_id='{$gameid}';"; 

try
{
    $result = $db->query($query);
}
catch(Exception $e)
{
    $message = "Game status edit failed at database level! Contact your administrator. <br />";
    $message .= $e->getMessage();
    $button = array("href"=>"gamestatus.php", "text"=>"Return to game status page");
    message("Editing gamestatus failed", $message, $button);
}

if($result !== true)
{
    $message = "Game status edit failed at database level! Contact your administrator.";
    $button = array("href"=>"gamestatus.php", "text"=>"Return to game status page");
    message("Editing gamestatus failed", $message, $button);
}

//successfully submitted, now just need to second if applicable
if($second)
{
    $query = "UPDATE games
              SET nominated_by = NULL
              WHERE id = '{$gameid}';";
    
    try
    {
        $result = $db->query($query);
    }
    catch(Exception $e)
    {
        $message = "Status edit succeeded, but seconding failed at database level! Contact your administrator. <br />";
        $message .= $e->getMessage();
        $button = array("href"=>"gamestatus.php", "text"=>"Return to game status page");
        message("Editing gamestatus failed", $message, $button);
    }
    
    if($result !== true)
    {
        $message = "Status edit succeeded, but seconding failed at database level! Contact your administrator.";
        $button = array("href"=>"gamestatus.php", "text"=>"Return to game status page");
        message("Editing gamestatus failed", $message, $button);
    }
}

$message = "Status edit succeeded!";
$button = array("href"=>"gamestatus.php", "text"=>"Return to game status page");
message("Game status edited!", $message, $button, "primary");


?>