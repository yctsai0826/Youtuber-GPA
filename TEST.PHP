<?php
// 简单示例，实际应用中应该使用数据库
$comments = [
    "这是第一条留言。",
    "这是第二条留言。",
    "这是第二条留言。",
    "这是第二条留言。",
    "这是第二条留言。",

    "这是第二条留言。", "这是第二条留言。",
    "这是第二条留言。", "这是第二条留言。", "这是第二条留言。", "这是第二条留言。", "这是第二条留言。", "这是第二条留言。", "这是第二条留言。", "这是第二条留言。", "这是第二条留言。", "这是第二条留言。",
    // 更多留言...
];

$perPage = 5; // 每页显示的留言数
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // 当前页码
$totalPages = ceil(count($comments) / $perPage); // 总页数
$start = ($page - 1) * $perPage; // 当前页的起始留言索引
$currentComments = array_slice($comments, $start, $perPage); // 获取当前页的留言
?>

<!DOCTYPE html>
<html>

<head>
    <title>留言板</title>
    <!-- 这里可以添加CSS样式或者链接外部样式表 -->
</head>

<body>

    <div id="comments-section">
        <?php foreach ($currentComments as $comment) : ?>
            <p><?php echo htmlspecialchars($comment); ?></p>
        <?php endforeach; ?>
    </div>

    <div id="pagination">
        <?php if ($page > 1) : ?>
            <a href="?page=<?php echo $page - 1; ?>">上一頁</a>
        <?php endif; ?>
        <span>第 <?php echo $page; ?> 页，共 <?php echo $totalPages; ?> 页</span>
        <?php if ($page < $totalPages) : ?>
            <a href="?page=<?php echo $page + 1; ?>">下一頁</a>
        <?php endif; ?>
    </div>

</body>

</html>