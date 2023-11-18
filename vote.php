<?php

$MAX_VOTES = parse_ini_file("constants.ini")["max_votes"];

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
    $message = "User not found! Contact your administrator.";
    $button = array("href"=>"votepage.php", "text"=>"Return to voting page");
    message("Voting failed!", $message, $button);
}

if(!isset($_POST["vote"]))
{
    $message = "No votes submitted!";
    $button = array("href"=>"votepage.php", "text"=>"Return to voting page");
    message("Voting failed!", $message, $button);
}

$votes = $_POST["vote"];
if(!is_array($votes))
{
    //ok - if valid it should ALWAYS _POST as array - even with just one value.
    //so, if it's not an array...
    
    $message = "Incorrect vote formatting. Contact your administrator.";
    $button = array("href"=>"votepage.php", "text"=>"Return to voting page");
    message("Voting failed!", $message, $button);
}

if(!array_is_list($votes))
{
    $message = "Incorrect vote formatting. Contact your administrator.";
    $button = array("href"=>"votepage.php", "text"=>"Return to voting page");
    message("Voting failed!", $message, $button);
}

if(count($votes) == 0)
{
    $message = "Empty vote array? What are you doing??";
    $button = array("href"=>"votepage.php", "text"=>"Return to voting page");
    message("Voting failed!", $message, $button);
}

if(count($votes) > $MAX_VOTES)
{
    $message = "More votes than currently allowed max ({$MAX_VOTES}).";
    $button = array("href"=>"votepage.php", "text"=>"Return to voting page");
    message("Voting failed!", $message, $button);
}

for ($i = 0; $i < count($votes); $i++)
{
    
    $votes[$i] = $db->real_escape_string($votes[$i]);
    
    $result = $db->query("SELECT IF(EXISTS( SELECT 1 FROM games WHERE id='{$votes[$i]}' ), 1, 0)");
    
    if($result->fetch_array()[0] == 0)
    {
        $message = "Voting for non-existent game? (id={$votes[$i]})";
        $button = array("href"=>"votepage.php", "text"=>"Return to voting page");
        message("Voting failed!", $message, $button);
    }
    
    $result = $db->query("SELECT IF(EXISTS( SELECT 1 FROM game_status WHERE game_id='{$votes[$i]}' AND player_id='{$user}' ), 1, 0)");
    
    if($result->fetch_array()[0] == 0)
    {
        $message = "Fill out your game status first.";
        $button = array("href"=>"newgamestatus.php", "text"=>"Go");
        message("Voting failed!", $message, $button);
    }
    
}


$query = "SELECT game_id, current_vote
          FROM game_status
          WHERE player_id='{$user}' AND DATEDIFF(NOW(), game_status.last_voted_for) < 6;";
//could be 7, but then we would get things where if you vote on Saturday one week and Friday the next,
//it would interpret that as the same week.
//ideally, this would actually look at the day of the week and reset on Saturday(/Sunday?)..
//...which is achievable, but i don't want to figure it out right now.
//TODO: that.

//anyway, for now if you consistently vote on either Friday or Saturday, it'll be fine.

$result = $db->query($query);

$unchangeable_votes = 0;
$changeable_votes = array();

$vote = $result->fetch_assoc();
while($vote != null)
{
    
    if ($vote["current_vote"] == 0)
    {
        $unchangeable_votes++;
        $vote = $result->fetch_assoc();
        continue;
    }
    
    //delaying vote removal so it isn't done if the number of unchangeable votes would push us past max
    array_push($changeable_votes, $vote);
    
    $vote = $result->fetch_assoc();
}

if(count($votes) + $unchangeable_votes > $MAX_VOTES)
{
    $message = "Too many unchangeable votes to put you under max ({$MAX_VOTES}).";
    $button = array("href"=>"votepage.php", "text"=>"Return to voting page");
    message("Voting failed!", $message, $button);
}

//ok, we better not have any more validation past here

foreach ($changeable_votes as $vote)
{
    $query = "UPDATE game_status
              SET current_vote = current_vote - 1, historical_vote = historical_vote - 1, last_voted_for = null
              WHERE player_id='{$user}' AND game_id='{$vote["game_id"]}';";
    
    try
    {
        $result = $db->query($query);
    }
    catch (Exception $e)
    {
        $message = "Vote removal failed at database level.";
        $button = array("href"=>"votepage.php", "text"=>"Return to voting page");
    
        message("Voting failed!", $message, $button);
    }
    
    if($result === false)
    {
        $message = "Vote removal failed at database level.";
        $button = array("href"=>"votepage.php", "text"=>"Return to voting page");
    
        message("Voting failed!", $message, $button);
    }
}

foreach ($votes as $vote)
{
    $query = "UPDATE game_status
              SET current_vote = current_vote + 1, historical_vote = historical_vote + 1, last_voted_for = NOW()
              WHERE player_id='{$user}' AND game_id='{$vote}';";
    
    try
    {
        $result = $db->query($query);
    }
    catch (Exception $e)
    {
        $message = "Vote adding failed at database level.";
        $button = array("href"=>"votepage.php", "text"=>"Return to voting page");
    
        message("Voting failed!", $message, $button);
    }
    
    if($result === false)
    {
        $message = "Vote adding failed at database level.";
        $button = array("href"=>"votepage.php", "text"=>"Return to voting page");
    
        message("Voting failed!", $message, $button);
    }
}

$message = "Votes accepted!";
$button = array("href"=>"homepage.php", "text"=>"Return to homepage");
message("Voted!", $message, $button, "primary");

echo("End of file reached.");

?>