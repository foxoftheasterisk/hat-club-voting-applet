<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('utils.php');
if(!isset($_COOKIE['user']))
{
    http_response_code(403);
    die();
    //403: forbidden
    //(401: not authorized sounds closer, but it wants authorization via http rather than other methods)
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
        http_response_code(403);
    }
    else
    {
        http_response_code(500);
    }
    die();
}

if(!isset($_POST['game']))
{
    http_response_code(400);
    die();
}

$gameid = $db->real_escape_string($_POST["game"]);

$gamedata = $db->query("SELECT nominated_by FROM games WHERE id='{$gameid}'");

if($gamedata->num_rows != 1)
{
    if($gamedata->num_rows == 0)
    {
        http_response_code(400);
    }
    else
    {
        http_response_code(500);
    }
    die();
}
$gamedata = $gamedata->fetch_assoc();

$result = $db->query("SELECT COUNT(*) FROM game_status WHERE game_id='{$gameid}' AND player_id='{$user}';");

if($result->fetch_array()[0] != 0)
{
    http_response_code(400);
    die();
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
    http_response_code(400);
    die();
    //can't second your own nomination!
}

if(!isset($_POST["willing"]))
{
    http_response_code(400);
    die();
}

$willing = $_POST["willing"];

if(!($willing == "good" || $willing == "veto" || $willing == "tech"))
{
    http_response_code(400);
    die();
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

$query = "INSERT INTO game_status (player_id, game_id, owned, status)
          VALUES ('{$user}', '{$gameid}', '{$owned}', '{$willing}');"; 

try
{
    $result = $db->query($query);
}
catch(Exception $e)
{
    http_response_code(500);
    die();
}

if($result !== true)
{
    http_response_code(500);
    die();
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
        http_response_code(500);
        die();
    }
    
    if($result !== true)
    {
        http_response_code(500);
        die();
    }
}

http_response_code(200);
exit();
//501: not implemented
?>