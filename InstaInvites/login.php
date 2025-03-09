<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "miniproject";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inputUsername = $_POST['username'];
    $inputPassword = $_POST['password'];

    // Validate input
    if (empty($inputUsername) || empty($inputPassword)) {
        echo "Username and password are required.";
    } else {
        // Secure query using prepared statements
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $inputUsername);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verify password (assuming password is hashed in the database)
            if (password_verify($inputPassword, $user['password'])) {
                session_start();
                $_SESSION['username'] = $user['username'];
                echo "Login successful! Welcome, " . $_SESSION['username'];
                // Redirect to the dashboard or another page
                header("Location: dashboard.php");
                exit();
            } else {
                echo "Invalid password.";
            }
        } else {
            echo "User not found.";
        }
        $stmt->close();
    }
}

$conn->close();
?>
