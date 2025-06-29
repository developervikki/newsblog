<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

$postId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($postId <= 0) {
    die("Invalid article.");
}

$post = $conn->query("SELECT * FROM posts WHERE id = $postId")->fetch_assoc();
if (!$post) {
    die("Post not found.");
}

$commentMsg = '';

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_comment'])) {
    $name = trim($_POST['name']);
    $comment = trim($_POST['comment']);

    if ($name && $comment) {
        $stmt = $conn->prepare("INSERT INTO comments (post_id, author_name, comment, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iss", $postId, $name, $comment);
        if ($stmt->execute()) {
            $commentMsg = "✅ Comment added successfully!";
        } else {
            $commentMsg = "❌ Failed to add comment.";
        }
    } else {
        $commentMsg = "⚠️ All fields are required.";
    }
}
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background: #f5f5f5;
        margin: 0;
    }
    main {
        max-width: 900px;
        margin: 20px auto;
        padding: 20px;
        background: white;
        border-radius: 8px;
    }
    section {
        max-width: 900px;
        margin: 20px auto;
        padding: 20px;
        background: white;
        border-radius: 8px;
    }
    form input, form textarea {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        margin-bottom: 15px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }
    form button {
        background-color: #2c3e50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    .comment-box {
        border-bottom: 1px solid #ddd;
        padding-bottom: 10px;
        margin-bottom: 15px;
    }
</style>

<main>
    <h2><?php echo htmlspecialchars($post['title']); ?></h2>
    <p><em>Published on <?php echo date('d M Y', strtotime($post['created_at'])); ?></em></p>

    <?php if (!empty($post['image_path'])): ?>
        <img src="<?php echo htmlspecialchars($post['image_path']); ?>" style="max-width: 100%; margin: 15px 0;" alt="Article Image">
    <?php endif; ?>

    <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
</main>
<section>
    <h3>🗣️ Recent Comments</h3>
    <?php
    $comments = $conn->query("SELECT author_name, comment, created_at FROM comments WHERE post_id = $postId ORDER BY created_at DESC LIMIT 5");
    if ($comments && $comments->num_rows > 0):
        while ($c = $comments->fetch_assoc()):
    ?>
        <div class="comment-box">
            <strong><?php echo htmlspecialchars($c['author_name']); ?></strong>
            <small> (<?php echo date('d M Y', strtotime($c['created_at'])); ?>)</small>
            <p><?php echo htmlspecialchars($c['comment']); ?></p>
        </div>
    <?php endwhile;
    else: ?>
        <p>No comments yet.</p>
    <?php endif; ?>
</section>

<section>
    <h3>💬 Leave a Comment</h3>
    <?php if ($commentMsg): ?>
        <p><?php echo $commentMsg; ?></p>
    <?php endif; ?>
    <form method="POST">
        <label>Your Name:</label>
        <input type="text" name="name" required>

        <label>Your Comment:</label>
        <textarea name="comment" rows="4" required></textarea>

        <button type="submit" name="submit_comment">Submit Comment</button>
    </form>
</section>



<section>
    <h3>📰 Latest Posts</h3>
    <ul style="list-style: none; padding: 0;">
        <?php
        $latestPosts = $conn->query("SELECT id, title FROM posts ORDER BY created_at DESC LIMIT 5");
        while ($lp = $latestPosts->fetch_assoc()):
        ?>
            <li style="margin-bottom: 8px;">
                <a href="article.php?id=<?php echo $lp['id']; ?>" style="text-decoration: none; color: #2c3e50;">
                    ➤ <?php echo htmlspecialchars($lp['title']); ?>
                </a>
            </li>
        <?php endwhile; ?>
    </ul>
</section>

<?php require_once 'includes/footer.php'; ?>
