<?php
include 'includes/session_check.php';
require_login();
flash_message();

include 'includes/header.php';
include 'includes/head.php';
include 'db_con.php';


$models = $pdo->query("SELECT DISTINCT Model FROM motorcycle ORDER BY Model ASC")->fetchAll();
$engineTypes = $pdo->query("SELECT DISTINCT Engine_type FROM motorcycle ORDER BY Engine_type ASC")->fetchAll();


$brand = $_GET['brand'] ?? '';
$model = $_GET['model'] ?? '';
$engine_type = $_GET['enginetype'] ?? '';

$query = "
  SELECT m.Motorcycle_id, m.Brand, m.Model, m.Color, m.Engine_type, m.Price, m.Status,
         (SELECT i.Image_path FROM motorcycle_image i 
          WHERE i.Motorcycle_id = m.Motorcycle_id 
          ORDER BY i.Uploaded_at DESC LIMIT 1) AS Image_path
  FROM motorcycle m WHERE 1
";
$params = [];

if ($brand) {
  $query .= " AND m.Brand LIKE ?";
  $params[] = "%$brand%";
}
if ($model) {
  $query .= " AND m.Model = ?";
  $params[] = $model;
}
if ($engine_type) {
  $query .= " AND m.Engine_type = ?";
  $params[] = $engine_type;
}

$query .= " ORDER BY m.Motorcycle_id ASC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$motorcycles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="main">
  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Motorcycles</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="index.php">Home</a></li>
          <li class="current">Motorcycles</li>
        </ol>
      </nav>
    </div>
  </div>

  <section id="motorcycles" class="section">
    <div class="container" data-aos="fade-up" data-aos-delay="100">

      <div class="results-header mb-4">
        <h5 class="fw-bold"><?= count($motorcycles) ?> Motorcycle<?= count($motorcycles) != 1 ? 's' : '' ?> Found</h5>
        <p class="text-muted">Select a motorcycle to view details or make a reservation.</p>
      </div>

      <div class="row g-4">
        <?php if ($motorcycles): ?>
          <?php foreach ($motorcycles as $moto): ?>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="150">
              <div class="card shadow-sm border-0 rounded-4 overflow-hidden h-100">

                <!-- Image -->
                <img src="<?= htmlspecialchars($moto['Image_path'] ?: 'assets/img/motorcycles/placeholder.jpg') ?>" 
                     class="card-img-top" alt="<?= htmlspecialchars($moto['Model']) ?>" 
                     style="height: 230px; object-fit: cover;">

                <!-- Details -->
                <div class="card-body d-flex flex-column justify-content-between">
                  <div>
                    <h5 class="card-title fw-bold mb-1"><?= htmlspecialchars($moto['Brand'] . ' ' . $moto['Model']) ?></h5>
                    <p class="text-muted mb-1"><i class="bi bi-palette"></i> <?= htmlspecialchars($moto['Color']) ?></p>
                    <p class="text-muted mb-1"><i class="bi bi-gear"></i> <?= htmlspecialchars($moto['Engine_type']) ?></p>
                    <p class="fw-bold text-success mt-2">₱<?= number_format($moto['Price'], 2) ?></p>
                  </div>

                  <div class="d-flex gap-2 mt-3">
                    <a href="motorcycle_details.php?id=<?= $moto['Motorcycle_id'] ?>" class="btn btn-outline-primary w-50">
                      <i class="bi bi-eye"></i> View Details
                    </a>
                    <a href="reservation.php?id=<?= $moto['Motorcycle_id'] ?>" class="btn btn-primary w-50">
                      <i class="bi bi-calendar-check"></i> Reserve Now
                    </a>
                  </div>
                </div>

              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="col-12 text-center text-muted">
            <p>No motorcycles available right now.</p>
          </div>
        <?php endif; ?>
      </div>

    </div>
  </section>
</main>

<?php include 'includes/footer.php'; ?>
