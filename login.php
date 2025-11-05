<?php
session_start();
include 'db_con.php'; 

$error = "";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM user WHERE Username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['Password'])) {
              
                $_SESSION['user_id'] = $user['User_id'];
                $_SESSION['username'] = $user['Username'];
                $_SESSION['role'] = $user['Role'];
                $_SESSION['first_name'] = $user['First_name'];

               
                $_SESSION['success'] = "Welcome, " . htmlspecialchars($user['First_name']) . "!";

                switch (strtolower($user['Role'])) {
                    case 'admin': 
                        header("Location: admin_dashboard.php");
                        exit;
                    case 'staff': 
                        header("Location: staff_dashboard.php");
                        exit;
                    default: 
                        header("Location: index.php");
                        exit;
                }
            } else {
                $error = "Invalid username or password.";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}


include 'includes/header.php';
include 'includes/head.php';
?>



<style>
  :root {
    --icon-left: 16px;
    --input-padding-left: 46px;
    --control-height: 48px;
  }

  body {
    background: linear-gradient(135deg, #f0f2f5 0%, #dfe9f3 100%);
    min-height: 100vh;
    font-family: "Poppins", sans-serif;
  }

  .login-card {
    background: #fff;
    border-radius: 1.5rem;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
    padding: 2.8rem 2.5rem;
    transition: transform 0.3s ease;
  }

  .login-card:hover {
    transform: translateY(-3px);
  }

  .login-title {
    font-size: 2rem;
    font-weight: 700;
    color: #222;
  }

  .login-subtitle {
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
    color: #6c757d;
    font-size: 1.1rem;
    pointer-events: none;
    z-index: 3;
  }

  .input-with-icon input.form-control {
    width: 100%;
    height: var(--control-height);
    padding-left: var(--input-padding-left);
    border-radius: 999px;
    border: 1px solid #d1d5db;
    background: #fff;
    transition: all 0.2s ease;
  }

  .input-with-icon input.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 4px rgba(0,123,255,0.1);
  }

  .form-label {
    font-weight: 600;
    color: #333;
    font-size: 0.95rem;
    margin-bottom: 6px;
  }

  .btn-primary {
    background-color: #007bff;
    border: none;
    font-weight: 600;
    letter-spacing: 0.4px;
    border-radius: 999px;
    height: 50px;
    transition: background 0.3s ease;
  }

  .btn-primary:hover {
    background-color: #0056b3;
  }

  .alert {
    border-radius: 10px;
  }

  .text-primary {
    color: #007bff;
    text-decoration: none;
  }

  .text-primary:hover {
    text-decoration: underline;
  }

  .hero-label i {
    color: #007bff;
  }
</style>


<main class="main">
  <section id="hero" class="hero section">
    <div class="container d-flex justify-content-center align-items-center min-vh-100" data-aos="fade-up" data-aos-delay="100">
      <div class="hero-wrapper w-100" style="max-width: 480px;">
        <div class="login-card text-center" data-aos="zoom-in" data-aos-delay="200">

          <div class="mb-4">
            <span class="hero-label">
              <i class="bi bi-person-circle fs-2"></i>
              <span class="ms-2 fw-semibold">Welcome Back</span>
            </span>
            <h1 class="login-title mt-3">Login to Your Account</h1>
            <p class="login-subtitle">Access your motorcycle dealership system and explore features based on your role.</p>
          </div>

        
          <form method="POST" class="text-start">
            <div class="mb-3">
              <label for="login-username" class="form-label">Username</label>
              <div class="input-with-icon">
                <i class="bi bi-person form-icon"></i>
                <input type="text" id="login-username" name="username" class="form-control" placeholder="Enter your username" required>
              </div>
            </div>

            <div class="mb-3">
              <label for="login-password" class="form-label">Password</label>
              <div class="input-with-icon">
                <i class="bi bi-lock form-icon"></i>
                <input type="password" id="login-password" name="password" class="form-control" placeholder="Enter your password" required>
              </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-3">
              <i class="bi bi-box-arrow-in-right me-2"></i> Login
            </button>
          </form>

       
          <?php if (!empty($error)): ?>
            <div class="alert alert-danger mt-4 text-center"><?= htmlspecialchars($error) ?></div>
          <?php endif; ?>

          <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert alert-success mt-4 text-center"><?= htmlspecialchars($_SESSION['success']) ?></div>
            <?php unset($_SESSION['success']); ?>
          <?php endif; ?>

        
          <div class="text-center mt-4">
            <p>Don’t have an account? <a href="signup.php" class="text-primary fw-semibold">Sign up here</a></p>
          </div>

        </div>
      </div>
    </div>
  </section>
</main>

<?php include 'includes/footer.php'; ?>
