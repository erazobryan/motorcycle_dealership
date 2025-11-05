<?php
session_start();
require_once 'db_con.php';


$error = "";
$success = "";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $first_name = trim($_POST['first_name']);
  $middle_name = trim($_POST['middle_name']);
  $last_name = trim($_POST['last_name']);
  $username = trim($_POST['username']);
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);
  $confirm_password = trim($_POST['confirm_password']);
  $role = trim($_POST['role']);

  if (
    empty($first_name) || empty($last_name) || empty($username) ||
    empty($email) || empty($password) || empty($confirm_password) || empty($role)
  ) {
    $error = "Please fill in all required fields.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Please enter a valid email address.";
  } elseif ($password !== $confirm_password) {
    $error = "Passwords do not match.";
  } else {
 
    $check = $pdo->prepare("SELECT * FROM user WHERE Username = ? OR Email = ?");
    $check->execute([$username, $email]);

    if ($check->fetch()) {
      $error = "Username or Email already exists.";
    } else {
    
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $pdo->prepare("
        INSERT INTO user (First_name, Middle_name, Last_name, Username, Email, Password, Role, Date_created)
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
      ");

      if ($stmt->execute([$first_name, $middle_name, $last_name, $username, $email, $hashedPassword, $role])) {
        $_SESSION['success'] = "Account created successfully! You can now log in.";
        header("Location: login.php");
        exit;
      } else {
        $error = "Failed to create account. Please try again.";
      }
    }
  }
}


include 'includes/header.php';
include 'includes/head.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign Up | Motorcycle Dealership</title>
  <link href="assets/css/style.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    :root{
      --icon-left: 16px;
      --input-padding-left: 46px;
      --control-height: 48px;
    }

    body {
      background: linear-gradient(135deg, #eaf0fb 0%, #f8fbff 100%);
      font-family: "Poppins", sans-serif;
    }

    .card {
      max-width: 680px;
      margin: 60px auto;
      background: #fff;
      border-radius: 14px;
      padding: 28px;
      box-shadow: 0 10px 30px rgba(16,24,40,0.08);
    }

    .card-header {
      text-align: center;
      margin-bottom: 18px;
    }

    .card-header .title {
      font-size: 1.6rem;
      font-weight: 700;
      margin: 6px 0;
    }

    .card-header .subtitle {
      color: #6b7280;
      font-size: 0.95rem;
    }

    .input-with-icon {
      position: relative;
      width: 100%;
    }

    .input-with-icon .form-icon {
      position: absolute;
      left: var(--icon-left);
      top: 50%;
      transform: translateY(-50%);
      color: #6b7280;
      font-size: 1.08rem;
      pointer-events: none;
      z-index: 3;
    }

    .input-with-icon input.form-control,
    .input-with-icon select.form-control {
      width: 100%;
      height: var(--control-height);
      padding-left: var(--input-padding-left);
      padding-right: 44px;
      border-radius: 999px;
      border: 1px solid #d1d5db;
      background: #fff;
      transition: border-color .15s, box-shadow .15s;
    }

    .input-with-icon select.form-control {
      background-image: linear-gradient(45deg, transparent 50%, #6b7280 50%), linear-gradient(135deg, #6b7280 50%, transparent 50%);
      background-position: calc(100% - 20px) calc(50% - 2px), calc(100% - 14px) calc(50% - 2px);
      background-size: 6px 6px, 6px 6px;
      background-repeat: no-repeat;
      padding-right: 48px;
    }

    .input-with-icon input.form-control:focus,
    .input-with-icon select.form-control:focus {
      outline: none;
      border-color: #3b82f6;
      box-shadow: 0 6px 18px rgba(59,130,246,0.08);
    }

    .input-with-icon .form-action {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: #6b7280;
      cursor: pointer;
      z-index: 4;
      font-size: 1.08rem;
    }

    .form-label {
      display: block;
      margin-bottom: 6px;
      font-weight: 600;
      color: #111827;
      font-size: 0.95rem;
    }

    .row-gap-16 { gap: 16px; display: grid; grid-template-columns: repeat(2, 1fr); }
    @media (max-width: 768px) {
      .row-gap-16 { grid-template-columns: 1fr; }
    }

    .btn-primary {
      width: 100%;
      height: 50px;
      border-radius: 999px;
      background: #2563eb;
      border: none;
      color: #fff;
      font-weight: 700;
      font-size: 1rem;
    }

    .helper {
      font-size: 0.88rem;
      color: #6b7280;
      margin-top: 10px;
      text-align: center;
    }

    .alert {
      margin-top: 14px;
      border-radius: 10px;
    }
  </style>
</head>

<body>
  <?php include 'includes/head.php'; ?>

  <main class="main">
    <section class="section">
      <div class="container">
        <div class="card" data-aos="zoom-in" data-aos-delay="120">
          <div class="card-header">
            <div class="badge" style="color:#2563eb;"><i class="bi bi-person-plus"></i></div>
            <div class="title">Create Your Account</div>
            <div class="subtitle">Sign up to manage motorcycles, purchases, and services</div>
          </div>

      
          <form method="POST" novalidate>
            <div class="row-gap-16">
              <div>
                <label class="form-label" for="first_name">First Name</label>
                <div class="input-with-icon">
                  <i class="bi bi-person form-icon"></i>
                  <input id="first_name" name="first_name" class="form-control" type="text" placeholder="First name" required>
                </div>
              </div>

              <div>
                <label class="form-label" for="middle_name">Middle Name</label>
                <div class="input-with-icon">
                  <i class="bi bi-person form-icon"></i>
                  <input id="middle_name" name="middle_name" class="form-control" type="text" placeholder="Middle name (optional)">
                </div>
              </div>

              <div style="grid-column: 1 / -1;">
                <label class="form-label" for="last_name">Last Name</label>
                <div class="input-with-icon">
                  <i class="bi bi-person form-icon"></i>
                  <input id="last_name" name="last_name" class="form-control" type="text" placeholder="Last name" required>
                </div>
              </div>

              <div>
                <label class="form-label" for="username">Username</label>
                <div class="input-with-icon">
                  <i class="bi bi-person-badge form-icon"></i>
                  <input id="username" name="username" class="form-control" type="text" placeholder="Choose a username" required>
                </div>
              </div>

              <div>
                <label class="form-label" for="email">Email</label>
                <div class="input-with-icon">
                  <i class="bi bi-envelope form-icon"></i>
                  <input id="email" name="email" class="form-control" type="email" placeholder="you@example.com" required>
                </div>
              </div>

              <div>
                <label class="form-label" for="password">Password</label>
                <div class="input-with-icon">
                  <i class="bi bi-lock form-icon"></i>
                  <input id="password" name="password" class="form-control" type="password" placeholder="Create a password" required>
                  <span class="form-action" data-target="password"><i class="bi bi-eye"></i></span>
                </div>
              </div>

              <div>
                <label class="form-label" for="confirm_password">Confirm Password</label>
                <div class="input-with-icon">
                  <i class="bi bi-lock form-icon"></i>
                  <input id="confirm_password" name="confirm_password" class="form-control" type="password" placeholder="Confirm password" required>
                  <span class="form-action" data-target="confirm_password"><i class="bi bi-eye"></i></span>
                </div>
              </div>

              <div style="grid-column: 1 / -1;">
                <label class="form-label" for="role">Select Role</label>
                <div class="input-with-icon">
                  <i class="bi bi-person-gear form-icon"></i>
                  <select id="role" name="role" class="form-control" required>
                    <option value="">-- Choose role --</option>
                    <option value="Customer">Customer</option>
                    <option value="Staff">Staff</option>
                    <option value="Admin">Admin</option>
                  </select>
                </div>
              </div>

              <div style="grid-column: 1 / -1;">
                <button type="submit" class="btn-primary">Create Account</button>
              </div>
            </div>
          </form>

          <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
          <?php endif; ?>
          <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
          <?php endif; ?>

          <div class="helper">
            Already have an account? <a href="login.php">Log in</a>
          </div>
        </div>
      </div>
    </section>
  </main>

  <?php include 'includes/footer.php'; ?>


  <script>
    document.querySelectorAll('.form-action').forEach(el => {
      el.addEventListener('click', () => {
        const targetId = el.getAttribute('data-target');
        const input = document.getElementById(targetId);
        if (!input) return;
        if (input.type === 'password') {
          input.type = 'text';
          el.innerHTML = '<i class="bi bi-eye-slash"></i>';
        } else {
          input.type = 'password';
          el.innerHTML = '<i class="bi bi-eye"></i>';
        }
      });
    });
  </script>
</body>
</html>
