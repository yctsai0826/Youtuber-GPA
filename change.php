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
        $sql = "UPDATE user SET password = '" . $new_password . "' WHERE username = '" . $username . "'";

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
    <title>更改密碼</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #f2f2f2;
            margin: 0;
        }

        .form-container {
            padding: 40px;
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        input[type=password],
        input[type=submit] {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 15px;
            box-sizing: border-box;
        }

        input[type=submit] {
            background-color: #245C73;
            color: white;
            cursor: pointer;
            border: none;
            border-radius: 15px;
        }

        input[type=submit]:hover {
            background-color: #144D75;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <form method="post">
            <div>
                <label for="new_password">新密碼：</label>
                <input type="password" id="new_password" name="new_password"><br>
            </div>
            <div>
                <label for="confirm_new_password">確認新密碼：</label>
                <input type="password" id="confirm_new_password" name="confirm_new_password"><br>
            </div>
            <div>
                <input type="submit" value="更改密碼">
            </div>
        </form>
    </div>
</body>

</html>