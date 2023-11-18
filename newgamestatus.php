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

?>
<!DOCTYPE html>
<html lang="en-US">
    <head>
        <title>New Game Status Required</title>
        
        <?php require('header_boilerplate.html'); ?>
        
        <script src="multiformsubmit.js" >
        </script>
        
    </head>
    <body>
        <div class="main">
            <a href="homepage.php">
                <img src="fishhat.png" alt="The Fish Hat" class="cap" />
            </a>
            <main class="main">
                <div class="toplevel primary">
                    <h3 class="title">New/nominated games require status:</h3>
                    <div class="flexrow">
                        <?php

$user = $_COOKIE['user'];

//confirm is real user
$user = $db->real_escape_string($user);
$result = $db->query("SELECT id FROM players WHERE id={$user}");
if($result->num_rows != 1)
{
    echo("Fake user. get outta here.");
    die();
}

$query = "SELECT id, name, emoji, ownership, nominated_by
          FROM games
          EXCEPT
          SELECT games.id, games.name, games.emoji, games.ownership, games.nominated_by
          FROM games JOIN game_status ON games.id = game_status.game_id
          WHERE game_status.player_id='{$user}'";

//ok. that should be good.
$result = $db->query($query);

$game = $result->fetch_assoc();
while($game != null)
{
    echo("              <form class='midlevel secondary flexcolumn'
                              action='creategamestatus.php'
                              method='POST'
                              autocomplete='off'> 
                            <input type='hidden' name='game' value='{$game["id"]}' />
                            <h4 class='title'>{$game["emoji"]} {$game["name"]}</h4>");
    
    //nomination block
    if($game["nominated_by"] != null)
    {
        if($game["nominated_by"]== $user)
        {
            echo("          <span>Nominated by: You</span>");
        }
        else
        {
            $subresult = $db->query("SELECT name, short_name FROM players WHERE id='{$game["nominated_by"]}'");
            if($subresult->num_rows != 1)
            {
                echo("Error: nominator rows != 1! Contact admin.");
            }
            $nom_by = $subresult->fetch_assoc();
            
            echo("          <span>Nominated by: 
                                <span class='shrinkable down'>
                                    <span class='short'>{$nom_by["short_name"]}</span>
                                    <span class='long'>{$nom_by["name"]}</span>
                                </span>
                            </span>
                            <span class='lowlevel'>
                                <input type='checkbox' id='second {$game["id"]}' name='second' value='yes' /><label for='second {$game["id"]}' class='text'>Second nomination</label>
                            </span>");
        }
    }
    
    echo("                  <select id='willing {$game["id"]}' class='action' name='willing' required>
                                <option></option>
                                <option value='good'>Will Play</option>
                                <option value='veto'>Veto</option>
                                <option value='tech'>Technical Difficulty</option>
                            </select>");
    
    switch($game["ownership"])
    {
        case "all":
            echo("          <span class='lowlevel'>
                                <input type='checkbox' id='owned {$game["id"]}' name='owned' value='yes' /><label for='owned {$game["id"]}' class='text'>Owned</label>
                            </span>");
            break;
        case "one":
            echo("          <span class='lowlevel'>
                                <input type='checkbox' id='owned {$game["id"]}' name='owned' value='yes' /><label for='owned' class='text'>Can host</label>
                            </span>");
            break;
    }
    //no case for "none" because no ownership information needed
    
    echo("              </form>");
    
    $game = $result->fetch_assoc();
}

                        ?>
                     </div>
                     <button class="big action" type="submit" onclick="submitAllForms(this)">Submit all</button>
                     <!--TODO: link back to whichever you came from (gamestatus, vote)-->
                </div>
            </main>
        </div>
    </body>
</html>