<?php
require('avatarGuess.php');
if(!isset($_GET['email'])) $_GET['email'] = "";
if(!isset($_GET['bestguess'])) $_GET['bestguess'] = 0;
?>
<html>
<head>
</head>
<body>
    <h1>Profile Picture Test</h1>
    <form method="GET">
        <table>
            <tr><td><?=($_GET['email'])?:"enter an email address to find at an avatar"?></td><td><img src="<?=guess_avatar_from_email($_GET['email'], $_GET['bestguess'])?>" width="50" height="50"></td></tr>
            <tr><td>email: <input type="text" name="email" value="<?=$_GET['email']?>"/><br/><label for="bestguess">include guesses?</label><input type="checkbox" name="bestguess" value="1" <?=($_GET['bestguess'])?'checked':''?>></td></tr>
            <tr><td colspan=2><center><input type='submit'/></center></td></tr>
        </table>
    </form>
</body>
</html>