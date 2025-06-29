<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

$success = $error = "";

// Handle Add Page
if (isset($_POST['add_page'])) {
    $title = sanitize($_POST['title']);
    $content = trim($_POST['content']);
    if ($title && $content) {
        $slug = strtolower(str_replace(' ', '-', $title));
        $stmt = $conn->prepare("INSERT INTO pages (title, slug, content) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $slug, $content);
        $stmt->execute() ? $success = "Page added!" : $error = "Error adding page.";
    } else {
        $error = "All fields are required.";
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM pages WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute() ? $success = "Page deleted." : $error = "Error deleting page.";
    header("Location: pages.php");
    exit;
}

// Handle Update
if (isset($_POST['update_page'])) {
    $id = (int)$_POST['page_id'];
    $title = sanitize($_POST['edit_title']);
    $content = trim($_POST['edit_content']);
    $slug = strtolower(str_replace(' ', '-', $title));
    if ($title && $content) {
        $stmt = $conn->prepare("UPDATE pages SET title = ?, slug = ?, content = ? WHERE id = ?");
        $stmt->bind_param("sssi", $title, $slug, $content, $id);
        $stmt->execute() ? $success = "Page updated!" : $error = "Error updating page.";
    } else {
        $error = "All fields are required.";
    }
}

$pages = $conn->query("SELECT * FROM pages ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Pages</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            min-height: 100vh;
        }

        .hamburger {
            display: none;
            font-size: 24px;
            padding: 15px;
            background: #2c3e50;
            color: white;
            border: none;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1001;
            cursor: pointer;
        }

        .sidebar {
            width: 240px;
            background-color: white;
            color: #333;
            padding: 20px;
            border-right: 1px solid #eee;
            transition: transform 0.3s ease;
            height: 100vh;
        }

        .sidebar h2 {
            font-size: 22px;
            margin-bottom: 20px;
        }

        .sidebar a {
            display: block;
            padding: 12px 20px;
            margin-bottom: 10px;
            color: #333;
            text-decoration: none;
            border-radius: 5px;
        }

        .sidebar a:hover {
            background-color: #f0f0f0;
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
            padding: 20px;
            border-radius: 8px;
        }

        input[type="text"], textarea {
            width: 100%;
            padding: 8px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn {
            background-color: #2c3e50;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #1a252f;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .success { color: green; margin-top: 10px; }
        .error { color: red; margin-top: 10px; }

        @media (max-width: 768px) {
            .hamburger {
                display: block;
            }

            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100%;
                transform: translateX(-100%);
                z-index: 1000;
                box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                padding-top: 60px;
            }
        }
    </style>
</head>
<body>

<!-- Hamburger -->
<button class="hamburger" onclick="toggleSidebar()">☰</button>

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

<!-- Main Content -->
<div class="main-content">
    <div class="container">
        <h2>📄 Manage Static Pages</h2>

        <?php if ($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
        <?php if ($success): ?><p class="success"><?= $success ?></p><?php endif; ?>

        <!-- Add Page -->
        <form method="POST">
            <input type="text" name="title" placeholder="Page title" required>
            <textarea name="content" rows="5" placeholder="Page content" required></textarea>
            <script>CKEDITOR.replace('content');</script>
            <button type="submit" name="add_page" class="btn">Add Page</button>
        </form>

        <!-- Page List -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Page Title</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($page = $pages->fetch_assoc()): ?>
                    <tr>
                        <td><?= $page['id']; ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="page_id" value="<?= $page['id']; ?>">
                                <input type="text" name="edit_title" value="<?= htmlspecialchars($page['title']); ?>" required>
                                <textarea name="edit_content" rows="2" required><?= htmlspecialchars($page['content']); ?></textarea>
                                <button type="submit" name="update_page" class="btn">Update</button>
                            </form>
                        </td>
                        <td>
                            <a href="?delete=<?= $page['id']; ?>" class="btn" style="background: crimson;" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function toggleSidebar() {
        document.getElementById("sidebar").classList.toggle("open");
    }
</script>

</body>
</html>
