<?php
include 'includes/session_check.php';
require_login();
flash_message();

include 'includes/header.php';
include 'includes/head2.php';
include 'db_con.php';


if (isset($_GET['action']) && isset($_GET['id'])) {
    $reservation_id = $_GET['id'];
    $action = $_GET['action'];

    if ($action === 'approve') {
        $stmt = $pdo->prepare("UPDATE reservation SET Status='Approved' WHERE Reservation_id=?");
        $stmt->execute([$reservation_id]);
        echo "<script>alert('✅ Reservation approved successfully!');window.location.href='reservation2.php';</script>";
        exit;
    } elseif ($action === 'reject') {
        $stmt = $pdo->prepare("UPDATE reservation SET Status='Rejected' WHERE Reservation_id=?");
        $stmt->execute([$reservation_id]);
        echo "<script>alert('❌ Reservation rejected!');window.location.href='reservation2.php';</script>";
        exit;
    }
}


if (isset($_GET['delete'])) {
    $reservation_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM reservation WHERE Reservation_id=?");
    $stmt->execute([$reservation_id]);
    echo "<script>alert('🗑️ Reservation deleted successfully!');window.location.href='reservation2.php';</script>";
    exit;
}


$stmt = $pdo->prepare("
    SELECT r.Reservation_id, r.Reservation_date, r.Status,
           CONCAT(u.First_name, ' ', u.Last_name) AS customer_name,
           CONCAT(m.Brand, ' ', m.Model) AS motorcycle_name,
           m.Motorcycle_id
    FROM reservation r
    JOIN user u ON r.User_User_id = u.User_id
    JOIN motorcycle m ON r.Motorcycle_Motorcycle_id = m.Motorcycle_id
    ORDER BY r.Reservation_date DESC
");
$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="main">

  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Reservation Management</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="dashboard.php">Dashboard</a></li>
          <li class="current">Reservations</li>
        </ol>
      </nav>
    </div>
  </div>

  <section class="section" data-aos="fade-up" data-aos-delay="100">
    <div class="container py-4">

      <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-success"><i class="bi bi-calendar-check"></i> Pending & Active Reservations</h4>
      </div>

      <div class="card shadow-sm border-0 rounded-4 p-3">
        <div class="table-responsive">
          <table id="reservationTable" class="table table-striped align-middle">
            <thead class="table-success">
              <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Motorcycle</th>
                <th>Reservation Date</th>
                <th>Status</th>
                <th class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($reservations as $r): ?>
              <tr>
                <td><?= $r['Reservation_id'] ?></td>
                <td><?= htmlspecialchars($r['customer_name']) ?></td>
                <td>
                  <a href="motorcycle_details2.php?id=<?= $r['Motorcycle_id'] ?>" class="text-decoration-none text-dark">
                    <?= htmlspecialchars($r['motorcycle_name']) ?>
                  </a>
                </td>
                <td><?= htmlspecialchars($r['Reservation_date']) ?></td>
                <td>
                  <?php if ($r['Status'] === 'Approved'): ?>
                    <span class="badge bg-success">Approved</span>
                  <?php elseif ($r['Status'] === 'Rejected'): ?>
                    <span class="badge bg-danger">Rejected</span>
                  <?php else: ?>
                    <span class="badge bg-warning text-dark">Pending</span>
                  <?php endif; ?>
                </td>
                <td class="text-center">
                  <?php if ($r['Status'] === 'Pending'): ?>
                    <a href="?action=approve&id=<?= $r['Reservation_id'] ?>" 
                       class="btn btn-outline-success btn-sm"
                       onclick="return confirm('Approve this reservation?');">
                      <i class="bi bi-check-circle"></i>
                    </a>
                    <a href="?action=reject&id=<?= $r['Reservation_id'] ?>" 
                       class="btn btn-outline-danger btn-sm"
                       onclick="return confirm('Reject this reservation?');">
                      <i class="bi bi-x-circle"></i>
                    </a>
                  <?php else: ?>
                    <button class="btn btn-outline-secondary btn-sm" disabled>
                      <i class="bi bi-dash-circle"></i>
                    </button>
                  <?php endif; ?>
                  <a href="?delete=<?= $r['Reservation_id'] ?>" 
                     class="btn btn-outline-dark btn-sm"
                     onclick="return confirm('Delete this reservation record?');">
                    <i class="bi bi-trash"></i>
                  </a>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include 'includes/footer.php'; ?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    $('#reservationTable').DataTable({
      pageLength: 8,
      order: [[0, "desc"]],
    });
  });
</script>
