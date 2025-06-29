<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/header.php';

$email = '';
$success = $error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email']);

    if (empty($email)) {
        $error = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        // Check if already subscribed
        $stmt = $conn->prepare("SELECT id FROM subscribers WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "You are already subscribed.";
        } else {
            // Insert new subscriber
            $stmt = $conn->prepare("INSERT INTO subscribers (email) VALUES (?)");
            $stmt->bind_param("s", $email);
            if ($stmt->execute()) {
                $success = "Thank you for subscribing!";
                $email = '';
            } else {
                $error = "Failed to subscribe. Please try again.";
            }
        }
    }
}
?>

<main style="max-width: 500px; margin: 40px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
    <h2>Subscribe to Our Newsletter</h2>

    <?php if ($error): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php elseif ($success): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php endif; ?>

    <form method="POST" action="subscribe.php">
        <label>Email address:</label><br>
        <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required style="width:100%; padding:8px;"><br><br>
        <button type="submit" style="padding:10px 20px; background:#2c3e50; color:white; border:none; border-radius:5px;">Subscribe</button>
    </form>
</main>

<?php require_once 'includes/footer.php'; ?>
