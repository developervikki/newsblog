<?php
// Sanitize input (e.g., from $_POST or $_GET)
function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Format datetime into a readable format
function formatDate($datetime) {
    return date("d M, Y", strtotime($datetime));
}

// Trim long text to a given length with "..." at the end
function excerpt($text, $maxLength = 150) {
    $text = strip_tags($text);
    if (strlen($text) > $maxLength) {
        return substr($text, 0, $maxLength) . '...';
    }
    return $text;
}

// Redirect to another page
function redirect($url) {
    header("Location: $url");
    exit();
}
