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
        <title>Nominate game</title>
        
        <?php require('header_boilerplate.html'); ?>
        
    </head>
    <body>
        <div class="main">
            <a href="homepage.php">
                <img src="fishhat.png" alt="The Fish Hat" class="cap" />
            </a>
            <main class="main">
                <form class="toplevel primary"
                      action="creategame.php"
                      method="POST"
                      autocomplete="off">
                    <h1 class="title">Nominate a game!</h1>
                    <div class="flexrow">
                        <div class="lowlevel flexcolumn">
                            <label for="gameName">Game Title:</label>
                            <input type="text" class="action" id="gameName" name="name" required />
                        </div>
                        
                        <div class="lowlevel flexcolumn">
                            <label for="emoji">Emoji:</label>
                            <input type="text" class="action" id="emoji" name="emoji" size="2" maxlength="5" required/>
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
                                       required 
                              />–<input type="number" 
                                        class="action" 
                                        id="maxPlay" 
                                        name="maximumplayers" 
                                        size="3"
                                        min="1"
                                        required />
                            </span>
                            <!--TODO: javascript verify that max >= min before submit-->
                            <label for="infPlay">(<input type="checkbox" 
                                                         class="action" 
                                                         id="infPlay" 
                                                         name="maximumplayers" 
                                                         value="∞" 
                                                         onchange="swapMaxPlay(this)" 
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
                                <option></option>
                                <option value="all">Everyone</option>
                                <option value="one">Host only</option>
                                <option value="free">Free</option>
                            </select>
                        </div>
                        
                        <div class="lowlevel flexcolumn">
                            <label for="genre" class="narrow">Genre/Category:</label>
                            <select id="genre" class="action" name="genre" onchange="showHideNewGenre(this)" required>
                                <option></option>
                                <?php

$query = "SELECT DISTINCT category FROM games";
$result = $db->query($query);
$row = $result->fetch_array();

while($row != null)
{
    $cat = str_replace("'", "&apos;", $row[0]);
    
    echo("                      <option value='{$cat}'>{$cat}</option>");
    
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
                    
                    <button class="medium action" type="submit">Nominate!</button> 
                </form>
            </main>
        </div>
    </body>
</html>