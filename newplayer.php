<!DOCTYPE html>
<html lang="en-US">
    <head>
        <title>New Player Account</title>
        
        <?php require('header_boilerplate.html'); ?>
        
    </head>
    <body>
        <div class="main">
            <img src="fishhat.png" alt="The Fish Hat" class="cap" />
            <main class="main"> <!--this seems like it should restrict the width by 80% then 80%,
                                    but it uses the total window width instead of the container width
                                    and i don't know why. (maybe because it's a <main>?) -->
                <form class="toplevel primary"
                      action="createplayer.php"
                      method="POST"
                      autocomplete="off">
                    <h1 class="title">Create new player account?</h1>

                    <div class="flexrow">
                        
                        <div class="lowlevel flexcolumn">
                            <label for="playerName">Name:</label>
                            <input type="text" class="action" id="playerName" name="name" required value="The Thing You Typed" /> <!--TODO: use PHP to make it actually populate -->
                        </div>
                        
                        <div class="lowlevel flexcolumn">
                            <label for="shortname" class="hastip up">Short version: <span class="tip">5 characters or less.<br />Emoji recommended.</span></label>
                            <input type="text" class="action" id="shortname" name="shortname" size="5" maxlength="5" required />
                        </div>
                        
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
                        
                        <a href="login.html">
                            <button class="medium action" type="button">Cancel</button>
                        </a>
                    </div>
                </form>
            </main>
        </div>
    </body>
</html>