<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'db.php';
session_start();

$adminUsername = 'JIAN';
$adminPassword = '12345';
$adminEmail = 'jian@example.com';
$adminRole = 'admin';
$adminImage = '';

$sqlCheck = "SELECT * FROM users WHERE username = ?";
$stmtCheck = $conn->prepare($sqlCheck);
$stmtCheck->bind_param("s", $adminUsername);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();

if ($resultCheck->num_rows === 0) {
    $hashedPassword = password_hash($adminPassword, PASSWORD_DEFAULT);
    $sqlInsert = "INSERT INTO users (username, password, email, image_path, role) VALUES (?, ?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("sssss", $adminUsername, $hashedPassword, $adminEmail, $adminImage, $adminRole);
    $stmtInsert->execute();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username_input = trim($_POST['username'] ?? '');
    $password_input = $_POST['password'] ?? '';

    if (empty($username_input) || empty($password_input)) {
        echo "<script>
            alert('❌ Username or password cannot be empty.');
            window.location.href = 'index.html';
        </script>";
        exit();
    }

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username_input);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password_input, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

    
            $redirectPage = ($user['role'] === 'admin') ? 'dashb.php' : 'feed.html';

            echo "<script>
                alert('Login successful! Redirecting...');
                window.location.href = '$redirectPage';
            </script>";
            exit();
        } else {
            echo "<script>
                alert('❌ Invalid Password.');
                window.location.href = 'index.html';
            </script>";
            exit();
        }
    } else {
        echo "<script>
            alert('❌ Username not found.');
            window.location.href = 'index.html';
        </script>";
        exit();
    }
}
?>
