<?php
session_start();
$conn = require_once "config.php";

$video_id = $_POST['video_id'];
$user_id = $_SESSION['user_id'];
//error_log("user_id=" . $user_id);
//$title = $_POST['title'];
$action = $_POST['action'];

if ($action == 'star') {

    $stmt = $conn->prepare("INSERT INTO star (user_id, video_id) VALUES (?, ?)");

    if ($stmt === false) {
        $response['error'] = "Error preparing statement: " . $conn->error;
    } else {
        // 从 POST 请求获取 title
        $stmt->bind_param("ii", $user_id, $video_id); //(int,int)

        if ($stmt->execute()) {
            $response['success'] = 'YES';
        } else {
            $response['error'] = "Error executing statement: " . $stmt->error;
        }

        $stmt->close();
    }
    
} elseif ($action == 'unstar') {
    $stmt = $conn->prepare("DELETE FROM star WHERE user_id = ? AND video_id = ?");

    if ($stmt === false) {
        $response['error'] = "Error preparing statement: " . $conn->error;
    } else {
        $stmt->bind_param("ii", $user_id, $video_id);

        if ($stmt->execute()) {
            $response['success'] = 'YES';
        } else {
            $response['error'] = 'error' . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
echo json_encode($response);
exit();


?>
