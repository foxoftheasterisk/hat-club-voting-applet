<!DOCTYPE html>
<html lang="en-US">
    <head>
        <title>Nominate game</title>
        
        <?php require('header_boilerplate.html'); ?>
        
        <!--TODO: redirect if not logged in-->
        
    </head>
    <body>
        <div class="main">
            <a href="homepage.php">
                <img src="fishhat.png" alt="The Fish Hat" class="cap" />
            </a>
            <main class="main"> <!--this seems like it should restrict the width by 80% then 80%,
                                    but it uses the total window width instead of the container width
                                    and i don't know why. (maybe because it's a <main>?) -->
                <form class="toplevel primary"
                      action="editgame.php"
                      method="POST"
                      autocomplete="off">
                    <h1 class="title">Nominate a game!</h1>
                    <input type="hidden" name="nominator" value="the Fox of the Asterisk"> 
                    <!-- TODO: make this fill name with PHP -->
                    <div class="flexrow">
                        
                        <div class="midlevel flexcolumn">
                            <label for="gameName">Game Title:</label>
                            <input type="text" class="action" id="gameName" name="name" required />
                        </div>
                        
                        <div class="midlevel flexcolumn">
                            <label for="emoji">Emoji:</label>
                            <input type="text" class="action" id="emoji" name="emoji" size="1" maxlength="4" required/>
                        </div>
                        
                        <div class="midlevel flexcolumn">
                            <label>Players:</label>
                            <span>
                                <input type="number" 
                                       class="action" 
                                       id="minPlay" 
                                       name="minimumplayers" 
                                       size="3" 
                                       required 
                              />–<input type="number" 
                                        class="action" 
                                        id="maxPlay" 
                                        name="maximumplayers" 
                                        size="3"
                                        required />
                            </span>
                            <label for="infPlay">(<input type="checkbox" 
                                                         class="action" 
                                                         id="infPlay" 
                                                         name="maximumPlayers" 
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
                                    }
                                    else
                                    {
                                        playerCounter.disabled=false;
                                        playerCounter.required=true;
                                    }
                                }
                            </script>
                        </div>
                        
                        <div class="midlevel flexcolumn">
                            <label for="own" class="narrow">Ownership required:</label>
                            <select id="own" class="action" name="own" required>
                                <option></option>
                                <option value="all">Everyone</option>
                                <option value="host">Host only</option>
                                <option value="none">Free</option>
                            </select>
                        </div>
                        
                        <div class="midlevel flexcolumn">
                            <label for="genre" class="narrow">Genre/Category:</label>
                            <select id="genre" class="action" name="genre" onchange="showHideNewGenre(this)" required>
                                <option></option>
                                <!--TODO: populate with PHP rather than sample values-->
                                <option value="Roguelike">Roguelike</option>
                                <option value="Board">Board</option>
                                <option value="Party">Party</option>
                                <option value="Sandbox">Sandbox</option>
                                <option value="Brawl">Brawl</option>
                                <option value="Fighter">Fighter</option>
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