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
    $message = 'Submitting user not found! Contact your admin.';
    $button = array("href"=>"homepage.php", "text" => "Return to homepage"); 
    message("Play submission failed!", $message, $button);
}

//ok, that was standard intro for a non-display page.
//Now...

if(!isset($_POST["player"]))
{
    $message = "No one is playing...";
    $button = array("href"=>"gamechooser.php", "text" => "Return to game chooser"); 
    message("Play submission failed!", $message, $button);
}

$players = $_POST["player"];

if(!is_array($players))
{
    $message = "Incorrect formatting. Get outta here.";
    $button = array("href"=>"gamechooser.php", "text" => "Return to game chooser"); 
    message("Play submission failed!", $message, $button);
}

if(count($players) == 0)
{
    $message = "This shouldn't happen? player.count = 0";
    $button = array("href"=>"gamechooser.php", "text" => "Return to game chooser"); 
    message("Play submission failed!", $message, $button);
}

for($i = 0; $i < count($players); $i++)
{
    $players[$i] = $db->real_escape_string($players[$i]);
    
    $result = $db->query("SELECT COUNT(*) FROM players WHERE id='{$players[$i]}';");
    $row = $result->fetch_array();
    
    if($row[0] != 1)
    {
        $message = 'Player {$players[$i]} does not exist! WooOoOOo, spoopy ghost.';
        $button = array("href"=>"homepage.php", "text" => "Return to homepage"); 
        message("Play submission failed!", $message, $button);
    }
}

//ok - all the players are real (& safe). Great!
$playerCSV = implode(", ", $players);


if(!isset($_POST["game"]))
{
    $message = "You have played no game. Success! (Not really.)";
    $button = array("href"=>"gamechooser.php", "text" => "Return to game chooser"); 
    message("Play submission failed!", $message, $button);
}

$game = $_POST["game"];

$game = $db->real_escape_string($game);

$result = $db->query("SELECT name, emoji FROM games WHERE id='{$game}';");
if($result->num_rows != 1)
{
    $message = 'This game (id={$game}) does not exist.';
    $button = array("href"=>"homepage.php", "text" => "Return to homepage"); 
    message("Play submission failed!", $message, $button);
}

$gameVals = $result->fetch_assoc();

//game is also good.
//alrighty then!
//the only other thing to verify would be that each player-game combo has a game_status...

//...but then i guess they don't actually need one, huh?
//if there isn't one, they can't have voted for it, and therefore, nothing need be done.
//great!

$query = "UPDATE game_status
          SET current_vote = 0
          WHERE game_id = {$game} AND player_id IN ({$playerCSV});";
//oh. that one was simple!

$result = $db->query($query);

if($result == false)
{
    $message = 'Gameplay submission failed at database level. Contact your administrator.';
    $button = array("href"=>"homepage.php", "text" => "Return to homepage"); 
    message("Play submission failed!", $message, $button);
}

$message = "Confirmed: You played {$gameVals["emoji"]} {$gameVals["name"]}.";
$button = array("href"=>"gamechooser.php", "text" => "Return to game chooser"); 
message("Game Played!", $message, $button, "primary");

echo("end of file.");

?>