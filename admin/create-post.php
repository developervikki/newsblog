<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

$title = $category = $short_desc = $content = '';
$success = $error = '';

$categoryQuery = $conn->query("SELECT id, name FROM categories ORDER BY name");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title']);
    $category = (int) $_POST['category'];
    $short_desc = sanitize($_POST['short_desc']);
    $content = $_POST['content'];
    $imagePath = '';

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

    if (!$title || !$category || !$short_desc || !$content) {
        $error = "All fields are required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO posts (title, category_id, short_description, content, image_path) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sisss", $title, $category, $short_desc, $content, $imagePath);

        if ($stmt->execute()) {
            $success = "Post created successfully!";
            $title = $category = $short_desc = $content = '';
        } else {
            $error = "Error saving post.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Post - Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            display: flex;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #222;
        }
        .sidebar {
            width: 240px;
            background: #ffffff;
            border-right: 1px solid #eee;
            padding: 20px;
            transition: transform 0.3s ease;
        }
        .sidebar h2 {
            text-align: center;
            font-size: 22px;
            margin-bottom: 20px;
        }
        .sidebar a {
            display: block;
            padding: 10px 15px;
            color: #333;
            text-decoration: none;
            margin-bottom: 8px;
            border-radius: 5px;
            transition: background 0.2s;
        }
        .sidebar a:hover {
            background-color: #f0f0f0;
        }
        .main-content {
            flex-grow: 1;
            padding: 30px;
            overflow-y: auto;
            width: 100%;
        }
        .container {
            max-width: 800px;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            margin: auto;
        }
        h2 {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin: 15px 0 5px;
            font-weight: bold;
        }
        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            font-size: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: vertical;
        }
        input[type="file"] {
            margin-top: 8px;
        }
        .btn {
            background-color: #0077cc;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            margin-top: 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #005fa3;
        }
        .success { color: green; margin-top: 10px; }
        .error { color: red; margin-top: 10px; }

        /* Hamburger menu */
        .hamburger {
            display: none;
            font-size: 24px;
            background: none;
            border: none;
            cursor: pointer;
            margin: 10px;
        }
        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }
            .sidebar {
                position: absolute;
                left: 0;
                top: 0;
                height: 100%;
                transform: translateX(-100%);
                z-index: 1000;
                box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .hamburger {
                display: block;
                padding: 10px 20px;
                background: #fff;
                border-bottom: 1px solid #eee;
                width: 100%;
                text-align: left;
            }
            .main-content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<button class="hamburger" onclick="toggleSidebar()">☰ Menu</button>
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
        <h2>Create New Post</h2>

        <?php if ($error): ?><p class="error"><?php echo $error; ?></p><?php endif; ?>
        <?php if ($success): ?><p class="success"><?php echo $success; ?></p><?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>" required>

            <label for="category">Category:</label>
            <select name="category" required>
                <option value="">-- Select Category --</option>
                <?php while ($row = $categoryQuery->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>" <?php if ($category == $row['id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($row['name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="short_desc">Short Description:</label>
            <textarea name="short_desc" rows="3" required><?php echo htmlspecialchars($short_desc); ?></textarea>

            <label for="content">Content:</label>
            <textarea name="content" id="content" rows="8"><?php echo htmlspecialchars($content); ?></textarea>
            <script>CKEDITOR.replace('content');</script>

            <label for="image">Featured Image:</label>
            <input type="file" name="image" accept="image/*">

            <button type="submit" class="btn">Create Post</button>
        </form>
    </div>
</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById("sidebar");
        sidebar.classList.toggle("open");
    }
</script>

</body>
</html>
