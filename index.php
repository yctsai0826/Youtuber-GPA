<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: welcome.php");
    exit;  //記得要跳出來，不然會重複轉址過多次
}
?>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>登入介面</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(to right, #ECE9E6, #F2F2F2);
            margin: 0;
        }

        .login-container {
            text-align: center;
            background: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .login-container h1 {
            margin-bottom: 20px;
        }

        input[type=text],
        input[type=password] {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 15px;
            box-sizing: border-box;
        }

        input[type=submit] {
            width: 100%;
            padding: 15px;
            border-radius: 15px;
            border: none;
            background-color: #245C73;
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
        }

        input[type=submit]:hover {
            background-color: #144D75;
        }

        a {
            text-decoration: none;
            color: #245C73;
            display: block;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h1>Log In</h1>
        <h2>你可以選擇登入或是註冊帳號~</h2>
        <form method="post" action="login.php">
            帳號：
            <input type="text" name="username"><br /><br />
            密碼：
            <input type="password" name="password"><br><br>
            <input type="submit" value="登入" name="submit"><br><br>
            <a href="register.html">還沒有帳號？現在就註冊！</a>
        </form>
    </div>
</body>

</html>