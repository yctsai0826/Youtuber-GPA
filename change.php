<?php
session_start();
$conn = require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST["new_password"];
    $confirm_new_password = $_POST["confirm_new_password"];

    // 验证新密码和确认新密码是否匹配
    if ($new_password == $confirm_new_password) {
        // 处理密码更改逻辑
        $username = $_SESSION["username"];
        $sql = "UPDATE user SET password = '".$new_password."' WHERE username = '".$username."'";

        if (mysqli_query($conn, $sql)) {
            echo "密码更改成功。";
            header("Location: welcome.php");
        } else {
            echo "error!!!";
            echo "发生错误：" . mysqli_error($conn);
        }
    } else {
        echo "密码不匹配。";
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>更改密码</title>
    <!-- 在这里可以添加其他的头部信息，比如样式表 -->
</head>
<body>
    <form method="post">
        新密码：<input type="password" name="new_password"><br>
        确认新密码：<input type="password" name="confirm_new_password"><br>
        <input type="submit" value="更改密码">
    </form>
    <!-- 在这里可以添加其他 HTML 内容 -->
</body>
</html>
