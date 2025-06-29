<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>NewsBlog</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="/newsblog/style.css" />
  <style>
    body {
      margin: 0;
      font-family: Arial, 'Times New Roman', Times, serif;
      background-color: #ffffff;
      color: #000000;
    }

    header {
      background-color: #ffffff;
      color: #000000;
      padding: 15px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      border-bottom: 2px solid #ddd;
      position: sticky;
      top: 0;
      z-index: 999;
    }

    .logo a {
      font-size: 26px;
      font-weight: bold;
      font-family: 'Georgia', serif;
      color: #000000;
      text-decoration: none;
    }

    nav {
      display: flex;
    }

    nav ul {
      list-style: none;
      display: flex;
      gap: 20px;
      margin: 0;
      padding: 0;
      flex-wrap: wrap;
    }

    nav ul li a {
      color: #000000;
      text-decoration: none;
      font-size: 16px;
      transition: color 0.3s, border-bottom 0.3s;
      padding: 4px 0;
      border-bottom: 2px solid transparent;
    }

    nav ul li a:hover {
      color: #c0392b;
      border-bottom: 2px solid #c0392b;
    }

    .menu-toggle {
      display: none;
      font-size: 28px;
      cursor: pointer;
      border: 1px solid #ccc;
      padding: 5px 10px;
      border-radius: 4px;
    }

    @media (max-width: 768px) {
      .menu-toggle {
        display: block;
      }

      nav {
        display: none;
        width: 100%;
      }

      nav.active {
        display: block;
        width: 100%;
      }

      nav ul {
        flex-direction: column;
        width: 100%;
        gap: 10px;
        padding: 10px 0;
      }

      nav ul li {
        width: 100%;
        text-align: left;
        padding-left: 20px;
        border-bottom: 1px solid #eee;
      }
    }
  </style>
</head>
<body>

<header>
  <div class="logo">
    <a href="/newsblog/index.php">📰 NewsBlog</a>
  </div>

  <div class="menu-toggle" onclick="toggleMenu()">☰</div>

  <nav id="mainNav">
    <ul>
      <li><a href="./index.php">Home</a></li>
      <li><a href="./category.php">Categories</a></li>
      <li><a href="./about.php">About</a></li>
      <li><a href="./contact.php">Contact</a></li>
      <li><a href="./search.php">Search</a></li>
      <li><a href="./admin/login.php">Login</a></li>
    </ul>
  </nav>
</header>

<script>
  function toggleMenu() {
    document.getElementById("mainNav").classList.toggle("active");
  }
</script>

</body>
</html>
