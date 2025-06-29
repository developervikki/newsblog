<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

$slug = $_GET['slug'] ?? '';
if (!$slug) {
    echo "<p>Invalid page.</p>";
    require_once 'includes/footer.php';
    exit;
}

$stmt = $conn->prepare("SELECT title, content FROM pages WHERE slug = ?");
$stmt->bind_param("s", $slug);
$stmt->execute();
$result = $stmt->get_result();
$page = $result->fetch_assoc();

if (!$page) {
    echo "<p>Page not found.</p>";
} else {
    echo "<div style='max-width:800px;margin:30px auto;padding:20px;background:#fff;'>";
    echo "<h2>" . htmlspecialchars($page['title']) . "</h2>";
    echo "<div>" . $page['content'] . "</div>"; // content is raw HTML
    echo "</div>";
}

require_once 'includes/footer.php';
?>
