<?php

$VETO_WEIGHT = 0.25;
$TECH_WEIGHT = 0.5;
$UNOWN_WEIGHT = 0.25;
$NOHOST_WEIGHT = 0.25;

$PENALTIES = parse_ini_file("constants.ini", true)["penalties"];

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

$query = "SELECT players.id, players.name, players.short_name, MIN(DATEDIFF(NOW(), game_status.last_voted_for)) AS time_since_vote
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
    $user = str_replace("<", "&lt;", $user);
    $short = str_replace("<", "&lt;", $short);
    
    echo("          <span class='player'>
                        <input type='checkbox' id='player {$row["id"]}' name='player[]' value='{$row["id"]}' onchange='getNewWeights()'");
    
    if(!isset($_GET["player"]))
    {
        if($days != null && $days < 7)
        {
            echo("checked ");
            array_push($selected, $row["id"]);
        }
    }
    else
    {
        if(in_array($row["id"], $_GET["player"]))
        {
            echo("checked ");
            array_push($selected, $row["id"]);
        }
    }
    echo ("/>
                        <label for='player {$row["id"]}' class='shrinkable down'>
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
                        <?php

$playerCount= count($selected);
$playerCSV = implode(", ", $selected);

//these queries keep getting longer and longer XD
//in theory, I should probably make these queries be saved procedures (and then unexpose everything else)
//...but that's a lot of effort in security that SHOULDN'T matter
//(besides, at the very least that would come after confirming the queries are *correct*)
$query = "SELECT games.id AS id, games.name AS name, games.emoji AS emoji, games.ownership AS ownership, SUM(game_status.current_vote) AS base_weight
          FROM games JOIN game_status ON games.id = game_status.game_id
          WHERE games.min_players <= $playerCount AND games.max_players >= $playerCount AND games.nominated_by IS NULL AND game_status.player_id IN ({$playerCSV})
          GROUP BY games.id
          HAVING SUM(game_status.current_vote) > 0;";

$result = $db->query($query);

$game = $result->fetch_assoc();
while ($game != null)
{
    $gameName = str_replace("'", "&apos;", $game["name"]);
    
    //first, issue checking
    $ownClause = "";
    switch($game["ownership"])
    {
        case "all":
            $ownClause = "OR game_status.owned = FALSE";
            break;
        case "one":
            $ownClause = "OR game_status.owned = TRUE";
            //we need to know that someone owns it to prevent an issue
            break;
    }        
    
    $query = "SELECT game_status.status AS willing, game_status.owned AS owned, players.name AS player
              FROM game_status JOIN players ON game_status.player_id = players.id
              WHERE (game_status.status <> 'good' {$ownClause}) AND game_status.game_id='{$game["id"]}' AND game_status.player_id IN ({$playerCSV});";
              
    $issres = $db->query($query);
    
    $weight = $game["base_weight"];
    $issueList = array();
    
    $hasHost = false;  
    //this variable doesn't matter except if the game needs hosting
    //regardless, it needs to be declared out here.
    
    $issue = $issres->fetch_assoc();
    while($issue != null)
    {
        
        switch ($issue["willing"])
        {
            case "veto":
                array_push($issueList, "🚫 <b>{$issue["player"]}</b> does not like to play <b>{$gameName}</b>! 🚫");
                $weight /= $PENALTIES["veto"];
                break;
            case "tech":
                array_push($issueList, "❗ <b>{$issue["player"]}</b> has had technical difficulties with <b>{$gameName}</b>! ❗");
                $weight /= $PENALTIES["tech"];
                break;
        }
        
        
        if($issue["owned"])
        {
            $hasHost = true;
            //checking if we need to track this is probably costlier than just tracking it
        }
        else
        {
            if($game["ownership"] == "all")
            {
                array_push($issueList, "❌ <b>{$issue["player"]}</b> does not own <b>{$gameName}</b>! ❌");
                $weight /= $PENALTIES["unowned"];
            }
        }
        $issue = $issres->fetch_assoc();
        
    }
    
    if($game["ownership"] == "one" && (!$hasHost))
    {
        array_push($issueList, "⭕ No one present can host <b>{$gameName}</b>! ⭕");
        $weight /= $PENALTIES["no_host"];
    }
    
    $weight = floor($weight);
    
    if(($weight <= 0))
    {
        //this game got issue'd out of existence      
        $game = $result->fetch_assoc();
        continue;
    }
    
    //time to print!
    
    if(count($issueList) == 0)
    {
        echo("          <button class='game weight'");
    }
    else
    {
        echo("          <button class='issue weight'");
    }
    
    echo("                      id='{$game["id"]}'
                                name='{$game["emoji"]} {$gameName}' 
                                value='{$weight}'
                                onclick='selectGame(this)'
                                style='order: {$weight}'>
                            {$game["emoji"]} {$weight}");
    
    if(count($issueList) > 0)
    {
        echo("              <p id='issues' style='display: none;'>");
        foreach($issuelist as $issue)
        {
            echo("              <span>{$issue}</span>");
        }
        echo("              </p>");
    }
    
    echo("              </button>");
    
    //after all those table rows, this seems downright simple.
    
    
    
    $game = $result->fetch_assoc();
}

                        ?>
                    </span>
                </details>
                <div class="flexrow">
                    <div id="IssueOutput" 
                         class="toplevel issue flexcolumn" 
                         style="display:none;" >
                        <h3 id="title" class="title"></h3>
                        <p id="issues" class="text"></p>
                        <button id="button" 
                                name="game" 
                                value="none" 
                                class="medium action" 
                                type="submit" 
                                form="playerform"
                                formaction="playgame.php"
                                formmethod="POST" >
                            Played!
                        </button>
                    </div>
                    <!--technically, ids should be unique through the whole document
                        however, htmlcollection's methods are very limited,
                        and name cannot be used on all elements.
                        so we're stuck doing illegal things!-->
                    <div id="Output" 
                         class="toplevel game flexcolumn" 
                         style="display: none;" >
                        <h3 id="title" class="title"></h3>
                        <br />
                        <button id="button" 
                                name="game" 
                                value="none" 
                                class="medium action" 
                                type="submit" 
                                form="playerform"
                                formaction="playgame.php"
                                formmethod="POST" >
                            Played!
                        </button>
                    </div>
                </div>
                <button class="big action" 
                        onclick="chooseGame()"
                        style="display:inline-block">Choose a game!</button>
                <div id="logbox"></div>
            </main>
        </div>
    </body>
</html>