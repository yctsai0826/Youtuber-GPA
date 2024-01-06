<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>视频列表</title>
    <style>
        .youtube-videos {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); /* 创建多列，每列至少 250px 宽，自动填充剩余空间 */
            grid-gap: 15px; /* 网格项之间的间隙 */
            margin: 20px; /* 容器的外边距 */
        }
        .youtube-video {
            background: #fff; /* 背景颜色 */
            border: 1px solid #ddd; /* 边框 */
            border-radius: 5px; /* 边框圆角 */
            overflow: hidden; /* 内容溢出隐藏 */
        }
        .thumbnail img {
            width: 100%; /* 图片宽度为 100% */
            height: auto; /* 高度自动 */
        }
        .video-info {
            padding: 10px; /* 内边距 */
        }
        .video-info a {
            text-decoration: none; /* 去除链接下划线 */
            color: #333; /* 文本颜色 */
            font-weight: bold; /* 字体加粗 */
        }
        /* 响应式设计 */
        @media (max-width: 600px) {
            .youtube-videos {
                grid-template-columns: 1fr; /* 小屏幕时只有一列 */
            }
        }
    </style>
</head>
<body>



<?php
session_start();  //很重要，可以用的變數存在session裡
$username=$_SESSION["username"];

echo "<h1>你好 ".$username."</h1>";
echo "<a href='logout.php'>登出</a>";
?>


<!-- 在这里添加搜索表单 -->
<form action="welcome.php" method="get">
    <input type="text" name="search" placeholder="搜索标题...">
    <input type="submit" value="搜索">
</form>


<?php
$conn = require_once "config.php";

$search = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT distinct video_id, thumbnail_link, title FROM youtube_trending_videos";
if ($search !== '') {
    $sql .= " WHERE title LIKE '%" . $conn->real_escape_string($search) . "%'";
}
$sql .= " LIMIT 5";
// $sql = "SELECT video_id, thumbnail_link, title FROM youtube_trending_videos LIMIT 5";
$result = $conn->query($sql);

// 检查查询结果
if ($result && $result->num_rows > 0) {
    echo "<div class='youtube-videos'>";
    // 输出每个视频的信息
    while ($row = $result->fetch_assoc()) {
        $videoUrl = "https://www.youtube.com/watch?v=" . $row['video_id'];
        echo "<div class='youtube-video'>";
        echo "<div class='thumbnail'>";
        echo "<img src='" . htmlspecialchars($row['thumbnail_link']) . "' alt='Thumbnail'>";
        echo "</div>";
        echo "<div class='video-info'>";
        echo "<p><a href='" . htmlspecialchars($videoUrl) . "'>" . htmlspecialchars($row['title']) . "</a></p>";
        echo "</div>";
        echo "</div>";
    }
    echo "</div>";
} else {
    echo "<p>没有找到视频。</p>";
}

$conn->close();
?>


<h2>更改密碼</h2>
<form action="change.php" method="post">
    新密碼：<input type="password" name="new_password"><br>
    確認新密碼：<input type="password" name="confirm_new_password"><br>
    <input type="submit" value="更改密碼">
</form>

 <section id="blog">
        <h2>Blog</h2>
        <article>
            <h3>Blog Post Title</h3>
            <p>Blog post content...</p>
            <form id="comment-form">
                <textarea id="comment-textarea" name="comment" placeholder="Enter comment..."></textarea>
                <button type="submit">Submit Comment</button>
            </form>
            <div id="comments-display">
                <!-- PHP代碼從這裡開始 -->
                <?php
                // 連接到數據庫
                $conn = require_once "config.php";

                // 檢查連接
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // 從數據庫獲取評論
                $sql = "SELECT nickname, content, created_at FROM comments ORDER BY created_at DESC";
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {
                    // 輸出評論
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='comment'>";
                        echo "<p><strong>" . htmlspecialchars($row['nickname']) . "</strong> <span>" . htmlspecialchars($row['created_at']) . "</span></p>";
                        echo "<p>" . htmlspecialchars($row['content']) . "</p>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>還沒有評論。</p>";
                }

                $conn->close();
                ?>
                <!-- PHP代碼結束 -->
            </div>
    </section>
<!-- End of content from website.html -->

<script>
        // This function will be called when the form is submitted
        function submitComment(event) {
            event.preventDefault();  // Prevent normal form submission

            var xhr = new XMLHttpRequest();
            var formData = new FormData(document.getElementById('comment-form'));
            var username = <?php echo json_encode($username); ?>;
            xhr.open('POST', 'handle_comment.php', true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        var commentsDisplay = document.getElementById('comments-display');
                        commentsDisplay.innerHTML += '<div><strong>' +
                            username + '</strong> ' + response.comment + '</div>';
                        document.getElementById('comment-textarea').value = ''; // Clear the textarea
                    } else {
                        alert('Error: ' + response.error);
                    }
                } else {
                    alert('An error occurred while submitting the comment.');
                }
            };
            xhr.send(formData);
        }

        // Function to attach the event listener to the form
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('comment-form').addEventListener('submit', submitComment);
        });
</script>