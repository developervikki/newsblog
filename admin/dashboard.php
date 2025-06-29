<?php
require_once '../includes/db.php';

$totalPosts = $conn->query("SELECT COUNT(*) AS count FROM posts WHERE is_deleted = 0")->fetch_assoc()['count'];
$totalComments = $conn->query("SELECT COUNT(*) AS count FROM comments WHERE is_approved = 1")->fetch_assoc()['count'];
$totalCategories = $conn->query("SELECT COUNT(*) AS count FROM categories")->fetch_assoc()['count'];
$totalUsers = $conn->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: Arial, sans-serif;
      background: #f9f9f9;
      color: #222;
    }

    header {
      background: #ffffff;
      padding: 15px 20px;
      border-bottom: 1px solid #ddd;
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    .hamburger {
      display: none;
      font-size: 24px;
      background: none;
      border: none;
      cursor: pointer;
    }

    .container {
      display: flex;
      min-height: 100vh;
    }

    .sidebar {
      width: 240px;
      background: #fff;
      border-right: 1px solid #eee;
      padding: 20px;
      transition: transform 0.3s ease;
    }

    .sidebar h2 {
      font-size: 20px;
      margin-bottom: 20px;
      text-align: center;
    }

    .sidebar a {
      display: block;
      padding: 12px;
      color: #333;
      text-decoration: none;
      margin-bottom: 10px;
      border-radius: 5px;
      transition: background 0.2s;
    }

    .sidebar a:hover {
      background: #f2f2f2;
    }

    .main-content {
      flex-grow: 1;
      padding: 30px;
      background: #fff;
    }

    .main-content h2 {
      margin-bottom: 20px;
    }

    .dashboard-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
    }

    .dashboard-card {
      background: #fefefe;
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 20px;
      text-align: center;
      transition: box-shadow 0.3s;
    }

    .dashboard-card:hover {
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .card-title {
      font-size: 16px;
      color: #555;
      margin-bottom: 10px;
    }

    .card-count {
      font-size: 30px;
      font-weight: bold;
      color: #0077cc;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .hamburger {
        display: block;
      }

      .container {
        flex-direction: column;
      }

      .sidebar {
        width: 100%;
        position: absolute;
        top: 60px;
        left: 0;
        transform: translateY(-200%);
        background: #fff;
        border-top: 1px solid #ddd;
        z-index: 999;
      }

      .sidebar.active {
        transform: translateY(0);
      }
    }
  </style>
</head>
<body>

<header>
  <h2>🧾 Admin Panel</h2>
  <button class="hamburger" onclick="document.getElementById('sidebar').classList.toggle('active')">☰</button>
</header>

<div class="container">
  <div class="sidebar" id="sidebar">
    
    <a href="dashboard.php">📊 Dashboard</a>
    <a href="create-post.php">📝 Create Post</a>
    <a href="edit-post.php">✏️ Edit Post</a>
    <a href="categories.php">📚 Categories</a>
    <a href="pages.php">📄 Pages</a>
    <a href="manage-comments.php">💬 Comments</a>
    <a href="users.php">👤 Users</a>
    <a href="logout.php">🚪 Logout</a>
  </div>

  <div class="main-content">
    <h2>Welcome to Admin Dashboard</h2>
    <div class="dashboard-grid">
      <div class="dashboard-card">
        <div class="card-title">Total Posts</div>
        <div class="card-count"><?php echo $totalPosts; ?></div>
      </div>
      <div class="dashboard-card">
        <div class="card-title">Total Comments</div>
        <div class="card-count"><?php echo $totalComments; ?></div>
      </div>
      <div class="dashboard-card">
        <div class="card-title">Categories</div>
        <div class="card-count"><?php echo $totalCategories; ?></div>
      </div>
      <div class="dashboard-card">
        <div class="card-title">Total Users</div>
        <div class="card-count"><?php echo $totalUsers; ?></div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
