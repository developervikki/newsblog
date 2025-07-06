 <?php 
require_once 'includes/db.php';
require_once 'includes/header.php';
require_once 'includes/functions.php'; 

$category = isset($_GET['category']) ? sanitize($_GET['category']) : '';

if (empty($category)) {
    echo "<main style='padding: 40px; text-align: center;'>⚠️ No category selected.</main>";
    require_once 'includes/footer.php';
    exit;
}

// Fetch posts in that category
$stmt = $conn->prepare("SELECT id, title, short_description, image_path, created_at FROM posts WHERE category = ? ORDER BY created_at DESC");
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();
?>

<style>
.category-posts {
    max-width: 1000px;
    margin: 40px auto;
    padding: 20px;
}
.category-posts h2 {
    font-size: 28px;
    margin-bottom: 30px;
    color: #2c3e50;
}
.article-card {
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 25px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.06);
    display: flex;
    flex-direction: column;
}
.article-card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}
.article-content {
    padding: 20px;
}
.article-content h3 {
    margin: 0 0 10px;
}
.article-content p {
    margin: 0 0 8px;
    color: #555;
}
.article-content small {
    color: #999;
}
.article-content a {
    text-decoration: none;
    color: #2980b9;
    font-weight: bold;
}
@media (max-width: 768px) {
    .category-posts {
        padding: 15px;
    }
}
</style>

<main class="category-posts">
    <h2>📂 Posts in "<?php echo htmlspecialchars(ucwords($category)); ?>"</h2>

    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="article-card">
                <?php
                $img = (!empty($row['image_path']) && file_exists($row['image_path']))
                    ? htmlspecialchars($row['image_path'])
                    : 'uploads/default.jpg';
                ?>
                <img src="<?php echo $img; ?>" alt="Post Image">
                <div class="article-content">
                    <h3><a href="article.php?id=<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['title']); ?></a></h3>
                    <p><?php echo excerpt($row['short_description'], 160); ?></p>
                    <small>🕒 <?php echo formatDate($row['created_at']); ?></small>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No posts found in this category.</p>
    <?php endif; ?>
</main>

<?php require_once 'includes/footer.php'; ?>
