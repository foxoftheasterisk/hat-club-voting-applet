<?php

require(utils.php);

if(isset($_COOKIE['name']))
{
    redirect("homepage.php");
}

?>

<!DOCTYPE html>
<html lang="en-US">
    <head>
        <title>Login to Hatclub</title>
        
        <?php require("header_boilerplate.html"); ?>
        
    </head>
    <body>
        <div class="main">
        
            <img src="fishhat.png" alt="The Fish Hat" class="splash" />
            
            <form class="toplevel primary" 
                  autocomplete="on" 
                  style="maxwidth: 500px;"
                  action="dologin.php"
                  method="POST"
                  >
                <h1 class="title">
                    Log in
                </h1> 
                <label for="username">username: </label> 
                <input type="text" id="username" name="username"/> <br />
                <label for="password">password: </label> 
                <input type="password" id="password" name="password"/> <br />
                <button type="submit" class="action">Log In</button>
            </form>
            
        </div>
    </body>
</html>