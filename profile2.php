<?php
include 'includes/session_check.php';
require_login();
include 'includes/header.php';
include 'includes/head.php';
include 'db_con.php';


$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM user WHERE User_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);


$res_stmt = $pdo->prepare("SELECT COUNT(*) FROM reservation WHERE User_User_id = ?");
$res_stmt->execute([$user_id]);
$total_reservations = $res_stmt->fetchColumn();

$sales_stmt = $pdo->prepare("SELECT COUNT(*) FROM sales WHERE User_User_id = ?");
$sales_stmt->execute([$user_id]);
$total_purchases = $sales_stmt->fetchColumn();
?>

<main class="main">
  <section id="profile" class="section py-5">
    <div class="container" data-aos="fade-up" data-aos-delay="100">

      <div class="row g-4">
 
        <div class="col-lg-4">
          <div class="card border-0 shadow-sm rounded-4 p-4 text-center" data-aos="fade-right" data-aos-delay="200">
            <img src="assets/img/users/default-profile.webp" 
                 alt="Profile Photo" 
                 class="rounded-circle mb-3 mx-auto" 
                 style="width:150px;height:150px;object-fit:cover;">
            <h3 class="fw-bold mb-1"><?= htmlspecialchars($user['First_name'] . ' ' . $user['Last_name']) ?></h3>
            <p class="text-muted mb-2"><i class="bi bi-person-badge"></i> <?= htmlspecialchars($user['Role']) ?></p>
            <p class="small text-secondary"><i class="bi bi-envelope"></i> <?= htmlspecialchars($user['Email']) ?></p>

            <button class="btn btn-primary rounded-pill mt-2 px-4" data-bs-toggle="modal" data-bs-target="#editProfileModal">
              <i class="bi bi-pencil-square"></i> Edit Profile
            </button>

            <hr class="my-4">

            <div class="d-flex justify-content-around text-center">
              <div>
                <h5 class="fw-bold mb-0"><?= $total_reservations ?></h5>
                <small class="text-muted">Reservations</small>
              </div>
              <div>
                <h5 class="fw-bold mb-0"><?= $total_purchases ?></h5>
                <small class="text-muted">Purchases</small>
              </div>
              <div>
                <h5 class="fw-bold mb-0"><?= date('Y', strtotime($user['Date_created'])) ?></h5>
                <small class="text-muted">Joined</small>
              </div>
            </div>
          </div>
        </div>


        <div class="col-lg-8">
          <div class="card border-0 shadow-sm rounded-4 p-4" data-aos="fade-left" data-aos-delay="200">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <div>
                <h4 class="fw-bold mb-0"><i class="bi bi-person-lines-fill"></i> Profile Details</h4>
                <small class="text-muted">Manage your account information</small>
              </div>
              <a href="contact.php" class="btn btn-outline-primary rounded-pill">
                <i class="bi bi-chat-dots"></i> Contact Support
              </a>
            </div>

            <hr>

            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label text-muted small">First Name</label>
                <div class="fw-semibold"><?= htmlspecialchars($user['First_name']) ?></div>
              </div>
              <div class="col-md-6">
                <label class="form-label text-muted small">Last Name</label>
                <div class="fw-semibold"><?= htmlspecialchars($user['Last_name']) ?></div>
              </div>
              <div class="col-md-6">
                <label class="form-label text-muted small">Username</label>
                <div class="fw-semibold"><?= htmlspecialchars($user['Username']) ?></div>
              </div>
              <div class="col-md-6">
                <label class="form-label text-muted small">Email Address</label>
                <div class="fw-semibold"><?= htmlspecialchars($user['Email']) ?></div>
              </div>
              <div class="col-md-6">
                <label class="form-label text-muted small">Account Type</label>
                <div class="fw-semibold"><?= htmlspecialchars($user['Role']) ?></div>
              </div>
              <div class="col-md-6">
                <label class="form-label text-muted small">Member Since</label>
                <div class="fw-semibold"><?= date('F j, Y', strtotime($user['Date_created'])) ?></div>
              </div>
            </div>

            <hr class="my-4">

            <div class="text-end">
              <button class="btn btn-secondary rounded-pill me-2" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                <i class="bi bi-pencil"></i> Edit Info
              </button>
              <a href="logout.php" class="btn btn-danger rounded-pill">
                <i class="bi bi-box-arrow-right"></i> Logout
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>


<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4">
      <form action="update_profile.php" method="POST">
        <div class="modal-header">
          <h5 class="modal-title fw-bold"><i class="bi bi-pencil"></i> Edit Profile</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">First Name</label>
              <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($user['First_name']) ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Last Name</label>
              <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($user['Last_name']) ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Username</label>
              <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['Username']) ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Email Address</label>
              <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['Email']) ?>" required>
            </div>
            <div class="col-12">
              <label class="form-label">Change Password</label>
              <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary rounded-pill px-4">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
