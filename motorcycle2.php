<?php
include 'includes/session_check.php';
require_login();
flash_message();

include 'includes/header.php';
include 'includes/head2.php';
include 'db_con.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_motorcycle'])) {
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $color = $_POST['color'];
    $engine = $_POST['engine_type'];
    $price = $_POST['price'];

    $stmt = $pdo->prepare("INSERT INTO motorcycle (Brand, Model, Color, Engine_type, Price, Status)
                           VALUES (?, ?, ?, ?, ?, 'Available')");
    $stmt->execute([$brand, $model, $color, $engine, $price]);

    echo "<script>alert('✅ Motorcycle added successfully!');window.location.href='motorcycle2.php';</script>";
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_motorcycle'])) {
    $id = $_POST['motorcycle_id'];
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $color = $_POST['color'];
    $engine = $_POST['engine_type'];
    $price = $_POST['price'];

    $stmt = $pdo->prepare("UPDATE motorcycle 
                           SET Brand=?, Model=?, Color=?, Engine_type=?, Price=? 
                           WHERE Motorcycle_id=?");
    $stmt->execute([$brand, $model, $color, $engine, $price, $id]);

    echo "<script>alert('✅ Motorcycle updated successfully!');window.location.href='motorcycle2.php';</script>";
    exit;
}


if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM motorcycle WHERE Motorcycle_id=?");
    $stmt->execute([$id]);

    echo "<script>alert('🗑️ Motorcycle deleted successfully!');window.location.href='motorcycle2.php';</script>";
    exit;
}


$stmt = $pdo->prepare("SELECT * FROM motorcycle WHERE Status='Available' ORDER BY Motorcycle_id DESC");
$stmt->execute();
$motorcycles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="main">


  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Available Motorcycles</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="dashboard.php">Dashboard</a></li>
          <li class="current">Motorcycles</li>
        </ol>
      </nav>
    </div>
  </div>

  <section class="section">
    <div class="container py-3">


      <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold text-success"><i class="bi bi-motorcycle"></i> Motorcycle Inventory</h4>
        <button class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#addMotorcycleModal">
          <i class="bi bi-plus-circle"></i> Add Motorcycle
        </button>
      </div>


      <div class="card shadow-sm border-0 rounded-4 p-3">
        <div class="table-responsive">
          <table id="motorcycleTable" class="table table-striped align-middle">
            <thead class="table-success">
              <tr>
                <th>ID</th>
                <th>Brand</th>
                <th>Model</th>
                <th>Color</th>
                <th>Engine Type</th>
                <th>Price (₱)</th>
                <th class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($motorcycles as $m): ?>
              <tr>
                <td><?= $m['Motorcycle_id'] ?></td>
                <td><?= htmlspecialchars($m['Brand']) ?></td>
                <td><?= htmlspecialchars($m['Model']) ?></td>
                <td><?= htmlspecialchars($m['Color']) ?></td>
                <td><?= htmlspecialchars($m['Engine_type']) ?></td>
                <td><?= number_format($m['Price'], 2) ?></td>
                <td class="text-center">
                  <button class="btn btn-outline-primary btn-sm" 
                          data-bs-toggle="modal" 
                          data-bs-target="#editMotorcycleModal<?= $m['Motorcycle_id'] ?>">
                    <i class="bi bi-pencil"></i>
                  </button>
                  <a href="?delete=<?= $m['Motorcycle_id'] ?>" 
                     class="btn btn-outline-danger btn-sm"
                     onclick="return confirm('Are you sure you want to delete this motorcycle?')">
                    <i class="bi bi-trash"></i>
                  </a>
                </td>
              </tr>


              <div class="modal fade" id="editMotorcycleModal<?= $m['Motorcycle_id'] ?>" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                  <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header bg-success text-white rounded-top">
                      <h5 class="modal-title"><i class="bi bi-pencil"></i> Edit Motorcycle</h5>
                      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" action="">
                      <div class="modal-body">
                        <input type="hidden" name="motorcycle_id" value="<?= $m['Motorcycle_id'] ?>">
                        <div class="row g-3">
                          <div class="col-md-6">
                            <label class="form-label fw-bold">Brand</label>
                            <input type="text" name="brand" class="form-control" value="<?= htmlspecialchars($m['Brand']) ?>" required>
                          </div>
                          <div class="col-md-6">
                            <label class="form-label fw-bold">Model</label>
                            <input type="text" name="model" class="form-control" value="<?= htmlspecialchars($m['Model']) ?>" required>
                          </div>
                          <div class="col-md-6">
                            <label class="form-label fw-bold">Color</label>
                            <input type="text" name="color" class="form-control" value="<?= htmlspecialchars($m['Color']) ?>" required>
                          </div>
                          <div class="col-md-6">
                            <label class="form-label fw-bold">Engine Type</label>
                            <input type="text" name="engine_type" class="form-control" value="<?= htmlspecialchars($m['Engine_type']) ?>" required>
                          </div>
                          <div class="col-md-6">
                            <label class="form-label fw-bold">Price (₱)</label>
                            <input type="number" name="price" step="0.01" class="form-control" value="<?= htmlspecialchars($m['Price']) ?>" required>
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="edit_motorcycle" class="btn btn-success">Save Changes</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</main>


<div class="modal fade" id="addMotorcycleModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content rounded-4 border-0 shadow">
      <div class="modal-header bg-success text-white rounded-top">
        <h5 class="modal-title"><i class="bi bi-plus-circle"></i> Add Motorcycle</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="">
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-bold">Brand</label>
              <input type="text" name="brand" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">Model</label>
              <input type="text" name="model" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">Color</label>
              <input type="text" name="color" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">Engine Type</label>
              <input type="text" name="engine_type" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">Price (₱)</label>
              <input type="number" name="price" step="0.01" class="form-control" required>
            </div>
          </div>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" name="add_motorcycle" class="btn btn-success">Save Motorcycle</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>


<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    $('#motorcycleTable').DataTable({
      pageLength: 8,
      order: [[0, "desc"]],
    });
  });
</script>
