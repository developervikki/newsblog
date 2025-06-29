# NewsBlog

**NewsBlog** is a simple and dynamic PHP-based blogging platform for publishing and managing news articles. It supports article management, image uploads, comment moderation, and basic user authentication for admins.

## 🗂️ Project Structure

newsblog/
│
├── admin/                      # Admin panel for managing content
│   ├── dashboard.php           # Admin dashboard
│   ├── login.php               # Admin login page
│   ├── create-post.php         # Create a new article
│   ├── edit-post.php           # Edit existing articles
│   ├── manage-comments.php     # Manage article comments
│   └── logout.php              # Admin logout script
│
├── includes/                   # Reusable components & DB config
│   ├── db.php                  # Database connection script
│   ├── header.php              # Header section (HTML + nav)
│   ├── footer.php              # Footer section
│   └── functions.php           # Utility functions
│
├── uploads/                    # Uploaded article images
│
├── index.php                   # Homepage showing latest news
├── article.php                 # Full article view page
├── category.php                # Category-wise article listing
├── search.php                  # Search results page
├── subscribe.php               # Newsletter form handling
├── contact.php                 # Contact form page
├── about.php                   # About the blog/site
├── style.css                   # Main CSS file




## ⚙️ Features

- Admin dashboard for post and comment management
- Article creation, editing, and deletion
- Image upload functionality for posts
- Frontend for viewing articles by category and full view
- Search functionality
- Newsletter subscription handler
- Contact and About pages
- Modular code with reusable components (`header`, `footer`, `functions`)

## 🧰 Technologies Used

- PHP (Core PHP, no frameworks)
- MySQL (via `mysqli`)
- HTML5, CSS3
- JavaScript (for UI interactions)
- XAMPP/WAMP for local development

## 🚀 Setup Instructions

1. **Clone or Download the repository**
   ```bash
   git clone https://github.com/developervikki/newsblog.git

Import the database

Open phpMyAdmin.

Create a new database, e.g., newsblog.

Import the .sql file (not provided here).

Configure Database

Edit /includes/db.php with your database credentials:
$conn = new mysqli('localhost', 'root', '', 'newsblog');

Run the Project

Place the project folder in htdocs (XAMPP) or your web root.

Start Apache & MySQL.

Visit http://localhost/newsblog in your browser.

🔐 Admin Login
Make sure you create admin login credentials in the users table manually or through a script. This allows access to /admin/login.php.

📂 Notes
Images must be uploaded to /uploads/ and referenced properly in articles.

Ensure file and folder permissions allow image upload (/uploads/ should be writable).

Extend with pagination, comment system, or user roles if needed.

📧 Contact
For questions or collaboration, reach out at: typhonya@gmail.com


---

Let me know if you also want a sample SQL file or additional sections like license, contributing, or environment variables.



