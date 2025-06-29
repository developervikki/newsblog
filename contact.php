<?php
require_once 'includes/header.php';
require_once 'includes/functions.php';

// Handle form submission
$name = $email = $message = '';
$success = $error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = sanitize($_POST["name"]);
    $email = sanitize($_POST["email"]);
    $message = sanitize($_POST["message"]);

    if (empty($name) || empty($email) || empty($message)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        // You can save to DB or send email here
        // mail('your@email.com', "Contact from $name", $message);
        $success = "Thank you! Your message has been received.";
        $name = $email = $message = ''; // Clear form
    }
}
?>

<main style="max-width: 600px; margin: 30px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
    <h2>Contact Us</h2>

    <?php if ($error): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php endif; ?>

    <form method="POST" action="contact.php">
        <label>Name:</label><br>
        <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" style="width:100%; padding:8px;" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" style="width:100%; padding:8px;" required><br><br>

        <label>Message:</label><br>
        <textarea name="message" rows="5" style="width:100%; padding:8px;" required><?php echo htmlspecialchars($message); ?></textarea><br><br>

        <button type="submit" style="padding:10px 20px; background:#2c3e50; color:white; border:none; border-radius:5px;">Send Message</button>
    </form>
</main>

<?php require_once 'includes/footer.php'; ?>
