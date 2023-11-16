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
        <title>Game Chooser</title>
        
        <?php require('header_boilerplate.html'); ?>
        
        <script src="gamechooser.js" >
        </script>
        
    </head>
    <body>
        <div class="main">
            <a href="homepage.php">
                <img src="fishhat.png" alt="The Fish Hat" class="cap" />
            </a>
            <main class="main"> <!--this seems like it should restrict the width by 80% then 80%,
                                    but it uses the total window width instead of the container width
                                    and i don't know why. -->
                <form class="toplevel primary"
                      action="gamechooser.php"
                      method="GET"
                      id="playerform">
                    <h1 class="title">
                        Select Players
                    </h1>
                    <?php

$selected = array();

$query = "SELECT players.name, players.short_name, MIN(DATEDIFF(NOW(), game_status.last_voted_for)) AS time_since_vote
          FROM players LEFT JOIN game_status ON players.id = game_status.player_id
          GROUP BY players.id";

$result = $db->query($query);

$row = $result->fetch_assoc();

while($row != null)
{
    $user = $row['name'];
    $short = $row['short_name'];
    $days = $row['time_since_vote'];
    
    //the whitespace on the final page might be weird
    //but i'm matching it in file, ok?
    
    //escaping... well, this is why i made that test user.
    $user = str_replace("'", "&apos;", $user);
    $short = str_replace("'", "&apos;", $short);
    
    echo("          <span class='player'>
                        <input type='checkbox' id='{$short}' name='{$user}' onchange='getNewWeights()'");
    
    if(empty($_GET))
    {
        if($days != null && $days < 7)
        {
            echo("checked ");
            array_push($selected, $user);
        }
    }
    else
    {
        if(isset($_GET[$row['name']]))
        {
            echo("checked ");
            array_push($selected, $user);
        }
    }
    echo ("/>
                        <label for='{$short}' class='shrinkable down'>
                            <span class='short'>{$short}</span>
                            <span class='long'>{$user}</span>
                        </label>
                    </span>");
    
    $row = $result->fetch_assoc();
}

                    ?>
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