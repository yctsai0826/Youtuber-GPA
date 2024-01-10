<?php
session_start();  //很重要，可以用的變數存在session裡
$username = $_SESSION["username"];
$user_id = $_SESSION["user_id"];
//error_log(var_dump($_SESSION));
$conn = require_once "config.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>影片列表</title>
    <link rel="icon" type="image/ico" href="loopy.ico">
    <style>
        body {
            /*color <body>*/
            font-family: Georgia, sans-serif;
            background-color: #f0f0f0;
            color: #477238;
        }

        h1 {
            color: #ffffff;
        }

        h2 {
            color: #082A40;
        }


        .header {
            grid-column: 2 / 4;
            grid-row: 1;
            /*z-index: 10;*/
            padding: 30px;
            text-align: center;
            background-image: url('header.gif');
            /* Path to your GIF file */
            color: white;
            font-size: 30px;
        }

        .top-right {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 20px;
            position: absolute;
            top: 0;
            right: 0;
        }

        .top-right a,
        .top-right h1 {
            margin-left: 20px;
            color: #ffffff;
            text-decoration: none;
        }

        .search-video {
            text-align: center;
            background: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: auto;
        }

        .youtube-videos {
            display: grid;
            grid-template-columns: repeat(5, 250px);
            /* Three columns with equal width */
            grid-gap: 15px;
            /* Space between grid items */
            margin: 20px;
            /* Margin around the container */
        }


        .youtube-video {
            background: #fff;
            /* 背景颜色 */
            border: 1px solid #ddd;
            /* 边框 */
            border-radius: 10px;
            /* 边框圆角 */
            overflow: hidden;
            /* 内容溢出隐藏 */
        }

        .thumbnail img {
            width: 100%;
            /* 图片宽度为 100% */
            height: auto;
            /* 高度自动 */
        }

        .video-info {
            padding: 10px;
            /* 内边距 */
        }

        .video-info a {
            text-decoration: none;
            /* 去除链接下划线 */
            color: #333;
            /* 文本颜色 */
            font-weight: bold;
            /* 字体加粗 */
        }

        .video-title {
            padding: 10px;
        }

        .video-title a {
            text-decoration: none;
            /* 去除链接下划线 */
            color: white;
            /* 文本颜色 */
            font-weight: bold;
            /* 字体加粗 */
        }

        .star-btn {
            cursor: pointer;
            color: grey;
            font-size: 24px;
        }

        .star-btn.on {
            color: gold;
        }

        .star-btn:hover,
        .star-btn:active {
            color: rgb(232, 166, 14);
        }

        .right-sidebar {
            position: fixed;
            top: 50%;
            right: 0;
            z-index: 5;
            width: 200px;
            background-color: #082A40;
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #ccc;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
            transform: translateY(-50%);
        }

        .right-sidebar h2 {
            color: #FFF6ED;
        }

        .right-sidebar p {
            color: #FFF6ED;
        }

        .main-content {
            flex-grow: 1;
            background-color: #ababab;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        #comment-form, #comments-display, #pagination {
            font-family: Arial, sans-serif;
            margin-bottom: 20px;
        }

        /* 文本区域和按钮样式 */
        #comment-textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        #comment-form button {
            background-color: #a0ced9;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        #comment-form button:hover {
            background-color: #a3edff;
        }

        /* 评论显示区域的样式 */
        .comment {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 4px;
            background-color: #f9f9f9;
            margin-bottom: 10px;
        }

        .comment p {
            margin: 5px 0;
            color: #082a40;
        }

        .comment strong {
            color: #082a40;
        }

        .comment span {
            font-size: 0.9em;
            color: #666;
        }

        /* 分页导航样式 */
        #pagination a {
            text-decoration: none;
            padding: 5px 10px;
            border: 1px solid #a0ced9;
            border-radius: 4px;
            color: #a0ced9;
        }

        #pagination span {
            padding: 5px 10px;
            border: 1px solid #a0ced9;
            border-radius: 4px;
            background-color: #a0ced9;
            color: white;
        }

        #pagination a:hover {
            background-color: #a3edff;
        }
        /* 响应式设计 */
        @media (max-width: 600px) {
            .youtube-videos {
                grid-template-columns: 1fr;
                /* 小屏幕时只有一列 */
            }
        }
    </style>
</head>

<body>
    <section>
        <!--header-->
        <div class="header">
            <h1>Youtube GPA</h1>
            <h1><br></h1>
            <h1><br></h1>
            <h1><br></h1>
        </div>
        

        <div class="top-right">
            <a href='logout.php'>登出</a>
            <a href='change.php'>更改密碼</a>
        </div>
        <!-- <form method="post" action="logout.php">
        <input type="submit" value="登出">
        </form> -->
        <!-- <a href="change.php">更改密码</a> -->
        <!-- 在这里添加搜索表单 -->
        <div class="search-video">
            <form action="" method="get">
                <input type="text" name="search" placeholder="找影片">
                <select name="region">
                    <option value="UK">英國</option>
                    <option value="US" selected>美國</option>
                    <option value="KR">韓國</option>
                    <option value="JP">日本</option>
                </select>
                <input type="submit" value="搜索">
            </form>
        </div>

        <?php
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $region = isset($_GET['region']) ? $_GET['region'] : 'US'; // 默认为美国
        
        // 根据区域选择相应的表
        $tableName = '';
        switch ($region) {
            case 'UK':
                $tableName = 'BR_gpa';
                break;
            case 'US':
                $tableName = 'US_gpa';
                break;
            case 'KR':
                $tableName = 'KR_gpa';
                break;
            case 'JP':
                $tableName = 'JP_gpa';
                break;
            default:
                $tableName = 'US_gpa'; // 如果没有匹配的区域，默认为 US_gpa
                break;
        }


        $sql = "SELECT a.v_id, a.yv_id, gpa, thumbnail_link, title ,a.syv_id
        FROM (SELECT youtube.video_id as v_id, youtube.youtube_video_id as yv_id, gpa, thumbnail_link, title, star.youtube_video_id as syv_id
        FROM $tableName as youtube
        LEFT JOIN star ON ( user_id = $user_id AND star.youtube_video_id = youtube.youtube_video_id)) as a ";
        if ($search !== '') {
            $sql .= " WHERE title LIKE '%" . $conn->real_escape_string($search) . "%'";
            $sql .= " ORDER BY gpa DESC LIMIT 10;";
        } else {
            $sql .= "ORDER BY gpa DESC LIMIT 10;";
        }

        error_log($sql);
        $result = mysqli_query($conn, $sql);

        // 检查查询结果
        if ($result && $result->num_rows > 0) {
            echo "<div class='youtube-videos'>";
            // 输出每个视频的信息
            while ($row = $result->fetch_assoc()) {
                //error_log(var_dump($row, true));
                $videoUrl = "https://www.youtube.com/watch?v=" . $row['yv_id'];
                echo "<div class='youtube-video'>";
                echo "<div class='thumbnail'>";
                echo "<img src='" . htmlspecialchars($row['thumbnail_link']) . "' alt='Thumbnail'>";
                echo "</div>";
                echo "<div class='video-info'>";
                //star
                echo "<div class='video' data-youtube-video-id='" . $row['yv_id'] . "'>"; // Fixed the syntax here
                echo "<span class='" . (empty($row['syv_id']) ? 'star-btn' : 'star-btn on') . "' 
                    onclick='handleStarClick(this)' 
                    data-starred='" . (empty($row['syv_id']) ? 'false' : 'true') . "'
                    data-youtube-video-id='" . htmlspecialchars($row['yv_id']) . "'>&#9733</span>"; //. class # id
                echo "</div>"; // Closing div for 'video'
                echo "<p><a href='" . htmlspecialchars($videoUrl) . "'>" . htmlspecialchars($row['title']) . "</a></p>";
                echo "<p>GPA  " . htmlspecialchars($row['gpa']) . "</p>";
                echo "</div>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<p>沒有符合條件之影片。</p>";
        }


        ?>

        <?php
        // 假设 'config.php' 包含数据库连接信息
        // $conn = require_once "config.php";

        $commentsPerPage = 5; // 每页显示的评论数
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // 当前页码
        $offset = ($page - 1) * $commentsPerPage; // 计算当前页的第一条评论的索引

        // 查询总评论数
        $totalCommentsQuery = "SELECT COUNT(*) AS total FROM comments";
        $totalResult = $conn->query($totalCommentsQuery);
        $totalRow = $totalResult->fetch_assoc();
        $totalComments = $totalRow['total'];
        $totalPages = ceil($totalComments / $commentsPerPage); // 总页数

        // 查询当前页的评论
        $sql = "SELECT nickname, content, created_at FROM comments ORDER BY created_at DESC LIMIT $offset, $commentsPerPage";
        $result = $conn->query($sql);
        ?>




        <section id="Comment">
            <h2>Comment</h2>
            <article>
                <form id="comment-form">
                    <textarea id="comment-textarea" name="comment" placeholder="Enter comment..."></textarea>
                    <button type="submit">Submit Comment</button>
                </form>
                <script>
                    // This function will be called when the form is submitted
                    function submitComment(event) {
                        event.preventDefault(); // 防止表單的默認提交行為

                        var xhr = new XMLHttpRequest();
                        var formData = new FormData(document.getElementById('comment-form'));
                        xhr.open('POST', 'handle_comment.php', true);

                        xhr.onload = function () {
                            if (xhr.status === 200) {
                                var response = JSON.parse(xhr.responseText);
                                if (response.success) {
                                    var commentsDisplay = document.getElementById('comments-display');

                                    // 假设 'nickname' 和 'comment' 从响应中获取
                                    var nickname = 'test'; // 示例昵称
                                    var comment = response.comment; // 假设评论内容来自 response 对象

                                    // 获取当前时间并格式化为类似于 PHP 的格式
                                    var now = new Date();
                                    var year = now.getFullYear();
                                    var month = ('0' + (now.getMonth() + 1)).slice(-2);
                                    var day = ('0' + now.getDate()).slice(-2);
                                    var hours = ('0' + now.getHours()).slice(-2);
                                    var minutes = ('0' + now.getMinutes()).slice(-2);
                                    var seconds = ('0' + now.getSeconds()).slice(-2);
                                    var timeString = year + '-' + month + '-' + day + ' ' + hours + ':' + minutes + ':' + seconds;

                                    // 创建显示评论和时间的 HTML
                                    var newCommentHTML = "<div class='comment'>" +
                                                        "<p><strong>" + nickname + "</strong> <span>" + timeString + "</span></p>" +
                                                        "<p>" + comment + "</p>" +
                                                        "</div>";

                                    // 将新评论插入到评论显示区域的开始位置
                                    commentsDisplay.insertAdjacentHTML('afterbegin', newCommentHTML);

                                    // 清空文本区域
                                    document.getElementById('comment-textarea').value = '';

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
                <div id="comments-display">
                    <?php
                    if ($result && $result->num_rows > 0) {
                        // 输出评论
                        while ($row = $result->fetch_assoc()) {
                            echo "<div class='comment'>";
                            echo "<p><strong>" . htmlspecialchars($row['nickname']) . "</strong> <span>" . htmlspecialchars($row['created_at']) . "</span></p>";
                            echo "<p>" . htmlspecialchars($row['content']) . "</p>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p>No comments yet.</p>";
                    }
                    ?>
                </div>

                <div id="pagination">
                    <?php
                    for ($i = 1; $i <= $totalPages; $i++) {
                        if ($i == $page) {
                            echo "<span>$i </span>";
                        } else {
                            echo "<a href='?page=$i'>$i</a> ";
                        }
                    }
                    ?>
                </div>
        </section>
        <!-- End of content from website.html -->
        <?php
        $videosPerPage = 4; // 每页显示的影片数
        $videoPage = isset($_GET['videoPage']) ? (int)$_GET['videoPage'] : 1; // 当前视频页码
        $offset = ($videoPage - 1) * $videosPerPage; // 计算当前页的第一部影片的索引

        // 假设 $user_id 包含当前用户的 ID
        $user_id = $_SESSION["user_id"];

        // 查询总影片数
        $totalVideosQuery = "SELECT COUNT(*) AS total FROM star WHERE user_id = ?";
        $totalStmt = $conn->prepare($totalVideosQuery);
        $totalStmt->bind_param("i", $user_id);
        $totalStmt->execute();
        $totalResult = $totalStmt->get_result();
        $totalRow = $totalResult->fetch_assoc();
        $totalVideos = $totalRow['total'];
        $totalPages = ceil($totalVideos / $videosPerPage); // 总页数

        // 查询当前页的影片
        $sql = "SELECT DISTINCT youtube.youtube_video_id, youtube.title, youtube.thumbnail_link 
        FROM total_youtube_videos AS youtube 
        INNER JOIN star ON youtube.youtube_video_id = star.youtube_video_id 
        WHERE star.user_id = ? 
        LIMIT ?, ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $user_id, $offset, $videosPerPage);
        $stmt->execute();
        $result = $stmt->get_result();
        ?>

        <div class="right-sidebar">
            <h2>Playlist</h2>
            <div id="star_video-display">
                <?php
                // 連接到數據庫
                // 確保已經包含了數據庫連接代碼
                // 例如: $conn = new mysqli('主機', '用戶名', '密碼', '數據庫名');
                
                // 檢查連接
                if ($conn->connect_error) {
                    die("連接失敗: " . $conn->connect_error);
                }


                // 檢查是否有結果
                if ($result->num_rows > 0) {
                    // 输出每个影片的信息
                    while ($row = $result->fetch_assoc()) {
                        $videoUrl = "https://www.youtube.com/watch?v=" . $row['youtube_video_id'];
                        echo "<div class='video-title'>";
                        echo "<img src='" . htmlspecialchars($row['thumbnail_link']) . "' alt='Thumbnail'>";
                        // echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                        echo "<p><a href='" . htmlspecialchars($videoUrl) . "'>" . htmlspecialchars($row['title']) . "</a></p>";
                        echo "</div>";
                    }
                } else {
                    echo "<div class = 'playlist'>";
                    echo "<p>沒有找到收藏的影片。</p>";
                    echo "</div>";
                }
                ?>
            </div>

            <div id="pagination">
                <?php
                // 保留原有的 page 参数
                $currentPage = isset($_GET['page']) ? $_GET['page'] : '';
                for ($i = 1; $i <= $totalPages; $i++) {
                    $link = htmlspecialchars("welcome.php?page=$currentPage&videoPage=$i");
                    echo $i == $videoPage ? "<span>$i </span>" : "<a href='$link'>$i</a> ";
                }
                ?>
            </div>
        </div>
    </section>
    <script>
        //starvideo function
        function handleStarClick(starElement) {
            var youtube_video_id = starElement.getAttribute('data-youtube-video-id');
            var isStarred = starElement.getAttribute('data-starred');
            starVideo(youtube_video_id, starElement, isStarred);
        }
        
        // 保留您现有的 starVideo 和 unstarVideo 函数
        function starVideo(youtube_video_id, starElement, isStarred) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "star_video.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                    try {
                        var response = JSON.parse(this.responseText);
                        if (response.success === 'YES') {
                            // 更新星标按钮的样式
                            if (isStarred === 'true') {
                                starElement.classList.remove('on');
                                starElement.setAttribute('data-starred', 'false');
                            } else {
                                starElement.classList.add('on');
                                starElement.setAttribute('data-starred', 'true');
                            }
                            // 在这里添加逻辑来更新页面上的播放列表或其他元素
                        } else if (response.error) {
                            alert("操作失敗: " + response.error);
                        }
                    } catch (e) {
                        console.error("JSON 解析錯誤:", e);
                    }
                } else if (this.readyState === XMLHttpRequest.DONE) {
                    console.error("請求錯誤，狀態碼:", this.status);
                }
            };

            var act = isStarred === 'true' ? "unstar" : "star";
            xhr.send("youtube_video_id=" + encodeURIComponent(youtube_video_id) + "&action=" + act);
        }
    </script>
</body>