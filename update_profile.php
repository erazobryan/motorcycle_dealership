<?php
include 'includes/session_check.php';
require_login();
include 'db_con.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first = trim($_POST['first_name']);
    $last = trim($_POST['last_name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($password)) {
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("UPDATE user SET First_name=?, Last_name=?, Username=?, Email=?, Password=? WHERE User_id=?");
        $stmt->execute([$first, $last, $username, $email, $hashed, $user_id]);
    } else {
        $stmt = $pdo->prepare("UPDATE user SET First_name=?, Last_name=?, Username=?, Email=? WHERE User_id=?");
        $stmt->execute([$first, $last, $username, $email, $user_id]);
    }

    $_SESSION['success_message'] = "Profile updated successfully!";
    header("Location: profile2.php");
    exit;
}
?>
