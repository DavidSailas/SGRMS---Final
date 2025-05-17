<?php
session_start();
include '../Database/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']); 

    // Prepare the query to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    if (!$stmt) {
        $_SESSION['error_username'] = "Database error: " . $conn->error;
        header("Location: ../Login/index.php");
        exit();
    }
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // If the user is found
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Compare the hashed password
        if (password_verify($password, $user['password'])) { 
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on user role
            switch ($user['role']) {
                case 'Head Guidance':
                    header("Location: #"); 
                    break;
                case 'Guidance Counselor':
                    header("Location: #"); 
                    break;
                case 'Teacher':
                    header("Location: #"); 
                    break;
                default:
                    header("Location: ../Login/index.php");
            }
            exit();
        } else {
            // Wrong password: set error for password field and preserve the username
            $_SESSION['error_password'] = "Wrong password!";
            $_SESSION['old_username'] = $username;
            header("Location: ../Login/index.php");
            exit();
        }
    } else {
        // User not found: set error for username field
        $_SESSION['error_username'] = "User  not found!";
        header("Location: ../Login/index.php");
        exit();
    }
}
?>