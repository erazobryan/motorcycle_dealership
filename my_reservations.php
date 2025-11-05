<?php
session_start();
require_once 'includes/session_check.php';
require_login();
require_once 'db_con.php';
include 'includes/header.php';
include 'includes/head.php';
flash_message();

$user_id = $_SESSION['user_id'];


$stmt = $pdo->prepare("
  SELECT r.Reservation_id, r.Reservation_date, r.Status,
         CONCAT(m.Brand, ' ', m.Model) AS motorcycle
  FROM reservation r
  JOIN motorcycle m ON r.Motorcycle_Motorcycle_id = m.Motorcycle_id
  WHERE r.User_User_id = ?
  ORDER BY r.Reservation_date DESC
");
$stmt->execute([$user_id]);
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="main">


  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">My Reservations</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="index.php">Home</a></li>
          <li class="current">My Reservations</li>
        </ol>
      </nav>
    </div>
  </div>


  <section id="my-reservations" class="section py-4">
    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="card shadow-sm border-0 rounded-4 p-4">

        <h4 class="fw-bold mb-3 text-success"><i class="bi bi-calendar-check"></i> Reservation History</h4>

        <?php if (empty($reservations)): ?>
          <div class="text-center text-muted p-4">
            <i class="bi bi-info-circle fs-1 mb-2"></i>
            <p>You have no reservations yet. Browse motorcycles and reserve one today!</p>
            <a href="motorcycle.php" class="btn btn-primary mt-2"><i class="bi bi-motorcycle"></i> Browse Motorcycles</a>
          </div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-success">
                <tr>
                  <th>#</th>
                  <th>Motorcycle</th>
                  <th>Date</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($reservations as $r): ?>
                  <tr>
                    <td><?= htmlspecialchars($r['Reservation_id']) ?></td>
                    <td><?= htmlspecialchars($r['motorcycle']) ?></td>
                    <td><?= htmlspecialchars($r['Reservation_date']) ?></td>
                    <td>
                      <span class="badge bg-<?= 
                        $r['Status'] == 'Approved' ? 'success' : 
                        ($r['Status'] == 'Pending' ? 'warning' : 'danger')
                      ?>">
                        <?= htmlspecialchars($r['Status']) ?>
                      </span>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>

      </div>
    </div>
  </section>

</main>

<?php include 'includes/footer.php'; ?>
