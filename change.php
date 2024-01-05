<?php
session_start();
$conn=require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST["new_password"];
    $confirm_new_password = $_POST["confirm_new_password"];

    // 驗證新密碼和確認新密碼是否匹配
    if ($new_password == $confirm_new_password) {
        // 驗證新密碼是否符合安全標準，例如長度
        // 使用 password_hash 加密新密碼
        // $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

        // 更新數據庫中的密碼
        $username=$_SESSION["username"];
        $sql = "UPDATE user SET password = '".$new_password."' WHERE username = '".$username."'";

        if (mysqli_query($conn, $sql)) {
            echo "密碼更改成功。";
            header("Location: welcome.php");
            // 可能需要重定向到某個頁面
        } else {
            echo "error!!!";
            echo "發生錯誤：" . mysqli_error($link);
        }
    } else {
        echo "密碼不匹配。";
    }

    mysqli_close($link);
}
?>
