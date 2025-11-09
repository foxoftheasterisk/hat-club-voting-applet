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
    message("Nomination failed!", $message, $button);
}

//check that name exists
if(!isset($_POST['name']))
{
    $message = "<p>
                    No title submitted.
                </p>
                <div class='flexrow'>
                    <a href='homepage.php'><button class='medium action'>Return to homepage</button></a>
                    <a href='nominate.php'><button class='medium action'>Try again</button></a>
                </div>";
    
    displayMessage("Nomination failed!", $message);
}

//check game does not already exist
$name = $_POST['name'];
$game = $db->real_escape_string($name);

$result = $db->query("SELECT * FROM games WHERE name='{$game}';");
if($result->num_rows != 0)
{
    if($result->num_rows > 1)
    {
        $message = "<p>
                        Multiple games with this title exist??
                    </p>
                    <div class='flexrow'>
                        <a href='homepage.php'><button class='medium action'>Return to homepage</button></a>
                        <a href='nominate.php'><button class='medium action'>Try again</button></a>
                    </div>";
    
        displayMessage("Nomination failed!", $message);
    }
    
    $row = $result->fetch_assoc();
    
    $message = "<h4 class='title'>
                    Game already exists!
                </h4>
                <div class='midlevel secondary flexcolumn'>
                    <span class='nowrap'>{$row["emoji"]} {$row["name"]}</span>
                    <span>{$row["category"]}</span>
                    <span>{$row["min_players"]}–{$row["max_players"]} players</span>
                    <span>Ownership needed: {$row["ownership"]}</span>";
    
    
    if($row["nominated_by"] != null)
    {
        
        $result = $db->query("SELECT name FROM players WHERE id={$row["nominated_by"]};");
        $nominator = $result->fetch_array()[0];
        
        $message .= "    <span>Nominated by: {$nominator}</span>";
        
    }
    
    
    $message .="</div>
                <div class='flexrow'>
                <a href='homepage.php'><button class='medium action'>Return to homepage</button></a>";
    
    if($row["nominated_by"] != null && $row["nominated_by"] != $_COOKIE["user"])
    {
        //TODO: change GET->POST after testing
        $message .= "<button type='submit' 
                              formaction='second.php' 
                              formmethod='GET' 
                              name='{$row["id"]}' 
                              value='on'
                              class='medium action'>Second nomination</button>";
    }
    
    $message .= "</div>";
    
    displayMessage("Nomination failed!", $message);
    
}

//now, much the same for emoji
//...could this be a function?
//...no i don't think so.
if(!isset($_POST['emoji']))
{
    $message = "<p>
                    No emoji submitted.
                </p>
                <div class='flexrow'>
                    <a href='homepage.php'><button class='medium action'>Return to homepage</button></a>
                    <a href='nominate.php'><button class='medium action'>Try again</button></a>
                </div>";
    
    displayMessage("Nomination failed!", $message);
}

//and check already used emoji...
$emoji = $_POST['emoji'];
$emoji = $db->real_escape_string($emoji);

$result = $db->query("SELECT emoji, name FROM games WHERE emoji='{$emoji}';");
if($result->num_rows != 0)
{
    if($result->num_rows > 1)
    {
        $message = "<p>
                        This emoji has already been used multiple times??
                    </p>
                    <div class='flexrow'>
                        <a href='homepage.php'><button class='medium action'>Return to homepage</button></a>
                        <a href='nominate.php'><button class='medium action'>Try again</button></a>
                    </div>";
    
        displayMessage("Nomination failed!", $message);
    }
    
    $row = $result->fetch_assoc();
    
    $message = "<p>
                    Emoji already in use:
                </p>
                <div class='midlevel secondary'>
                    <span>{$row["emoji"]} {$row["name"]}</span>
                </div>
                <div class='flexrow'>
                    <a href='homepage.php'><button class='medium action'>Return to homepage</button></a>
                    <a href='nominate.php'><button class='medium action'>Try again</button></a>
                </div>";
    
    displayMessage("Nomination failed!", $message);
}

if(!(isset($_POST['minimumplayers']) && isset($_POST['maximumplayers'])) )
{
    $message = "<p>
                    Player count(s) not submitted.
                </p>
                <div class='flexrow'>
                    <a href='homepage.php'><button class='medium action'>Return to homepage</button></a>
                    <a href='nominate.php'><button class='medium action'>Try again</button></a>
                </div>";
    
    displayMessage("Nomination failed!", $message);
}

$min = $_POST['minimumplayers'];
$max = $_POST['maximumplayers'];

if($max == "∞")
{
    $max = "9999";
}

if(!(is_numeric($min) && is_numeric($max)))
{
    $message = "<p>
                    One or both player counts is not a number!
                    min: {$min} - max: {$max}
                </p>
                <div class='flexrow'>
                    <a href='homepage.php'><button class='medium action'>Return to homepage</button></a>
                    <a href='nominate.php'><button class='medium action'>Try again</button></a>
                </div>";
    
    displayMessage("Nomination failed!", $message);
}

if($min > $max)
{
    $message = "<p>
                    Impossible player counts - min({$min}) cannot be higher than max ({$max})!
                </p>
                <div class='flexrow'>
                    <a href='homepage.php'><button class='medium action'>Return to homepage</button></a>
                    <a href='nominate.php'><button class='medium action'>Try again</button></a>
                </div>";
    
    displayMessage("Nomination failed!", $message);
}

//i don't think these should need escaping - they're guaranteed to be numeric
//still, it doesn't hurt.
$max = $db->real_escape_string($max);
$min = $db->real_escape_string($min);

if(!isset($_POST['own']))
{
    $message = "<p>
                    Ownership requirement not submitted.
                </p>
                <div class='flexrow'>
                    <a href='homepage.php'><button class='medium action'>Return to homepage</button></a>
                    <a href='nominate.php'><button class='medium action'>Try again</button></a>
                </div>";
    
    displayMessage("Nomination failed!", $message);
}

$own = $_POST['own'];

if(!($own == "all" || $own == "one" || $own == "free"))
{
    $message = "<p>
                    {$own} is not a valid ownership requirement!
                </p>
                <div class='flexrow'>
                    <a href='homepage.php'><button class='medium action'>Return to homepage</button></a>
                    <a href='nominate.php'><button class='medium action'>Try again</button></a>
                </div>";
    
    displayMessage("Nomination failed!", $message);
}

//ok this REALLY shouldn't need escaping
//im still doing it though
$own = $db->real_escape_string($own);

if(!isset($_POST['genre']))
{
    $message = "<p>
                    Genre/category not submitted.
                </p>
                <div class='flexrow'>
                    <a href='homepage.php'><button class='medium action'>Return to homepage</button></a>
                    <a href='nominate.php'><button class='medium action'>Try again</button></a>
                </div>";
    
    displayMessage("Nomination failed!", $message);
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

$query = "INSERT INTO games (name, emoji, min_players, max_players, ownership, category, nominated_by)
          VALUES ('{$game}', '{$emoji}', '{$min}', '{$max}', '{$own}', '{$cat}', '{$user}')";

$result = $db->query($query);

if($result === TRUE)
{
    $message = "<p>
                    Successfully nominated {$emoji} {$name}!
                </p>
                <div class='flexrow'>
                    <a href='newgamestatus.php?source=gamestatus'><button class='medium action'>Continue to status</button></a>
                </div>";
    
    displayMessage("Game nominated!", $message, "primary");
}
else
{
    $message = "<p>
                    Submission failed at database. Contact your administrator.
                </p>
                <div class='flexrow'>
                    <a href='homepage.php'><button class='medium action'>Return to homepage</button></a>
                    <a href='nominate.php'><button class='medium action'>Try again</button></a>
                </div>";
    
    displayMessage("Nomination failed!", $message);
}

echo("This shouldn't possibly appear. Contact your admin.");
?>