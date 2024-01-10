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
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            color: #477238;
        }

        h1 {
            color: #0066cc;
        }


        .header {
            grid-column: 2 / 4;
            grid-row: 1;
            /*z-index: 10;*/
            padding: 20px;
            text-align: center;
            background: #1abc9c;
            color: white;
            font-size: 30px;
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
            background-color: #C4E1DD;
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #ccc;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
            transform: translateY(-50%);
        }

        .main-content {
            flex-grow: 1;
            background-color: #ababab;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
            <h1>Youtuber GPA</h1>
            <p>My supercool header</p>
        </div>

        <?php
        echo "<h1>你好 " . $username . "</h1>";
        echo "<a href='logout.php'>登出</a><br>";
        echo "<a href='change.php'>更改密碼</a><br>";
        ?>

        <!-- <form method="post" action="logout.php">
        <input type="submit" value="登出">
        </form> -->
        <!-- <a href="change.php">更改密码</a> -->
        <!-- 在这里添加搜索表单 -->
        <form action="" method="get">
            <input type="text" name="search" placeholder="搜索视频">
            <select name="region">
                <option value="UK">英国</option>
                <option value="US" selected>美国</option>
                <option value="KR">韩国</option>
                <option value="JP">日本</option>
            </select>
            <input type="submit" value="搜索">
        </form>

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
        echo $sql;
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
                echo "<p>" . htmlspecialchars($row['gpa']) . "</p>";
                echo "</div>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<p>没有找到视频。</p>";
        }


        ?>

        <section id="blog">
            <h2>Blog</h2>
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
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            var response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                var commentsDisplay = document.getElementById('comments-display');
                                // 獲取當前時間並格式化為 ISO 8601 字符串
                                var now = new Date();
                                var year = now.getFullYear();
                                var month = ('0' + (now.getMonth() + 1)).slice(-2);
                                var day = ('0' + now.getDate()).slice(-2);
                                var hours = ('0' + now.getHours()).slice(-2);
                                var minutes = ('0' + now.getMinutes()).slice(-2);
                                var seconds = ('0' + now.getSeconds()).slice(-2);
                                var timeString = year + '-' + month + '-' + day + ' ' + hours + ':' + minutes + ':' + seconds;
                                // 創建顯示留言和時間的HTML
                                var newCommentHTML = '<div><strong>' + 'test' + '</strong> ' + timeString + '<br>' +'<br>' + response.comment + '</div>';
                                // 將新留言插入到留言顯示區域的開始位置
                                commentsDisplay.insertAdjacentHTML('afterbegin', newCommentHTML);
                                document.getElementById('comment-textarea').value = ''; // 清空文本區域
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
                    document.addEventListener('DOMContentLoaded', function() {
                        document.getElementById('comment-form').addEventListener('submit', submitComment);
                    });
                </script>
                <div id="comments-display">
                    <?php
                    // 連接到數據庫
                    // $conn = require_once "config.php";
                    
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
                    }
                    ?>
                </div>
        </section>
        <!-- End of content from website.html -->

        <div class="right-sidebar">
            <h2>Playlist</h2>
            <div id="comments-display">
                <?php
                // 連接到數據庫
                // 確保已經包含了數據庫連接代碼
                // 例如: $conn = new mysqli('主機', '用戶名', '密碼', '數據庫名');
                
                // 檢查連接
                if ($conn->connect_error) {
                    die("連接失敗: " . $conn->connect_error);
                }

                // 假設 $user_id 包含當前用戶的 ID
                $user_id = $_SESSION["user_id"];

                // 準備 SQL 查詢來選擇所有被該用戶標記的影片
                // 注意: 這裡我假設 'youtube_video_id' 是正確的欄位名
                $sql = "SELECT DISTINCT youtube.youtube_video_id, youtube.title, youtube.thumbnail_link 
                        FROM total_youtube_videos AS youtube 
                        INNER JOIN star ON youtube.youtube_video_id = star.youtube_video_id
                        WHERE star.user_id = ?";
                // 預處理和綁定
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $user_id);

                // 執行語句並獲取結果
                $stmt->execute();
                $result = $stmt->get_result();

                // 檢查是否有結果
                if ($result->num_rows > 0) {
                    // 輸出每個影片的信息
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='video'>";
                        echo "<img src='" . htmlspecialchars($row['thumbnail_link']) . "' alt='Thumbnail'>";
                        echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                        echo "</div>";
                    }
                } else {
                    echo "沒有找到收藏的影片。";
                }
                ?>
            </div>
        </div>
    </section>
    <script>//starvideo function
        function handleStarClick(starElement) {
            //var title = starElement.getAttribute('data-title');
            var youtube_video_id = starElement.getAttribute('data-youtube-video-id');
            var isStarred = starElement.getAttribute('data-starred');
            starVideo(youtube_video_id, starElement, isStarred);
        }

        // 保留您现有的 starVideo 和 unstarVideo 函数
        function starVideo(youtube_video_id, starElement, isStarred) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "star_video.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
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
                            alert("操作失败: " + response.error);
                        }
                    } catch (e) {
                        console.error("JSON 解析错误:", e);
                    }
                } else if (this.readyState === XMLHttpRequest.DONE) {
                    console.error("请求错误，状态码:", this.status);
                }
            };

            var act = isStarred === 'true' ? "unstar" : "star";
            xhr.send("youtube_video_id=" + encodeURIComponent(youtube_video_id) + "&action=" + act);
        }
    </script>
</body>
