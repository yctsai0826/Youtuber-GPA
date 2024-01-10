//add_to_playlist
<?php
$conn = require_once "config.php";

$youtube_video_id = $_POST['youtube_video_id'];
//$username = $_SESSION['userId']; // 假设用户已经登录

// 检查视频是否已经在播放列表中
$check = $conn->prepare("SELECT * FROM playlist WHERE user_id = ? AND youtube_video_id = ?");
$check->bind_param("is", $username, $youtube_video_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows == 0) {
    // 视频还不在播放列表中，执行插入操作
    $stmt = $conn->prepare("INSERT INTO playlist (user_id, youtube_video_id) VALUES (?, ?)");
    $stmt->bind_param("is", $username, $youtube_video_id);
    $stmt->execute();
    $stmt->close();

    // 可以返回一些信息给前端，例如新插入视频的详细信息
    echo json_encode([
        'status' => 'success',
        'videoId' => $video_id
        // 'videoTitle' => ..., // 从数据库获取
        // 'videoThumbnail' => ..., // 从数据库获取
    ]);
} else {
    // 视频已经存在于播放列表
    echo json_encode([
        'status' => 'exists'
    ]);
}

$conn->close();
?>
