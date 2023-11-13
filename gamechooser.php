<!DOCTYPE html>
<html lang="en-US">
    <head>
        <title>Game Chooser</title>
        
        <?php require('header_boilerplate.html'); ?>
        
        <script src="gamechooser.js" >
        </script>
        
        <!--TODO: redirect if not logged in-->
        
    </head>
    <body>
        <div class="main">
            <a href="homepage.php">
                <img src="fishhat.png" alt="The Fish Hat" class="cap" />
            </a>
            <main class="main"> <!--this seems like it should restrict the width by 80% then 80%,
                                    but it uses the total window width instead of the container width
                                    and i don't know why. -->
                <form class="toplevel primary">
                    <h1 class="title">
                        Select Players
                    </h1>
                    <!--TODO: replace sample data with PHP-->
                    <span class="player">
                        <input type="checkbox" id="Iri" name="Iri" />
                        <label for="Iri" class="shrinkable down">
                            <span class="short">Iri</span>
                            <span class="long">Iri</span>
                        </label>
                    </span>
                    <span class="player">
                        <input type="checkbox" id="the Fox of the Asterisk" name="the Fox of the Asterisk" />
                        <label for="the Fox of the Asterisk" class="shrinkable down">
                            <span class="short">*Fox</span>
                            <span class="long">the Fox of the Asterisk</span>
                        </label>
                    </span>
                    <span class="player">
                        <input type="checkbox" id="NeedADispenserHere" name="NeedADispenserHere" />
                        <label for="NeedADispenserHere" class="shrinkable down">
                            <span class="short">🐟🧢</span>
                            <span class="long">NeedADispenserHere</span>
                        </label>
                    </span>
                    <span class="player">
                        <input type="checkbox" id="KonnorLetterBee" name="KonnorLetterBee" />
                        <label for="KonnorLetterBee" class="shrinkable down">
                            <span class="short">Corey</span>
                            <span class="long">KonnorLetterBee</span>
                        </label>
                    </span>
                    <span class="player">
                        <input type="checkbox" id="ShenzoTheLost" name="ShenzoTheLost" />
                        <label for="ShenzoTheLost" class="shrinkable down">
                            <span class="short">💀</span>
                            <span class="long">ShenzoTheLost</span>
                        </label>
                    </span>
                    <span class="player">
                        <input type="checkbox" id="flame.leaf" name="flame.leaf" />
                        <label for="flame.leaf" class="shrinkable down">
                            <span class="short">🔥.🍁</span>
                            <span class="long">flame.leaf</span>
                        </label>
                    </span>
                </form>
                <details class="toplevel secondary">
                    <summary>Show game weights</summary>
                    <span id="weightsBox" class="flexrowrev">
                        <!--TODO: replace sample data with PHP-->
                        <button class="game weight" 
                                id="Root: A Game of Woodland Might and Right" 
                                name="😾 Root: A Game of Woodland Might and Right" 
                                value=13
                                onclick="selectGame(this)"
                                style="order: 13">
                            😾 13
                        </button>
                        <button class="issue weight"
                                id="Minecraft (Vanilla)"
                                name="⛏️ Minecraft (Vanilla)"
                                value=4
                                onclick="selectGame(this)"
                                style="order: 4">
                            ⛏️ 4 <p id="issues" style="display: none;"><b>flame.leaf</b> does not enjoy playing <b>Minecraft (Vanilla)</b></p>
                        </button>
                        <button class="game weight" 
                                id="Golf with your Friends" 
                                name="🏌️‍♀️ Golf with your Friends" 
                                value=11
                                onclick="selectGame(this)"
                                style="order: 11">
                            🏌️‍♀️ 11
                        </button>
                        <button class="game weight" 
                                id="Duck Game" 
                                name="🦆 Duck Game" 
                                value=7
                                onclick="selectGame(this)"
                                style="order: 7">
                            🦆 7
                        </button>
                        <button class="issue weight"
                                id="Skullgirls"
                                name="💀 Skullgirls"
                                value=2
                                onclick="selectGame(this)"
                                style="order: 2">
                            💀 2 <p id="issues" style="display: none;"><b>the Fox of the Asterisk</b> does not enjoy playing <b>Skullgirls</b></p>
                        </button>
                        <button class="issue weight"
                                id="Pummel Party"
                                name="🤛 Pummel Party"
                                value=1
                                onclick="selectGame(this)"
                                style="order: 1">
                            🤛 1 <p id="issues" style="display: none;"><b>the Fox of the Asterisk</b> has had technical difficulties with <b>Pummel Party</b></p>
                        </button>
                    </span>
                </details>
                <div class="flexrow">
                    <form id="IssueOutput" 
                          class="toplevel issue flexcolumn" 
                          style="display:none;" 
                          action="playgame.php" 
                          method="POST">
                        <h3 id="title" class="title"></h3>
                        <p id="issues" class="text"></p>
                        <button id="button" 
                                name="game" 
                                value="none" 
                                class="medium action" 
                                type="submit" >
                            Play!
                        </button>
                    </form>
                    <form id="Output" 
                          class="toplevel game flexcolumn" 
                          style="display: none;"
                          action="playgame.php" 
                          method="POST">
                        <h3 id="title" class="title"></h3>
                        <br />
                        <button id="button" 
                                name="game" 
                                value="none" 
                                class="medium action" 
                                type="submit" >
                            Play!
                        </button>
                    </form>
                </div>
                <button class="big action" 
                        onclick="chooseGame()"
                        style="display:inline-block">Choose a game!</button>
                <div id="logbox"></div>
            </main>
        </div>
    </body>
</html>