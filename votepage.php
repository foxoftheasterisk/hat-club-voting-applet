<?php

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

$user = $db->real_escape_string($_COOKIE['user']);

//redirect to newgamestatus if there are unfilled gamestatuses
//(We can't just use COUNT actually since we need to subtract.
//we could skip EXISTS, but the extra call probably makes it run faster, actually?
//SQL is a language with a lot of back-end optimization.)
$query = "SELECT IF( EXISTS( SELECT id 
                             FROM games 
                             EXCEPT
                             SELECT games.id
                             FROM games JOIN game_status ON games.id = game_status.game_id
                             WHERE game_status.player_id = '{$user}' ) , 1, 0);";

$result = $db->query($query);

if($result->fetch_array()[0] != 0)
{
    redirect("newgamestatus.php");
}

function buildRow($game)
{
    global $db, $PENALTIES;
    $gameid = $game["id"];
    
    //first, run through everyone's game_status to find issues and count up votes
    //(we have to do this first so we can color issue rows)
    $issues = array();
    $issueval = 0;
    $votes = 0;
    $total_votes = 0;
    
    $query = "SELECT game_status.current_vote AS votes, game_status.historical_vote AS hist, game_status.owned AS owned, game_status.status AS willing, players.name AS name
              FROM game_status JOIN players ON game_status.player_id = players.id
              WHERE game_status.game_id = '{$game["id"]}'";
    
    $result = $db->query($query);
    $status = $result->fetch_assoc();
    while($status != null)
    {
        $votes += $status["votes"];
        $total_votes += $status["hist"];
        
        if($status["willing"] != "good")
        {
            array_push($issues, array("name" => $status["name"], "issue" => $status["willing"]));
            switch($status["willing"])
            {
                case "veto":
                    $issueval += $PENALTIES["veto"];
                case "tech":
                    $issueval += $PENALTIES["tech"];
            }
        }
        
        if($game["ownership"] == "all" && !$status["owned"])
        {
            array_push($issues, array("name" => $status["name"], "issue" => "unowned"));
            $issueval += $PENALTIES["unowned"];
        }
        
        $status = $result->fetch_assoc();
    }
    
    //ok. now we can start writing.
    
    if(count($issues) == 0)
    {
        echo("      <tr class='game'>");
    }
    else
    {
        echo("      <tr class='issue'>");
    }
    
    $name = str_replace("'", "&apos;", $game["name"]);
    $lastDisplay;
    $lastValue;
    if($game["last"] == null)
    {
        $lastValue = 0;
        if($game["hist"] == 0)
        {
            $lastDisplay = "Never";
        }
        else
        {
            $lastDisplay = "Unknown";
        }
    }
    else
    {
        $lastValue = intval($game["last"]);
        $lastDisplay = date("m/j/y", $lastValue);
    }
    
    
    
    echo("              <td data-sortvalue='{$name}'>
                            <span class='shrinkable right'>
                                <span class='big'>{$game["emoji"]}</span><span class='long'>{$name}</span>
                            </span>
                        </td>
                        <td>{$votes}</td>
                        <td data-sortvalue='{$lastValue}'>{$lastDisplay}</td>
                        <td>{$game["hist"]}</td>
                        <td>{$total_votes}</td>
                        <td data-sortvalue='{$issueval}'>");
    
    foreach ($issues as $issue)
    {
        echo("              <span class='hastip left'>");
        switch($issue["issue"])
        {
            case "veto":
                echo("          🚫<span class='tip'>{$issue["name"]} does not like to play {$name}.</span>");
                break;
            case "tech":
                echo("          ❗<span class='tip'>{$issue["name"]} has had technical issues with {$name}.</span>");
                break;
            case "unowned":
                echo("          ❌<span class='tip'>{$issue["name"]} does not own {$name}.</span>");
                break;
        }
        echo("              </span>");
    }
    
    //TODO: start vote checkbox checked if voted for this week
    //TODO: disable if vote cannot be changed (& count toward vote limiting)
    echo("              </td>
                        <td><input type='checkbox' form='vote' name='vote[]' value='{$game["id"]}' /></td>
                    </tr>");
}

?>

<!DOCTYPE html>
<html lang="en-US">
    <head>
        <title>VOTE!</title>
        
        <?php require('header_boilerplate.html'); ?>
        
        <script src="sortutil.js" ></script>
        <!--TODO: vote limiting in js-->
        
    </head>
    <body onload="makeSortablesSortable()">
        <div class="main">
            <a href="homepage.php">
                <img src="fishhat.png" alt="The Fish Hat" class="cap" />
            </a>
            <main class="toplevel secondary tight flexcolumn">
                <table>
                    <tr class="primary header">
                        <th class="sortable" data-sorttype="text">Game</th>
                        <th class="sortable" data-sorttype="numberDesc">
                            <span class="shrinkable down">
                                <span class="long">Current Votes</span>
                                <span class="short">Votes</span>
                            </span>
                        </th>
                        <th class="sortable" data-sorttype="numberDesc">
                            <span class="shrinkable down">
                                <span class="long">Last Voted</span>
                                <span class="short">Last</span>
                            </span>
                        </th>
                        <th class="sortable" data-sorttype="numberDesc">
                            <span class="shrinkable down">
                                <span class="long">Your Votes</span>
                                <span class="short">You</span>
                            </span>
                        </th>
                        <th class="sortable" data-sorttype="numberDesc">
                            <span class="shrinkable down">
                                <span class="long">Total Votes</span>
                                <span class="short">All</span>
                            </span>
                        </th>
                        <th class="sortable" data-sorttype="number">
                            <span class="shrinkable down">
                                <span class="long">Issues</span>
                                <span class="short">XX</span>
                            </span>
                        </th>
                        <th>Vote!</th>
                    </tr>
                    <?php

//an (INNER) JOIN should not exclude any games, since we already redirect if any games are missing
$query = "SELECT games.id AS id, games.name AS name, games.emoji AS emoji, game_status.historical_vote AS hist, game_status.last_voted_for AS last, games.ownership AS ownership
          FROM games JOIN game_status ON games.id = game_status.game_id
          WHERE game_status.player_id ='{$user}' AND games.nominated_by IS NULL AND (game_status.status='good' AND NOT (game_status.owned = 0 AND games.ownership = 'all'))";

$result = $db->query($query);

$game = $result->fetch_assoc();
while($game != null)
{
    buildRow($game);
    
    $game = $result->fetch_assoc();
}

                    ?>
                </table>
                <?php

//this should capture all games that were not previously captured
$query = "SELECT games.id AS id, games.name AS name, games.emoji AS emoji, game_status.historical_vote AS hist, game_status.last_voted_for AS last, games.ownership AS ownership
          FROM games JOIN game_status ON games.id = game_status.game_id
          WHERE game_status.player_id ='{$user}' AND games.nominated_by IS NULL AND NOT (game_status.status='good' AND NOT (game_status.owned = 0 AND games.ownership = 'all'))";

$result = $db->query($query);

if($result->num_rows > 0)
{
    echo("      <details class='tight flexcolumn'>
                    <summary class='title'>Show games with personal issues</summary>
                    <table style='width: 100%'>
                        <tr class='tertiary header'>
                            <th class='sortable' data-sorttype='text'>Game</th>
                            <th class='sortable' data-sorttype='numberDesc'>
                                <span class='shrinkable down'>
                                    <span class='long'>Current Votes</span>
                                    <span class='short'>Votes</span>
                                </span>
                            </th>
                            <th class='sortable' data-sorttype='numberDesc'>
                                <span class='shrinkable down'>
                                    <span class='long'>Last Voted</span>
                                    <span class='short'>Last</span>
                                </span>
                            </th>
                            <th class='sortable' data-sorttype='numberDesc'>
                                <span class='shrinkable down'>
                                    <span class='long'>Your Votes</span>
                                    <span class='short'>You</span>
                                </span>
                            </th>
                            <th class='sortable' data-sorttype='numberDesc'>
                                <span class='shrinkable down'>
                                    <span class='long'>Total Votes</span>
                                    <span class='short'>All</span>
                                </span>
                            </th>
                            <th class='sortable' data-sorttype='number'>
                                <span class='shrinkable down'>
                                    <span class='long'>Issues</span>
                                    <span class='short'>XX</span>
                                </span>
                            </th>
                            <th>Vote!</th>
                        </tr>");
    
    $game = $result->fetch_assoc();
    while($game != null)
    {
        buildRow($game);
        
        $game = $result->fetch_assoc();
    }
    
    echo("          </table>
                </details>");
}

                ?>
                <form id="vote" 
                      action="vote.php" 
                      method="POST" 
                      class="primary footer">
                    <button class="big action" type="submit">Vote!</button>
                </form>
            </main>
        </div>
    </body>
</html>