<?php

//if cookie is set, redirect to homepage
if(isset($_COOKIE["user"]))
{
    $resp_code = 400;
    //400: Bad request
    //if you're logged in, you must be already a user, 
    //and therefore, should not be creating a new account
    //(should it be 403 Forbidden?)
    
    header("Location: homepage.php", true, $resp_code);
    exit();
}

?>
<!DOCTYPE html>
<html lang="en-US">
    <head>
        <title>New Player Account</title>
        
        <?php require('header_boilerplate.html'); ?>
        
    </head>
    <body>
        <div class="main">
            <img src="fishhat.png" alt="The Fish Hat" class="cap" />
            <main class="main"> 
                <form class="toplevel primary"
                      action="createplayer.php"
                      method="POST"
                      autocomplete="off">
                    <h1 class="title">Create new player account?</h1>

                    <div class="flexrow">
                        
                        <div class="lowlevel flexcolumn">
                            <label for="playerName">Name:</label>
                            <input type="text" 
                                   class="action" 
                                   id="playerName" 
                                   name="name" 
                                   required 
                                   <?php 
                                        $name= $_POST['username'];
                                        echo("value=\"{$name}\"");?> 
                                   /> 
                        </div>
                        
                        <div class="lowlevel flexcolumn">
                            <label for="shortname" class="hastip up">Short version: <span class="tip">5 characters or less.<br />Emoji welcome.</span></label>
                            <input type="text" class="action" id="shortname" name="shortname" size="5" maxlength="5" required />
                        </div>
                        <!--TODO: disable if username is 5 or less characters-->
                        
                        <div class="lowlevel flexcolumn">
                            <label for="password">Password: </label>
                            <input type="password" class="action" id="password" name="password" required />
                        </div>
                        
                        <div class="lowlevel flexcolumn">
                            <label for="password2">Confirm password: </label>
                            <input type="password" class="action" id="password2" name="password2" required />
                        </div>
                        
                    </div>
                    
                    <div class="flexrow">
                        <button class="medium action" type="submit">Confirm</button> 
                        <!--TODO: javascript check password match-->
                        
                        <a href="login.html">
                            <button class="medium action" type="button">Cancel</button>
                        </a>
                    </div>
                </form>
            </main>
        </div>
    </body>
</html>