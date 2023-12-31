<?php

require("utils.php");

if(!isset($_COOKIE["message-body"]))
{
    redirect("homepage.php");
}

?>
<!DOCTYPE html>
<html lang="en-US">
    <head>
        <title>
            <?php echo($_COOKIE["message-title"]); ?>
        </title>
        <?php require('header_boilerplate.html'); ?>
    </head>
    <body>
        <div class="main">
        
            <img src="fishhat.png" alt="The Fish Hat" class="cap" />
            
            <div class="toplevel flexcolumn <?php echo($_COOKIE["message-class"]); ?>">
                <?php echo($_COOKIE["message-body"]); ?>
            </div>
        </div>
    </body>
</html>

<?php

//the only way to unset cookies is to have them expire in the past
//which is super weird and kinda annoying, but whatever.
setcookie("message-title", null, time()-30);
setcookie("message-body", null, time()-30);
setcookie("message-class", null, time()-30);

?>