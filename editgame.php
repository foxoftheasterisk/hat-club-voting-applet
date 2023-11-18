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

//game validation & loading

if(!isset($_GET["game"]))
{
    $message = "No game selected to edit";
    $button = array("homepage.php", "Return to homepage");
    $message("Bad edit request", $message, $button);
}

$gameid = $db->real_escape_string($_GET["game"]);

$result = $db->query("SELECT name, emoji, min_players, max_players, ownership, category
                      FROM games
                      WHERE id='{$gameid}'");

if($result->num_rows != 1)
{
    $message = "Game {$gameid} does not exist.";
    $button = array("homepage.php", "Return to homepage");
    $message("Bad edit request", $message, $button);
}

$game = $result->fetch_assoc();

$gameName = str_replace('"', "&quot;", $game["name"]);
$emoji = str_replace('"', "&quot;", $game["emoji"]);

?>
<!DOCTYPE html>
<html lang="en-US">
    <head>
        <title>Edit game</title>
        
        <?php require('header_boilerplate.html'); ?>
        
    </head>
    <body>
        <div class="main">
            <a href="homepage.php">
                <img src="fishhat.png" alt="The Fish Hat" class="cap" />
            </a>
            <main class="main">
                <form class="toplevel primary"
                      action="savegame.php"
                      method="POST"
                      autocomplete="off">
                    <input type="hidden" name="id" value="<?=$gameid?>" />
                    <h1 class="title">Editing <?=$game["emoji"]?> <?=$gameName?></h1>
                    <div class="flexrow">
                        <div class="lowlevel flexcolumn">
                            <label for="gameName">Game Title:</label>
                            <input type="text" 
                                   class="action" 
                                   id="gameName" 
                                   name="name" 
                                   value="<?=$gameName?>" 
                                   required />
                        </div>
                        
                        <div class="lowlevel flexcolumn">
                            <label for="emoji">Emoji:</label>
                            <input type="text" 
                                   class="action" 
                                   id="emoji" 
                                   name="emoji" 
                                   size="2" 
                                   maxlength="5" 
                                   value="<?=$emoji?>"
                                   required />
                        </div>
                        
                        <div class="lowlevel flexcolumn">
                            <label>Players:</label>
                            <span>
                                <input type="number" 
                                       class="action" 
                                       id="minPlay" 
                                       name="minimumplayers" 
                                       size="3" 
                                       min="1"
                                       value="<?=$game["min_players"]?>"
                                       required 
                              />–<input type="number" 
                                        class="action" 
                                        id="maxPlay" 
                                        name="maximumplayers" 
                                        size="3"
                                        min="1"
                                        <?php
if($game["max_players"] != "9999")
{
    echo("                              value='{$game["max_players"]}'
                                        required");
}
else
{
    echo("                              disabled");
}
                                        ?>
                                         />
                            </span>
                            <!--TODO: javascript verify that max >= min before submit-->
                            <label for="infPlay">(<input type="checkbox" 
                                                         class="action" 
                                                         id="infPlay" 
                                                         name="maximumplayers" 
                                                         value="∞" 
                                                         onchange="swapMaxPlay(this)" 
                                                         <?php if($game["max_players"] == "9999") 
                                                               { echo("checked"); } ?>
                                                         />Unlimited)</label>
                            
                            <script>
                                function swapMaxPlay(infBox)
                                {
                                    let playerCounter = document.getElementById("maxPlay");
                                    if(infBox.checked) {
                                        playerCounter.disabled=true;
                                        playerCounter.required=false;
                                        //infBox.name = "maximumplayers";
                                    }
                                    else
                                    {
                                        playerCounter.disabled=false;
                                        playerCounter.required=true;
                                        //infBox.name = "infiniteplayers";
                                    }
                                }
                            </script>
                        </div>
                        
                        <div class="lowlevel flexcolumn">
                            <label for="own" class="narrow">Ownership required:</label>
                            <select id="own" class="action" name="own" required>
                                <option value="all" 
                                        <?php if ($game["ownership"] == "all") { echo("selected"); } ?>
                                        >Everyone</option>
                                <option value="one"
                                        <?php if ($game["ownership"] == "one") { echo("selected"); } ?>
                                        >Host only</option>
                                <option value="free"
                                        <?php if ($game["ownership"] == "free") { echo("selected"); } ?>
                                        >Free</option>
                            </select>
                        </div>
                        
                        <div class="lowlevel flexcolumn">
                            <label for="genre" class="narrow">Genre/Category:</label>
                            <select id="genre" class="action" name="genre" onchange="showHideNewGenre(this)" required>
                                <?php

$query = "SELECT DISTINCT category FROM games ORDER BY category";
$result = $db->query($query);
$row = $result->fetch_array();

while($row != null)
{
    $cat = str_replace("'", "&apos;", $row[0]);
    
    echo("                      <option value='{$cat}' ");
    if($cat == $game["category"])
    {
        echo("                          selected ");
    }
    echo("                              >{$cat}</option>");
    
    $row = $result->fetch_array();
}

                                ?>
                                <option value="New">New...</option>
                            </select>
                            <span id="newgenrecont" class="flexcolumn" style="display: none;" >
                                <label for="newgenre">New genre:</label>
                                <input type="text" class="action" id="newgenre" name="newgenre" disabled />
                            </span>
                            
                            <script>
                                function showHideNewGenre(genreSelector)
                                {
                                    let newGenreBox = document.getElementById("newgenrecont");
                                    let newGenreInput = document.getElementById("newgenre");
                                    if(genreSelector.value == "New") {
                                        newGenreBox.style.display = "flex";
                                        newGenreInput.disabled = false;
                                        newGenreInput.required = true;
                                    }
                                    else {
                                        newGenreBox.style.display = "none";
                                        newGenreInput.disabled = true;
                                        newGenreInput.required = false;
                                    }
                                }
                            </script>
                        </div>
                        
                    </div>
                    <div class="flexrow">
                        <a href="gamestatus.php">
                            <button type='button' class='medium action'>Return to game status page</button>
                        </a>
                        <button class="medium action" type="submit">Confirm changes</button> 
                    </div>
                </form>
            </main>
        </div>
    </body>
</html>