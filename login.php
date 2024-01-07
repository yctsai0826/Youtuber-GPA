<?php
// Include config file
$conn = require_once "config.php";

// Define variables and initialize with empty values
$username = $_POST["username"];
$password = $_POST["password"];
//增加hash可以提高安全性
$password_hash = password_hash($password, PASSWORD_DEFAULT);
// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "SELECT * FROM user WHERE username = '" . $username . "'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    // $correct_password = $row["password"]; // 從數據庫中獲取的哈希密碼
    if (mysqli_num_rows($result) == 1 && $password == $row["password"]) {
        echo "登入條件符合，即將重定向到 welcome.php";
        session_start();
        // Store data in session variables
        $_SESSION["loggedin"] = true;
        //這些是之後可以用到的變數
        $_SESSION["user_id"] = $row["user_id"];
        //error_log("user_id=" . $_SESSION["user_id"] . "," . $row["user_id"]);
        // $_SESSION["id"] = mysqli_fetch_assoc($result)["id"];
        $_SESSION["username"] = $username;
        // $_SESSION["username"] = mysqli_fetch_assoc($result)["username"];
        header("Location: welcome.php");
    } else {
        function_alert("帳號或密碼錯誤");
    }
} else {
    function_alert("Something wrong");
}

// Close connection
mysqli_close($link);

function function_alert($message)
{

    // Display the alert box  
    echo "<script>alert('$message');
     window.location.href='index.php';
    </script>";
    return false;
}
?>
?>