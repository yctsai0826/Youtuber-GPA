<?php
session_start();  //很重要，可以用的變數存在session裡
$username=$_SESSION["username"];
echo "<h1>你好 ".$username."</h1>";
echo "<a href='logout.php'>登出</a>";
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
        <h3>Welcome to our youtube platform</h3>
        <p>Blog post content...</p>
        <form id="comment-form">
            <textarea id="comment-textarea" name="comment" placeholder="Enter comment..."></textarea>
            <button type="submit">Submit Comment</button>
        </form>
        <div id="comments-display">
            <!-- Comments will be loaded here -->
        </div>
    </article>
</section>
<!-- End of content from website.html -->

<script>
        // This function will be called when the form is submitted
        function submitComment(event) {
            event.preventDefault();  // Prevent normal form submission

            var xhr = new XMLHttpRequest();
            var formData = new FormData(document.getElementById('comment-form'));

            xhr.open('POST', 'handle_comment.php', true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        var commentsDisplay = document.getElementById('comments-display');
                        commentsDisplay.innerHTML += '<div><strong>' +
                            'Guest:</strong> ' + response.comment + '</div>';
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

