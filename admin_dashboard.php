<?php
include 'includes/session_check.php';
require_login();
flash_message();

include 'includes/header.php';
include 'includes/head2.php';
include 'db_con.php';


$total_motorcycles = $pdo->query("SELECT COUNT(*) FROM motorcycle")->fetchColumn();
$total_sales = $pdo->query("SELECT SUM(Total_amount) FROM sales")->fetchColumn() ?? 0;
$total_customers = $pdo->query("SELECT COUNT(*) FROM user WHERE Role='Customer'")->fetchColumn();
$total_reservations = $pdo->query("SELECT COUNT(*) FROM reservation")->fetchColumn();


$status_stmt = $pdo->query("SELECT Status, COUNT(*) AS total FROM motorcycle GROUP BY Status");
$status_data = [];
while ($row = $status_stmt->fetch()) $status_data[$row['Status']] = $row['total'];
$available = $status_data['Available'] ?? 0;
$reserved = $status_data['Reserved'] ?? 0;
$sold = $status_data['Sold'] ?? 0;

$sales_stmt = $pdo->query("
  SELECT DATE_FORMAT(Sales_date, '%b') AS month, SUM(Total_amount) AS total
  FROM sales GROUP BY MONTH(Sales_date)
");
$months = [];
$amounts = [];
while ($row = $sales_stmt->fetch()) {
  $months[] = $row['month'];
  $amounts[] = $row['total'];
}
?>

<main class="main">

  <section class="section dashboard py-4">
    <div class="container" data-aos="fade-up" data-aos-delay="100">

      
      <div class="section-title mb-4">
        <h2 class="fw-bold text-success">Dashboard Overview</h2>
      </div>

     
      <div class="row g-4 mb-4">

        <div class="col-md-3 col-sm-6">
          <div class="card shadow-sm p-4 text-center border-0 rounded-4">
            <i class="bi bi-motorcycle fs-2 text-success"></i>
            <h5 class="fw-bold mt-2"><?= $total_motorcycles ?></h5>
            <p class="text-muted mb-0">Motorcycles Available</p>
          </div>
        </div>

        <div class="col-md-3 col-sm-6">
          <div class="card shadow-sm p-4 text-center border-0 rounded-4">
            <i class="bi bi-cash-coin fs-2 text-primary"></i>
            <h5 class="fw-bold mt-2">₱<?= number_format($total_sales, 2) ?></h5>
            <p class="text-muted mb-0">Total Sales</p>
          </div>
        </div>

        <div class="col-md-3 col-sm-6">
          <div class="card shadow-sm p-4 text-center border-0 rounded-4">
            <i class="bi bi-people fs-2 text-info"></i>
            <h5 class="fw-bold mt-2"><?= $total_customers ?></h5>
            <p class="text-muted mb-0">Registered Customers</p>
          </div>
        </div>

        <div class="col-md-3 col-sm-6">
          <div class="card shadow-sm p-4 text-center border-0 rounded-4">
            <i class="bi bi-calendar-check fs-2 text-warning"></i>
            <h5 class="fw-bold mt-2"><?= $total_reservations ?></h5>
            <p class="text-muted mb-0">Reservations Made</p>
          </div>
        </div>
      </div>

      
      <div class="row g-4 mb-4">

       
        <div class="col-lg-8">
          <div class="card shadow-sm p-4 border-0 rounded-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-graph-up"></i> Monthly Sales Overview</h5>
            <canvas id="salesChart"></canvas>
          </div>
        </div>

       
        <div class="col-lg-4">
          <div class="card shadow-sm p-4 border-0 rounded-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-pie-chart"></i> Inventory Status</h5>
            <canvas id="inventoryChart"></canvas>
          </div>
        </div>
      </div>

     
      <div class="row">
        <div class="col-lg-12">
          <div class="card shadow-sm p-4 border-0 rounded-4">
            <h5 class="fw-bold mb-3 text-success"><i class="bi bi-bookmark-check"></i> Recent Reservations</h5>
            <table class="table table-hover align-middle">
              <thead class="table-success">
                <tr>
                  <th>#</th><th>Customer</th><th>Motorcycle</th><th>Status</th><th>Date</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $resStmt = $pdo->query("
                  SELECT r.Reservation_id, r.Status, r.Reservation_date,
                         CONCAT(u.First_name, ' ', u.Last_name) AS customer,
                         CONCAT(m.Brand, ' ', m.Model) AS motorcycle
                  FROM reservation r
                  JOIN user u ON r.User_User_id=u.User_id
                  JOIN motorcycle m ON r.Motorcycle_Motorcycle_id=m.Motorcycle_id
                  ORDER BY r.Reservation_date DESC LIMIT 5
                ");
                foreach ($resStmt as $r):
                ?>
                  <tr>
                    <td><?= $r['Reservation_id'] ?></td>
                    <td><?= htmlspecialchars($r['customer']) ?></td>
                    <td><?= htmlspecialchars($r['motorcycle']) ?></td>
                    <td><span class="badge bg-<?= $r['Status']=='Approved'?'success':($r['Status']=='Pending'?'warning':'danger') ?>"><?= $r['Status'] ?></span></td>
                    <td><?= $r['Reservation_date'] ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </section>

</main>

<?php include 'includes/footer.php'; ?>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const salesCtx = document.getElementById('salesChart');
new Chart(salesCtx, {
  type: 'line',
  data: {
    labels: <?= json_encode($months) ?>,
    datasets: [{
      label: 'Sales (₱)',
      data: <?= json_encode($amounts) ?>,
      borderColor: '#077f46',
      backgroundColor: 'rgba(7,127,70,0.1)',
      tension: 0.3,
      fill: true
    }]
  },
  options: { plugins: { legend: { display: false } } }
});

const invCtx = document.getElementById('inventoryChart');
new Chart(invCtx, {
  type: 'doughnut',
  data: {
    labels: ['Available', 'Reserved', 'Sold'],
    datasets: [{
      data: [<?= $available ?>, <?= $reserved ?>, <?= $sold ?>],
      backgroundColor: ['#198754','#ffc107','#dc3545']
    }]
  },
  options: { plugins: { legend: { position: 'bottom' } } }
});
</script>
