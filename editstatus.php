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

$result = $db->query("SELECT COUNT(*) FROM players WHERE id='{$user}';");
$row = $result->fetch_array();
if($row[0] == 0)
{
    $message = "User not found! Contact your administrator.";
    $button = array("href"=>"homepage.php", "text"=>"Return to homepage");
    message("Bad edit request", $message, $button);
}

if(!isset($_GET["game"]))
{
    $message = "No game selected to edit";
    $button = array("homepage.php", "Return to homepage");
    $message("Bad edit request", $message, $button);
}

$gameid = $db->real_escape_string($_GET["game"]);

$result = $db->query("SELECT name, emoji, ownership, nominated_by
                      FROM games
                      WHERE id='{$gameid}'");

if($result->num_rows != 1)
{
    $message = "Game {$gameid} does not exist.";
    $button = array("homepage.php", "Return to homepage");
    $message("Bad edit request", $message, $button);
}

$game = $result->fetch_assoc();

$result = $db->query("SELECT owned, status
                      FROM game_status
                      WHERE game_id='{$gameid}' AND player_id='{$user}';");

if($result->num_rows == 0)
{
    redirect("newgamestatus.php");
}
else if($result->num_rows > 1)
{
    $message = "Duplicate game_status found! Contact your administrator.";
    $button = array("href"=>"homepage.php", "text"=>"Return to homepage");
    message("Bad edit request", $message, $button);
}

$status = $result->fetch_assoc();

$nom_by = null;

if($game["nominated_by"] != null && $game["nominated_by"] != $user)
{
    $result = $db->query("SELECT name, short_name FROM players WHERE id='{$game["nominated_by"]}'");
    if($result->num_rows != 1)
    {
        $message = "Error: nominator rows != 1! Contact admin.";
        $button = array("href"=>"homepage.php", "text"=>"Return to homepage");
        message("Bad edit request", $message, $button);
    }
    $nom_by = $result->fetch_assoc();
}

//that's all the verifying we need. On to the page!

?>
<!DOCTYPE html>
<html lang="en-US">
    <head>
        <title>Edit game status</title>
        
        <?php require('header_boilerplate.html'); ?>
        
    </head>
    <body>
        <div class="main">
            <a href="homepage.php">
                <img src="fishhat.png" alt="The Fish Hat" class="cap" />
            </a>
            <main class="main">
                <div class="toplevel primary">
                    <h3 class="title">Editing status for <?php echo("{$game["emoji"]} {$game["name"]}");?></h3>
                    <div class="flexrow">
                        <form class='midlevel secondary flexcolumn'
                              action='savegamestatus.php'
                              method='POST'
                              autocomplete='off'> 
                            <?php
echo("                      <input type='hidden' name='game' value='{$gameid}' />");

//nomination block
if($game["nominated_by"] != null)
{
    if($game["nominated_by"]== $user)
    {
        echo("              <span>Nominated by: You</span>");
    }
    else
    {
        echo("              <span>Nominated by: 
                                <span class='shrinkable down'>
                                    <span class='short'>{$nom_by["short_name"]}</span>
                                    <span class='long'>{$nom_by["name"]}</span>
                                </span>
                            </span>
                            <span class='lowlevel'>
                                <input type='checkbox' id='second' name='second' value='yes' /><label for='second' class='text'>Second nomination</label>
                            </span>");
    }
}                           ?>
                            <select id='willing' class='action' name='willing' required>
                                <option value='good' 
                                    <?php if($status["status"]=="good"){ echo("selected");}?> >Will Play</option>
                                <option value='veto'
                                    <?php if($status["status"]=="veto"){ echo("selected");}?> >Veto</option>
                                <option value='tech'
                                    <?php if($status["status"]=="tech"){ echo("selected");}?> >Technical Difficulty</option>
                            </select>
                            <?php
if($game["ownership"] != "free")
{
    echo("                 <span class='lowlevel'>
                                <input type='checkbox' 
                                       id='owned' 
                                       name='owned' 
                                       value='yes' ");
    if($status["owned"])
    {
        echo("                         checked");
    }
    echo ("                             />
                                    <label for='owned' class='text'>");
    
    switch($game["ownership"])
    {
        case "all":
            echo("Owned");
            break;
        case "one":
            echo("Can host");
            break;
    }
    
    echo("</label>
                                </span>");
}
                            ?>
                            <button class="medium action" type="submit">Submit</button>
                        </form>
                        <form action='editgame.php' method='GET'>
                            <button type='submit' 
                                    class='medium action' 
                                    name='game' 
                                    value='<?=$gameid?>'>Edit global game data</button>
                        </form>
                     </div>
                </div>
            </main>
        </div>
    </body>
</html>