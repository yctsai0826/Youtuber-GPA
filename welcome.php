<?php
session_start();  //很重要，可以用的變數存在session裡
$username=$_SESSION["username"];
echo "<h1>你好 ".$username."</h1>";
echo "<a href='logout.php'>登出</a>";
?>

<h2>更改密碼</h2>
<form action="change.php" method="post">
    新密碼：<input type="password" name="new_password"><br>
    確認新密碼：<input type="password" name="confirm_new_password"><br>
    <input type="submit" value="更改密碼">
</form>
