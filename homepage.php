<?php

require('utils.php');

if(!isset($_COOKIE['user']))
{
    redirect("login.php");
}

?>
<!DOCTYPE html>
<html lang="en-US">
    <head>
        <title>Hatclub Games Voting Site</title>
        
        <?php require('header_boilerplate.html'); ?>
        <!--TODO: redirect to login if no username cookie-->
        
    </head>
    <body>
        <div class="main">
            <?php 
            
            $db = connectToDB();
            
            ?>
        
            <!-- TODO: show different options if not yet voted -->
            <img src="fishhat.png" alt="The Fish Hat" class="splash">
            <main class="flexcolumn">
                <a href="gamechooser.php">
                    <button type="button" class="big primary">Choose a game!</button>
                </a>
                <a href="votepage.php">
                    <button type="button" class="medium secondary">View/edit votes</button>
                </a>
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