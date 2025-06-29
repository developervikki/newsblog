<?php
require_once 'includes/db.php';
require_once 'includes/header.php';
require_once 'includes/functions.php';

$searchTerm = isset($_GET['q']) ? sanitize($_GET['q']) : '';
?>

<main style="max-width: 800px; margin: 30px auto; padding: 20px;">
    <h2>Search Results for "<?php echo htmlspecialchars($searchTerm); ?>"</h2>

    <form method="GET" action="search.php" style="margin-bottom: 20px;">
        <input type="text" name="q" value="<?php echo htmlspecialchars($searchTerm); ?>" placeholder="Search articles..." style="width: 70%; padding: 8px;">
        <button type="submit" style="padding: 8px 15px; background-color: #2c3e50; color: white; border: none;">Search</button>
    </form>

    <?php
    if (!empty($searchTerm)) {
        // Search in title and content
        $likeTerm = "%{$searchTerm}%";
        $stmt = $conn->prepare("SELECT id, title, short_description, created_at FROM posts WHERE title LIKE ? OR content LIKE ? ORDER BY created_at DESC");
        $stmt->bind_param("ss", $likeTerm, $likeTerm);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div style='margin-bottom: 25px; padding: 15px; background: #fff; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);'>";
                echo "<h3><a href='article.php?id=" . $row['id'] . "'>" . htmlspecialchars($row['title']) . "</a></h3>";
                echo "<p>" . excerpt($row['short_description'], 150) . "</p>";
                echo "<small>Published on " . formatDate($row['created_at']) . "</small>";
                echo "</div>";
            }
        } else {
            echo "<p>No results found for this keyword.</p>";
        }
    } else {
        echo "<p>Please enter a keyword to search.</p>";
    }
    ?>
</main>

<?php require_once 'includes/footer.php'; ?>
