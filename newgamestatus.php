<!DOCTYPE html>
<html lang="en-US">
    <head>
        <title>New Game Status Required</title>
        
        <?php require('header_boilerplate.html'); ?>
        
        <!-- TODO: redirect if not logged in -->
        
    </head>
    <body>
        <div class="main">
            <a href="homepage.php">
                <img src="fishhat.png" alt="The Fish Hat" class="cap" />
            </a>
            <main class="main"> <!--this seems like it should restrict the width by 80% then 80%,
                                    but it uses the total window width instead of the container width
                                    and i don't know why. (maybe because it's a <main>?) -->
                <div class="toplevel primary">
                    <h3 class="title">New/nominated games require status:</h3>
                    <div class="flexrow">
                        <!--TODO: populate with PHP -->
                        <form class="midlevel secondary flexcolumn"
                              action="editgamestatus.php"
                              target="_blank"
                              method="HEAD"
                              autocomplete="off"> <!--TODO: once again HEAD is only for debug purposes
                                                            also _blank--> 
                            <input type="hidden" name="player" value="the Fox of the Asterisk" />
                            <input type="hidden" name="game" value="Terraria" />
                            <h4 class="title">🌳 Terraria</h4>
                            <select id="🌳 willing" class="action" name="willing" required>
                                <option></option>
                                <option value="good">Will Play</option>
                                <option value="veto">Veto</option>
                                <option value="tech">Technical Difficulty</option>
                            </select>
                            <span class="lowlevel">
                                <input type="checkbox" id="🌳 owned" name="owned" value="yes" /><label for="🌳 owned" class="text">Owned</label>
                            </span>
                        </form>
                        
                        <form class="midlevel secondary flexcolumn"
                              action="editgamestatus.php"
                              target="_blank"
                              method="HEAD"
                              autocomplete="off"> <!--TODO: once again HEAD is only for debug purposes
                                                            also _blank--> 
                            <input type="hidden" name="player" value="the Fox of the Asterisk" />
                            <input type="hidden" name="game" value="Jackbox" />
                            <h4 class="title">💩 Jackbox</h4>
                            <span class="lowlevel">
                                <input type="checkbox" id="💩 second" name="second" value="yes" /><label for="💩 second" class="text">Second nomination</label>
                            </span>
                            <select id="💩 willing" class="action" name="willing" required>
                                <option></option>
                                <option value="good">Will Play</option>
                                <option value="veto">Veto</option>
                                <option value="tech">Technical Difficulty</option>
                            </select>
                            <span class="lowlevel">
                                <input type="checkbox" id="💩 owned" name="owned" value="yes" /><label for="💩 owned" class="text">Owned</label>
                            </span>
                        </form>
                        
                        <form class="midlevel secondary flexcolumn"
                              action="editgamestatus.php"
                              target="_blank"
                              method="HEAD"
                              autocomplete="off"> <!--TODO: once again HEAD is only for debug purposes
                                                            also _blank--> 
                            <input type="hidden" name="player" value="the Fox of the Asterisk" />
                            <input type="hidden" name="game" value="Codenames" />
                            <h4 class="title">🕵️ Codenames</h4>
                            <span class="lowlevel">
                                <input type="checkbox" id="🕵️ second" name="second" value="yes" /><label for="🕵️ second" class="text">Second nomination</label>
                            </span>
                            <select id="🕵️ willing" class="action" name="willing" required>
                                <option></option>
                                <option value="good">Will Play</option>
                                <option value="veto">Veto</option>
                                <option value="tech">Technical Difficulty</option>
                            </select>
                        </form>
                        
                        <!-- alright. the plan is:
                             have a bunch of forms, one for each game that needs a status update
                             use js fetch to send them all without loading a new page
                             (verify this in some way? need to figure that out)
                             after doing all fetches, redirects to homepage -->
                     </div>
                     <button class="big action" type="submit">Submit all</button>
                </div>
            </main>
        </div>
    </body>
</html>