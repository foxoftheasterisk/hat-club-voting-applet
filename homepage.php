<!DOCTYPE html>
<html lang="en-US">
    <head>
        <title>Hatclub Games Voting Site</title>
        
        <!-- <?php require('header_boilerplate.html'); ?> -->
        
        <!-- TODO: remove duplicate boilerplate (& uncomment) -->
        <link rel="icon"
              type="image/x-icon"
              href="fishhat.ico" />
        <link rel="stylesheet" 
              href="hatclubstyle.css" />
        <meta charset="UTF-8" />
        <meta name="robots" 
              content="noindex, nofollow" />
        <meta name="viewport" 
              content="width=device-width, initial-scale=1.0">
        <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
        <link rel="manifest" href="favicon/site.webmanifest">
        <link rel="mask-icon" href="favicon/safari-pinned-tab.svg" color="#008080">
        <meta name="msapplication-TileColor" content="#00aba9">
        <meta name="theme-color" content="#008080">
        <!--End boilerplate-->
        
        <!--TODO: redirect to login if no username cookie-->
        
    </head>
    <body>
        <div class="main">
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