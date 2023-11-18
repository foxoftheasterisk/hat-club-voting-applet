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
if($row[0] == 0)
{
    $message = 'Submitting user not found! Contact your admin.';
    $button = array("href"=>"homepage.php", "text" => "Return to homepage"); 
    message("Game edit failed!", $message, $button);
}

//check that name exists
if(!isset($_POST['name']))
{
    $message = 'No name submitted.';
    $button = array("href"=>"gamestatus.php", "text" => "Return to game status page"); 
    message("Game edit failed!", $message, $button);
}

//check that id exists and is a valid game
if(!isset($_POST['id']))
{
    $message = "No game selected to edit.";
    $button = array("href"=>"homepage.php", "text"=>"Return to homepage");
    message("Game edit failed!", $message, $button);
}

$gameid = $db->real_escape_string($_POST["id"]);

$result = $db->query("SELECT COUNT(id) FROM games WHERE id='{$gameid}'");
$row = $result->fetch_array();
if($row[0] != 1)
{
    $message = "Selected game does not exist.";
    $button = array("href"=>"homepage.php", "text"=>"Return to homepage");
    message("Game edit failed!", $message, $button);
}

//check that name exists
if(!isset($_POST['name']))
{
    $message = "No name submitted!";
    $button = array("href"=>"gamestatus.php", "text" => "Return to game status page"); 
    message("Game edit failed!", $message, $button);
}

//check name is not used by different game
$name = $_POST['name'];
$title = $db->real_escape_string($name);

$result = $db->query("SELECT emoji FROM games WHERE name='{$title}' AND NOT id='{$gameid}';");
if($result->num_rows != 0)
{
    if($result->num_rows > 1)
    {
        $message = 'Multiple other games with this title already exist??';
        $button = array("href"=>"gamestatus.php", "text" => "Return to game status page"); 
        message("Game edit failed!", $message, $button);
    }
    
    $game = $result->fetch_assoc();
    
    $message = "Different game ({$game["emoji"]}) is using this title!";
    $button = array("href"=>"gamestatus.php", "text" => "Return to game status page"); 
    message("Game edit failed!", $message, $button);
}

//now, much the same for emoji
if(!isset($_POST['emoji']))
{
    $message = "No emoji submitted!";
    $button = array("href"=>"gamestatus.php", "text" => "Return to game status page"); 
    message("Game edit failed!", $message, $button);
}

//and check already used emoji...
$emoji = $_POST['emoji'];
$emoji = $db->real_escape_string($emoji);

$result = $db->query("SELECT name FROM games WHERE emoji='{$emoji}' AND NOT id='{$gameid}';");
if($result->num_rows != 0)
{
    if($result->num_rows > 1)
    {
        $message = 'Multiple other games using this emoji exist??';
        $button = array("href"=>"gamestatus.php", "text" => "Return to game status page"); 
        message("Game edit failed!", $message, $button);
    }
    
    $game = $result->fetch_assoc();
    
    $message = "Different game ({$game["name"]}) is using this emoji!";
    $button = array("href"=>"gamestatus.php", "text" => "Return to game status page"); 
    message("Game edit failed!", $message, $button);
}

if(!(isset($_POST['minimumplayers']) && isset($_POST['maximumplayers'])) )
{
    $message = 'Player count(s) not submitted.';
    $button = array("href"=>"gamestatus.php", "text" => "Return to game status page"); 
    message("Game edit failed!", $message, $button);
}

$min = $_POST['minimumplayers'];
$max = $_POST['maximumplayers'];

if($max == "∞")
{
    $max = "9999";
}

if(!(is_numeric($min) && is_numeric($max)))
{
    $message = 'One or both player counts is not a number!';
    $button = array("href"=>"gamestatus.php", "text" => "Return to game status page"); 
    message("Game edit failed!", $message, $button);
}

if($min > $max)
{
    $message = "Impossible player counts - min({$min}) cannot be higher than max ({$max})!";
    $button = array("href"=>"gamestatus.php", "text" => "Return to game status page"); 
    message("Game edit failed!", $message, $button);
}

//i don't think these should need escaping - they're guaranteed to be numeric
//still, it doesn't hurt.
$max = $db->real_escape_string($max);
$min = $db->real_escape_string($min);

if(!isset($_POST['own']))
{
    $message = 'Ownership requirement not submitted.';
    $button = array("href"=>"gamestatus.php", "text" => "Return to game status page"); 
    message("Game edit failed!", $message, $button);
}

$own = $_POST['own'];

if(!($own == "all" || $own == "one" || $own == "free"))
{
    $message = "{$own} is not a valid ownership requirement!";
    $button = array("href"=>"gamestatus.php", "text" => "Return to game status page"); 
    message("Game edit failed!", $message, $button);
}

//ok this REALLY shouldn't need escaping
//im still doing it though
$own = $db->real_escape_string($own);

if(!isset($_POST['genre']))
{
    $message = "Genre/category not submitted.";
    $button = array("href"=>"gamestatus.php", "text" => "Return to game status page"); 
    message("Game edit failed!", $message, $button);
}

$cat = $_POST['genre'];

$cat = $db->real_escape_string($cat);

if($cat == "New")
{
    if(!isset($_POST['newgenre']))
    {
        $message = "Genre set to \"New\", but no new genre submitted!";
        $button = array("href"=>"gamestatus.php", "text" => "Return to game status page"); 
        message("Game edit failed!", $message, $button);
    }
    
    $cat = $db->real_escape_string($_POST['newgenre']);
    
    $result = $db->query("SELECT DISTINCT category FROM games WHERE category='{$cat}';");
    //i swear there is a point to this
    
    if($result->num_rows != 0)
    {
        $realcat = $result->fetch_array()[0];
        
        //because the database is case-insensitive, the case may be different between $cat and $realcat
        //hence why both are stored.
        $message = "Tried to create genre \"{$cat}\", but the genre \"{$realcat}\" already exists!";
        $button = array("href"=>"gamestatus.php", "text" => "Return to game status page"); 
        message("Game edit failed!", $message, $button);
    }
}



//all checks done!! now to do the thing!

$query = "UPDATE games
          SET name='{$title}', emoji='{$emoji}', min_players='{$min}', max_players='{$max}', ownership='{$own}', category='{$cat}'
          WHERE id='{$gameid}'";

$result = $db->query($query);

if($result === TRUE)
{
    $message = "Successfully edited {$emoji} {$name}!";
    $button = array("href"=>"gamestatus.php", "text" => "Return to game status page"); 
    message("Game edit success!", $message, $button, "primary");
}
else
{
    $message = "Submission failed at database. Contact your administrator.";
    $button = array("href"=>"gamestatus.php", "text" => "Return to game status page"); 
    message("Game edit failed!", $message, $button);
}

echo("This shouldn't possibly appear. Contact your admin.");
?>