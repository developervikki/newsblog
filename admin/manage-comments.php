<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Delete comment
if (isset($_GET['delete_id'])) {
    $deleteId = (int) $_GET['delete_id'];
    $stmt = $conn->prepare("UPDATE comments SET is_deleted = 1 WHERE id = ?");
    $stmt->bind_param("i", $deleteId);
    $stmt->execute();
    header("Location: manage-comments.php?msg=deleted");
    exit;
}

// Approve comment
if (isset($_GET['approve_id'])) {
    $approveId = (int) $_GET['approve_id'];
    $stmt = $conn->prepare("UPDATE comments SET is_approved = 1 WHERE id = ?");
    $stmt->bind_param("i", $approveId);
    $stmt->execute();
    header("Location: manage-comments.php?msg=approved");
    exit;
}

// Fetch latest comments
$sql = "
    SELECT comments.id, comments.comment, comments.author_name, comments.created_at, comments.is_approved, posts.title 
    FROM comments 
    JOIN posts ON comments.post_id = posts.id 
    WHERE comments.is_deleted = 0 
    ORDER BY comments.created_at DESC
    LIMIT 50
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Comments</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: Arial, sans-serif;
            display: flex;
            min-height: 100vh;
            background: #f5f5f5;
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
            padding: 10px 15px;
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
            padding: 20px;
            width: 100%;
        }

        .table-container {
            overflow-x: auto;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #2c3e50;
            color: white;
        }

        .btn {
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }

        .delete-btn {
            background-color: crimson;
            color: white;
        }

        .approve-btn {
            background-color: green;
            color: white;
            margin-right: 5px;
        }

        .success-msg {
            color: green;
            margin-top: 10px;
        }

        /* Mobile Responsive */
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
                box-shadow: 2px 0 5px rgba(0,0,0,0.1);
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
    <h2>🗨️ Manage Comments</h2>

    <?php if (isset($_GET['msg'])): ?>
        <p class="success-msg">
            <?= $_GET['msg'] === 'deleted' ? "Comment deleted successfully." : ($_GET['msg'] === 'approved' ? "Comment approved successfully." : "") ?>
        </p>
    <?php endif; ?>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Comment</th>
                    <th>Author</th>
                    <th>Post</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): $i = 1; ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($row['comment']) ?></td>
                            <td><?= htmlspecialchars($row['author_name']) ?></td>
                            <td><?= htmlspecialchars($row['title']) ?></td>
                            <td><?= $row['is_approved'] ? '✅ Approved' : '⏳ Pending' ?></td>
                            <td><?= date('d M, Y', strtotime($row['created_at'])) ?></td>
                            <td>
                                <?php if (!$row['is_approved']): ?>
                                    <a href="?approve_id=<?= $row['id'] ?>" class="btn approve-btn" onclick="return confirm('Approve this comment?')">✅ Approve</a>
                                <?php endif; ?>
                                <a href="?delete_id=<?= $row['id'] ?>" class="btn delete-btn" onclick="return confirm('Delete this comment?')">❌ Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7">No comments found.</td></tr>
                <?php endif; ?>
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
