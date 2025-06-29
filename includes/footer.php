<footer style="
    background: #ffffff;
    color: #333;
    padding: 30px 20px;
    font-family: Arial, sans-serif;
    margin-top: 40px;
    border-top: 1px solid #eee;
">
  <style>
    .footer-container {
      display: flex;
      flex-wrap: wrap;
      gap: 30px;
      justify-content: space-between;
      max-width: 1200px;
      margin: auto;
    }
    .footer-column {
      flex: 1 1 250px;
      min-width: 220px;
    }
    .footer-column h3,
    .footer-column h4 {
      margin-bottom: 10px;
      color: #222;
    }
    .footer-column p,
    .footer-column a {
      color: #555;
      line-height: 1.6;
      text-decoration: none;
      font-size: 15px;
    }
    .footer-column ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    .footer-column li {
      margin-bottom: 8px;
    }
    .footer-social {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
    }
    .footer-bottom {
      text-align: center;
      margin-top: 30px;
      color: #888;
      font-size: 14px;
    }

    @media (max-width: 768px) {
      .footer-column {
        flex: 1 1 100%;
        min-width: 100%;
      }
      .footer-social {
        justify-content: flex-start;
      }
    }
  </style>

  <div class="footer-container">

    <!-- About Section -->
    <div class="footer-column">
      <h3>📰 NewsBlog</h3>
      <p>
        Your trusted source for tech, politics, sports, lifestyle, and trending news. Stay informed, stay updated.
      </p>
    </div>

    <!-- Pages -->
    <div class="footer-column">
      <h4>📄 Pages</h4>
      <ul>
        <?php
        $pages = $conn->query("SELECT slug, title FROM pages ORDER BY id ASC LIMIT 5");
        while ($page = $pages->fetch_assoc()) {
          echo "<li><a href='/page.php?slug=" . htmlspecialchars($page['slug']) . "'>• " . htmlspecialchars($page['title']) . "</a></li>";
        }
        ?>
      </ul>
    </div>

    <!-- Categories -->
    <div class="footer-column">
      <h4>📂 Categories</h4>
      <ul>
        <?php
        $cats = $conn->query("SELECT id, name FROM categories ORDER BY name ASC LIMIT 5");
        while ($cat = $cats->fetch_assoc()) {
          echo "<li><a href='/category.php?id=" . $cat['id'] . "'>• " . htmlspecialchars($cat['name']) . "</a></li>";
        }
        ?>
      </ul>
    </div>

    <!-- Social Links -->
    <div class="footer-column">
      <h4>🌐 Follow Us</h4>
      <div class="footer-social">
        <a href="#">Facebook</a>
        <a href="#">Twitter</a>
        <a href="#">Instagram</a>
      </div>
    </div>

  </div>

  <div class="footer-bottom">
    &copy; <?php echo date('Y'); ?> NewsBlog. All rights reserved.
  </div>
</footer>
