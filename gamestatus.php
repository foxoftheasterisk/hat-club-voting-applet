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

$user = $db->real_escape_string($_COOKIE['user']);

//redirect to newgamestatus if there are unfilled gamestatuses
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
    //welp this is the only recourse for debug, so: let it be
}

function buildRow($game, $isNominated = false, $user = null)
{
    
    //TODO: change class to issue when issues
    echo("          <tr class='game'>
                        <td data-sortvalue='{$game["name"]}'>
                            <span class='shrinkable right'>
                                <span class='big'>{$game["emoji"]}</span><span class='long'>{$game["name"]}</span>
                            </span>
                        </td>");
    
    $max = $game["max"];
    if($max == 9999)
    {
        $max = "∞";
        //we want to change the display (but NOT the sort value) to ∞ if the number is 9999
    }
    echo("              <td data-sortvalue='{$game["max"]}'>{$game["min"]}-{$max}</td>
                        <td>{$game["genre"]}</td>");
    
    if($game["owned"])
    {
        echo("          <td data-sortvalue='1'>✔️</td>");
    }
    else
    {
        switch($game["ownership"])
        {
            case "all":
                echo("  <td data-sortvalue='0'>❌</td>");
                break;
            case "one":
                echo("  <td data-sortvalue='0.5'>
                            <span class='hastip left'>⭕
                                <span class='tip'>You do not own {$game["name"]}, but only the host needs it.</span>
                            </span>
                        </td>");
                break;
            case "free":
                echo("  <td data-sortvalue='1'>
                            <span class='hastip left'>➖
                                <span class='tip'>{$game["name"]} is a free game.</span>
                            </span>
                        </td>");
                break;
        }
    }
    
    switch ($game["willing"])
    {
        case "good":
            echo("      <td data-sortvalue='1'>✔️</td>");
            break;
        case "tech":
            echo("      <td data-sortvalue='0.5'>
                            <span class='hastip left'>❗
                                <span class='tip'>You have had technical difficulty with {$game["name"]}.</span>
                            </span>
                        </td>");
            break;
        case "veto":
            echo("      <td data-sortvalue='0'>
                            <span class='hastip left'>❌
                                <span class='tip'>You refuse to play {$game["name"]}.</span>
                            </span>
                        </td>");
            break;
    }
    
    echo("              <td>
                            <a href='editstatus.php?{$game["name"]}'>
                                <button type='button' class='medium action'>Edit</button>
                            </a>
                        </td>");
    
    if($isNominated)
    {
        if($game["nominator"] == $user)
        {
            echo("      <td>
                            <span class='hastip left'>
                                <input type='checkbox' form='second' name='second' value='{$game["name"]}' disabled \>
                                <span class='tip'>You cannot second your own nomination!</span>
                            </span>
                        </td>");
        }
        else
        {
            echo("      <td><input type='checkbox' form='second' name='second' value='{$game["name"]}' \>
                        </td>");
        }
    }
    
    echo("          </tr>");
}


?>
<!DOCTYPE html>
<html lang="en-US">
    <head>
        <title>Game roster</title>
        
        <?php require('header_boilerplate.html'); ?>
        
        <script src="sortutil.js" ></script>
        
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
                        <th class="sortable" data-sorttype="numberdesc">
                            <span class="shrinkable down">
                                <span class="short">Pl#</span>
                                <span class="long">Player count</span>
                            </span>
                        </th>
                        <th class="sortable" data-sorttype="text">
                            <span class="shrinkable down">
                                <span class="short">Type</span>
                                <span class="long">Category<wbr />/Genre</span>
                            </span>
                        </th>
                        <th class="sortable" data-sorttype="numberdesc">
                            <span class="shrinkable down">
                                <span class="short">Own?</span>
                                <span class="long">Owned</span>
                            </span>
                        </th>
                        <th class="sortable" data-sorttype="numberdesc">
                            <span class="shrinkable down">
                                <span class="short">Play?</span>
                                <span class="long">Will Play</span>
                            </span>
                        </th>
                        <th>
                        </th>
                    </tr>
                    <?php

//yikes what a monster of a query
$query = "SELECT games.id AS id, games.name AS name, games.emoji AS emoji, games.min_players AS min, games.max_players AS max, games.ownership AS ownership, games.category AS genre, game_status.owned AS owned, game_status.status AS willing
          FROM games JOIN game_status ON games.id = game_status.game_id
          WHERE game_status.player_id ='{$user}' AND games.nominated_by IS NULL";

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

$query = "SELECT games.id AS id, games.name AS name, games.emoji AS emoji, games.min_players AS min, games.max_players AS max, games.ownership AS ownership, games.category AS genre, game_status.owned AS owned, game_status.status AS willing, games.nominated_by AS nominator
          FROM games JOIN game_status ON games.id = game_status.game_id
          WHERE game_status.player_id ='{$user}' AND games.nominated_by IS NOT NULL;";


$result = $db->query($query);

if($result->num_rows > 0)
{
    echo('      <details class="tight flexcolumn">
                    <summary class="title">Show nominated games</summary>
                    <table style="width: 100%;">
                        <tr class="tertiary header">
                            <th class="sortable" data-sorttype="text">Game</th>
                            <th class="sortable" data-sorttype="number">
                                <span class="shrinkable down wide"> <!--we want wide ONLY if there is only one row -->
                                    <span class="short">Pl#</span>
                                    <span class="long">Player count</span>
                                </span>
                            </th>
                            <th class="sortable" data-sorttype="text">
                                <span class="shrinkable down wide">
                                    <span class="short">Type</span>
                                    <span class="long">Category<wbr />/Genre</span>
                                </span>
                            </th>
                            <th class="sortable" data-sorttype="numberdesc">
                                <span class="shrinkable down wide">
                                    <span class="short">Own?</span>
                                    <span class="long">Owned</span>
                                </span>
                            </th>
                            <th class="sortable" data-sorttype="numberdesc">
                                <span class="shrinkable down wide">
                                    <span class="short">Play?</span>
                                    <span class="long">Will Play</span>
                                </span>
                            </th>
                            <th>
                            </th>
                            <th>
                                <span class="shrinkable left">
                                    <span class="short">2</span>
                                    <span class="long">Second</span>
                                </span>
                            </th>
                        </tr>');
    
    
    $game = $result->fetch_assoc();
    while($game != null)
    {
        buildRow($game, true, $user);
        
        $game = $result->fetch_assoc();
    }
    
    
    echo('          </table>
                    <form id="second" 
                          action="second.php" 
                          method="post" 
                          class="primary footer">
                        <button class="big action" type="submit">Second selected</button>
                    </form>
                </details>');
}
                ?> 
            </main>
        </div>
    </body>
</html>