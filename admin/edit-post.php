<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

$searchTerm = $_GET['search'] ?? '';
$searchSQL = !empty($searchTerm)
    ? "SELECT id, title FROM posts WHERE title LIKE ? ORDER BY created_at DESC"
    : "SELECT id, title FROM posts ORDER BY created_at DESC";

$stmtList = $conn->prepare($searchSQL);
if (!empty($searchTerm)) {
    $like = "%" . $searchTerm . "%";
    $stmtList->bind_param("s", $like);
}
$stmtList->execute();
$postsList = $stmtList->get_result();

$postId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$success = $error = '';
$post = ['title' => '', 'category' => '', 'short_description' => '', 'content' => '', 'image_path' => ''];

if ($postId > 0) {
    $stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    $post = $stmt->get_result()->fetch_assoc();

    if (!$post) {
        $error = "Post not found.";
        $postId = 0;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $postId > 0) {
    $title = sanitize($_POST['title']);
    $category = sanitize($_POST['category']);
    $short_desc = sanitize($_POST['short_desc']);
    $content = $_POST['content'];
    $imagePath = $post['image_path'];

    if (!empty($_FILES['image']['name'])) {
        $imageName = time() . '-' . basename($_FILES['image']['name']);
        $targetDir = "../uploads/";
        $targetFile = $targetDir . $imageName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $imagePath = "uploads/" . $imageName;
        } else {
            $error = "Image upload failed.";
        }
    }

    if (!$error && $title && $category && $short_desc && $content) {
        $stmt = $conn->prepare("UPDATE posts SET title=?, category=?, short_description=?, content=?, image_path=? WHERE id=?");
        $stmt->bind_param("sssssi", $title, $category, $short_desc, $content, $imagePath, $postId);

        if ($stmt->execute()) {
            $success = "Post updated successfully!";
            $post['title'] = $title;
            $post['category'] = $category;
            $post['short_description'] = $short_desc;
            $post['content'] = $content;
            $post['image_path'] = $imagePath;
        } else {
            $error = "Error updating post.";
        }
    } else if (!$error) {
        $error = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Post - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            display: flex;
            font-family: Arial, sans-serif;
            min-height: 100vh;
            background: #f5f5f5;
        }

        .hamburger {
            display: none;
            background: #fff;
            padding: 15px;
            border: none;
            font-size: 24px;
            cursor: pointer;
            width: 100%;
            text-align: left;
            border-bottom: 1px solid #ccc;
        }

        .sidebar {
            width: 240px;
            background-color: #ffffff;
            padding: 20px;
            border-right: 1px solid #eee;
            transition: transform 0.3s ease;
        }

        .sidebar h2 {
            font-size: 22px;
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar a {
            display: block;
            padding: 10px 15px;
            margin-bottom: 10px;
            color: #333;
            text-decoration: none;
            border-radius: 5px;
        }

        .sidebar a:hover {
            background: #f0f0f0;
        }

        .main-content {
            flex-grow: 1;
            padding: 30px;
            width: 100%;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }

        h2 {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            font-size: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn {
            background-color: #0077cc;
            color: white;
            border: none;
            padding: 10px 20px;
            margin-top: 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #005fa3;
        }

        .success { color: green; margin-top: 10px; }
        .error { color: red; margin-top: 10px; }

        .post-list {
            margin-top: 40px;
        }

        .post-item {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #eee;
            padding: 10px 0;
        }

        .search-bar {
            margin-top: 30px;
        }

        img {
            margin-top: 10px;
            max-width: 150px;
            border-radius: 4px;
        }

        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }

            .hamburger {
                display: block;
            }

            .sidebar {
                position: absolute;
                top: 0;
                left: 0;
                height: 100%;
                transform: translateX(-100%);
                z-index: 999;
                background: #fff;
                box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<!-- Hamburger -->
<button class="hamburger" onclick="toggleSidebar()">☰ Menu</button>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <h2>🧾 Admin</h2>
      <a href="dashboard.php">📊 Dashboard</a>
    <a href="create-post.php">📝 Create Post</a>
    <a href="edit-post.php">✏️ Edit Post</a>
    <a href="categories.php">📚 Categories</a>
    <a href="pages.php">📄 Pages</a>
    <a href="manage-comments.php">💬 Comments</a>
    <a href="users.php">👤 Users</a>
    <a href="logout.php">🚪 Logout</a>
</div>

<!-- Main -->
<div class="main-content">
    <div class="container">
        <h2>Edit Post</h2>

        <?php if ($error): ?><p class="error"><?php echo $error; ?></p><?php endif; ?>
        <?php if ($success): ?><p class="success"><?php echo $success; ?></p><?php endif; ?>

        <?php if ($postId > 0): ?>
            <form method="POST" enctype="multipart/form-data">
                <label>Title:</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>

                <label>Category:</label>
                <select name="category" required>
                    <option value="">-- Select Category --</option>
                    <option value="tech" <?php if ($post['category'] === 'tech') echo 'selected'; ?>>Tech</option>
                    <option value="politics" <?php if ($post['category'] === 'politics') echo 'selected'; ?>>Politics</option>
                    <option value="sports" <?php if ($post['category'] === 'sports') echo 'selected'; ?>>Sports</option>
                    <option value="lifestyle" <?php if ($post['category'] === 'lifestyle') echo 'selected'; ?>>Lifestyle</option>
                </select>

                <label>Short Description:</label>
                <textarea name="short_desc" rows="3"><?php echo htmlspecialchars($post['short_description']); ?></textarea>

                <label>Content:</label>
                <textarea name="content" id="content"><?php echo htmlspecialchars($post['content']); ?></textarea>
                <script>CKEDITOR.replace('content');</script>

                <label>Featured Image:</label><br>
                <?php if (!empty($post['image_path'])): ?>
                    <img src="../<?php echo $post['image_path']; ?>" alt="Current Image">
                <?php endif; ?>
                <input type="file" name="image">

                <button type="submit" class="btn">Update Post</button>
            </form>
        <?php endif; ?>

        <!-- Search -->
        <div class="search-bar">
            <form method="GET">
                <input type="text" name="search" placeholder="Search by title..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button type="submit" class="btn">Search</button>
            </form>
        </div>

        <!-- Post List -->
        <div class="post-list">
            <h3>All Posts</h3>
            <?php while ($p = $postsList->fetch_assoc()): ?>
                <div class="post-item">
                    <span><?php echo htmlspecialchars($p['title']); ?></span>
                    <a href="?id=<?php echo $p['id']; ?>" class="btn">Edit</a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
    }
</script>

</body>
</html>
