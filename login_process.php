<?php
// Start a session to manage user login state
session_start();

// Sample username and password for testing (replace with database later)
$validUsername = "testuser";
$validPassword = "123456";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the submitted username and password
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate credentials
    if ($username === $validUsername && $password === $validPassword) {
        // Successful login
        $_SESSION['username'] = $username;
        header("Location: dashboard.php"); // Redirect to a dashboard or home page
        exit();
    } else {
        // Invalid credentials
        echo "<script>alert('Invalid username or password!'); window.location.href='login.html';</script>";
    }
} else {
    // If accessed without form submission
    header("Location: login.html");
    exit();
}
?>
