<?php
include '../db_con.php'; // adjust path if needed

header('Content-Type: application/json');

// Allow only POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
  exit;
}

// Sanitize and validate inputs
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

if (!$name || !$email || !$subject || !$message) {
  echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields.']);
  exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  echo json_encode(['status' => 'error', 'message' => 'Invalid email address.']);
  exit;
}

try {
  // ✅ Save message to database
  $stmt = $pdo->prepare("
    INSERT INTO messages (name, email, subject, message, date_sent)
    VALUES (?, ?, ?, ?, NOW())
  ");
  $stmt->execute([$name, $email, $subject, $message]);

  // Return success response
  echo json_encode([
    'status' => 'success',
    'message' => 'Your message has been sent successfully. Our team will reply soon!'
  ]);
} catch (PDOException $e) {
  echo json_encode([
    'status' => 'error',
    'message' => 'Database error: ' . $e->getMessage()
  ]);
  exit;
}

// 🟢 OPTIONAL EMAIL (disabled on localhost to avoid warning)
// If you want to enable it later, configure SMTP and uncomment below:

/*
$to = "youradminemail@example.com";
$email_subject = "New Contact Message: " . htmlspecialchars($subject);
$email_body = "
You have received a new message from your website contact form.

Name: $name
Email: $email
Phone: $phone
Subject: $subject

Message:
$message
";

$headers = "From: $name <$email>\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

@mail($to, $email_subject, $email_body, $headers);
*/
?>
