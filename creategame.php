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

$user = $_COOKIE['user'];
$user = $db->real_escape_string($user);

$result = $db->query("SELECT COUNT(*) FROM players WHERE name='{$user}';");
$row = $result->fetch_array();
if($row[0] == 0)
{
    $message = '<p>
                    Submitting user not found! Contact your admin.
                </p>
                <a href="homepage.php"><button class="medium action">Return to homepage</button></a>';
    
    displayMessage("Nomination failed!", $message);
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
$game = $_POST['name'];
$game = $db->real_escape_string($game);

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



echo("Congrats, it reached the end.");
?>