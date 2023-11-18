<?php

require('utils.php');
if(!isset($_COOKIE['user']))
{
    redirect("login.php");
}

$db = connectToDB();

$user = $db->real_escape_string($_COOKIE['user']);

$query = "SELECT MIN(DATEDIFF(NOW(), last_voted_for)) AS days
          FROM game_status
          WHERE player_id='{$user}' AND last_voted_for IS NOT NULL";


$result = $db->query($query);
$row = $result->fetch_assoc();

$needsToVote = false;
if($row["days"] == "")
{
    $needsToVote = true;
}
else if($row["days"] >= 6)
{
    $needsToVote = true;
}

?>
<!DOCTYPE html>
<html lang="en-US">
    <head>
        <title>Hatclub Games Voting Site</title>
        
        <?php require('header_boilerplate.html'); ?>
        
    </head>
    <body>
        <div class="main">
            <?php 
            
            $db = connectToDB();
            
            ?>
        
            <!-- TODO: show different options if not yet voted -->
            <img src="fishhat.png" alt="The Fish Hat" class="splash">
            <main class="flexcolumn">
                <?php

if($needsToVote)
{
    echo("      <a href='votepage.php'>
                    <button type='button' class='big primary'>Vote!</button>
                </a>
                <a href='gamechooser.php'>
                    <button type='button' class='medium secondary'>View game chooser</button>
                </a>");
}
else
{
    echo("      <a href='gamechooser.php'>
                    <button type='button' class='big primary'>Choose a game!</button>
                </a>
                <a href='votepage.php'>
                    <button type='button' class='medium secondary'>View/edit votes</button>
                </a>");
}

                ?>
                <a href="gamestatus.php">
                    <button type="button" class="medium secondary">View/edit game status</button>
                </a>
                <a href="nominate.php">
                    <button type="button" class="medium secondary">Nominate game</button>
                </a>
            </main>
        </div>
    </body>
</html>