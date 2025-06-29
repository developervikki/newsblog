<?php
require_once 'includes/db.php';
require_once 'includes/header.php';
?>

<style>
* {
    box-sizing: border-box;
}
body {
    margin: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f9f9f9;
    color: #222;
}

/* Wrapper layout */
.wrapper {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    padding: 20px;
    justify-content: center;
}

/* Sidebar container: wraps Pages & Categories */
.sidebar-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
    width: 100%;
}

/* Sidebar box */
.sidebar {
    background-color: #ffffff;
    color: #333;
    padding: 20px;
    border-radius: 8px;
    min-width: 220px;
    max-width: 280px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    height: fit-content;
    flex: 1 1 100%;
}

/* Sidebar headings and links */
.sidebar h3 {
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #eee;
    font-size: 18px;
}
.sidebar a {
    display: block;
    padding: 10px 0;
    color: #0077cc;
    text-decoration: none;
    font-size: 15px;
    border-bottom: 1px dashed #ccc;
    transition: color 0.3s ease;
}
.sidebar a:hover {
    color: #d62828;
}

/* Main Content */
.main-content {
    background-color: #ffffff;
    padding: 30px;
    border-radius: 8px;
    flex: 2;
    min-width: 300px;
    max-width: 1000px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

/* Search Bar */
.search-bar {
    margin-bottom: 20px;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.search-bar input {
    padding: 10px;
    width: 300px;
    max-width: 100%;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 5px;
}
.search-bar button {
    padding: 10px 20px;
    background-color: #0077cc;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s;
}
.search-bar button:hover {
    background-color: #005fa3;
}

/* Articles Grid */
.articles-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

/* Article Card */
.article-card {
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    transition: transform 0.3s ease;
}
.article-card:hover {
    transform: translateY(-5px);
}
.article-card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
}
.article-card .content {
    padding: 15px;
}
.article-card h3 {
    margin: 0 0 10px;
    font-size: 20px;
    color: #333;
}
.article-card a {
    text-decoration: none;
    color:rgb(15, 16, 16);
}
.article-card a:hover {
    color: #d62828;
}
.article-card p {
    font-size: 14px;
    color: #555;
}
.article-card small {
    color: #999;
    font-size: 13px;
}

/* Responsive Tweaks */
@media (max-width: 768px) {
    .wrapper {
        flex-direction: column;
        padding: 15px;
    }

    .main-content {
        padding: 20px;
    }

    .search-bar {
        flex-direction: column;
        align-items: stretch;
    }

    .search-bar input {
        width: 100%;
    }

    /* Mobile: sidebar blocks in 2-columns */
    .sidebar-container .sidebar {
        flex: 1 1 45%;
        min-width: 45%;
    }
}
</style>

<!-- Main Wrapper -->
<div class="wrapper">

    

    <!-- Main Content Area -->
    <div class="main-content">
        <h2>📰 Latest Articles</h2>

        <div class="search-bar">
            <form method="GET">
                <input type="text" name="search" placeholder="Search articles..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit">Search</button>
            </form>
        </div>

        <div class="articles-grid">
            <?php
            $search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
            $sql = "SELECT id, title, short_description, image_path, created_at FROM posts";
            if (!empty($search)) {
                $sql .= " WHERE title LIKE '%$search%' OR short_description LIKE '%$search%'";
            }
            $sql .= " ORDER BY created_at DESC LIMIT 8";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $img = (!empty($row['image_path']) && file_exists($row['image_path']))
                        ? htmlspecialchars($row['image_path'])
                        : 'uploads/default.jpg';

                    echo "<div class='article-card'>";
                    echo "<a href='article.php?id=" . $row['id'] . "'><img src='" . $img . "' alt='Image'></a>";
                    echo "<div class='content'>";
                    echo "<h3><a href='article.php?id=" . $row['id'] . "'>" . htmlspecialchars($row['title']) . "</a></h3>";
                    echo "<p>" . htmlspecialchars($row['short_description']) . "</p>";
                    echo "<small>Published on " . date('d M Y', strtotime($row['created_at'])) . "</small>";
                    echo "</div></div>";
                }
            } else {
                echo "<p>No articles found.</p>";
            }
            ?>
        </div>
    </div>
    <!-- Left Sidebars: Pages + Categories in same row (mobile = 2 blocks) -->
    <div class="sidebar-container">
        <!-- Pages -->
        <div class="sidebar">
            <h3>📄 Pages</h3>
            <?php
            $pages = $conn->query("SELECT slug, title FROM pages ORDER BY id ASC");
            if ($pages && $pages->num_rows > 0) {
                while ($page = $pages->fetch_assoc()) {
                    echo "<a href='page.php?slug=" . htmlspecialchars($page['slug']) . "'>" . htmlspecialchars($page['title']) . "</a>";
                }
            } else {
                echo "<p>No pages found.</p>";
            }
            ?>
        </div>

        <!-- Categories -->
        <div class="sidebar">
            <h3>📂 Categories</h3>
            <?php
            $cats = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");
            if ($cats && $cats->num_rows > 0) {
                while ($cat = $cats->fetch_assoc()) {
                    echo "<a href='category.php?id=" . $cat['id'] . "'>" . htmlspecialchars($cat['name']) . "</a>";
                }
            } else {
                echo "<p>No categories found.</p>";
            }
            ?>
        </div>
    </div>

</div>

<?php require_once 'includes/footer.php'; ?>
