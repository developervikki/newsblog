<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

$success = $error = "";

// Handle Create Category
if (isset($_POST['add_category'])) {
    $name = sanitize($_POST['name']);
    if (!empty($name)) {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute() ? $success = "Category added!" : $error = "Error adding category.";
    } else {
        $error = "Category name cannot be empty.";
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute() ? $success = "Category deleted." : $error = "Error deleting category.";
    header("Location: categories.php");
    exit;
}

// Handle Update
if (isset($_POST['update_category'])) {
    $id = (int)$_POST['category_id'];
    $name = sanitize($_POST['edit_name']);
    if (!empty($name)) {
        $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $id);
        $stmt->execute() ? $success = "Category updated!" : $error = "Error updating category.";
    } else {
        $error = "Category name cannot be empty.";
    }
}

// Fetch All
$categories = $conn->query("SELECT * FROM categories ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Categories</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
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

        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
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

<!-- Hamburger Button -->
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
        <h2>📚 Manage Categories</h2>

        <?php if ($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
        <?php if ($success): ?><p class="success"><?= $success ?></p><?php endif; ?>

        <!-- Add Category -->
        <form method="POST">
            <input type="text" name="name" placeholder="New category name" required>
            <button type="submit" name="add_category" class="btn">Add Category</button>
        </form>

        <!-- List Categories -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($cat = $categories->fetch_assoc()): ?>
                    <tr>
                        <td><?= $cat['id']; ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="category_id" value="<?= $cat['id']; ?>">
                                <input type="text" name="edit_name" value="<?= htmlspecialchars($cat['name']); ?>" required>
                                <button type="submit" name="update_category" class="btn">Update</button>
                            </form>
                        </td>
                        <td>
                            <a href="?delete=<?= $cat['id']; ?>" class="btn" style="background: crimson;" onclick="return confirm('Are you sure?')">Delete</a>
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
