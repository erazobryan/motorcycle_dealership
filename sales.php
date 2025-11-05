<?php
include 'includes/session_check.php';
require_login();
flash_message();

include 'includes/header.php';
include 'includes/head2.php';
include 'db_con.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_sale'])) {
    $motorcycle_id = $_POST['motorcycle_id'];
    $customer_id = $_POST['customer_id'];
    $sale_date = $_POST['sale_date'];
    $total_amount = $_POST['total_amount'];

    $pdo->beginTransaction();
    try {
  
        $stmt = $pdo->prepare("INSERT INTO sales (Sales_date, Total_amount, Motorcycle_Motorcycle_id, User_User_id)
                               VALUES (?, ?, ?, ?)");
        $stmt->execute([$sale_date, $total_amount, $motorcycle_id, $customer_id]);

        $update = $pdo->prepare("UPDATE motorcycle SET Status='Sold' WHERE Motorcycle_id=?");
        $update->execute([$motorcycle_id]);

        $pdo->commit();
        echo "<script>alert('✅ Sale recorded successfully!');window.location.href='sales2.php';</script>";
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<script>alert('❌ Failed to record sale: " . addslashes($e->getMessage()) . "');</script>";
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_sale'])) {
    $sale_id = $_POST['sale_id'];
    $sale_date = $_POST['sale_date'];
    $total_amount = $_POST['total_amount'];

    $stmt = $pdo->prepare("UPDATE sales SET Sales_date=?, Total_amount=? WHERE Sales_id=?");
    $stmt->execute([$sale_date, $total_amount, $sale_id]);

    echo "<script>alert('✅ Sale updated successfully!');window.location.href='sales2.php';</script>";
    exit;
}


if (isset($_GET['delete'])) {
    $sale_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM sales WHERE Sales_id=?");
    $stmt->execute([$sale_id]);
    echo "<script>alert('🗑️ Sale deleted successfully!');window.location.href='sales2.php';</script>";
    exit;
}


$sales = $pdo->query("
    SELECT s.Sales_id, s.Sales_date, s.Total_amount,
           CONCAT(u.First_name, ' ', u.Last_name) AS customer_name,
           CONCAT(m.Brand, ' ', m.Model) AS motorcycle_name
    FROM sales s
    JOIN user u ON s.User_User_id = u.User_id
    JOIN motorcycle m ON s.Motorcycle_Motorcycle_id = m.Motorcycle_id
    ORDER BY s.Sales_date DESC
")->fetchAll(PDO::FETCH_ASSOC);


$motorcycles = $pdo->query("SELECT Motorcycle_id, CONCAT(Brand, ' ', Model) AS name FROM motorcycle WHERE Status='Available' ORDER BY Brand ASC")->fetchAll(PDO::FETCH_ASSOC);
$customers = $pdo->query("SELECT User_id, CONCAT(First_name, ' ', Last_name) AS name FROM user WHERE Role='Customer' ORDER BY First_name ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="main">


  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Sales Management</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="dashboard.php">Dashboard</a></li>
          <li class="current">Sales</li>
        </ol>
      </nav>
    </div>
  </div>

  <section class="section" data-aos="fade-up" data-aos-delay="100">
    <div class="container py-4">


      <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-success"><i class="bi bi-cash-coin"></i> Sales Records</h4>
        <button class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#addSaleModal">
          <i class="bi bi-plus-circle"></i> Add Sale
        </button>
      </div>


      <div class="card shadow-sm border-0 rounded-4 p-3">
        <div class="table-responsive">
          <table id="salesTable" class="table table-striped align-middle">
            <thead class="table-success">
              <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Motorcycle</th>
                <th>Sale Date</th>
                <th>Total Amount (₱)</th>
                <th class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($sales as $s): ?>
              <tr>
                <td><?= $s['Sales_id'] ?></td>
                <td><?= htmlspecialchars($s['customer_name']) ?></td>
                <td><?= htmlspecialchars($s['motorcycle_name']) ?></td>
                <td><?= htmlspecialchars($s['Sales_date']) ?></td>
                <td><strong><?= number_format($s['Total_amount'], 2) ?></strong></td>
                <td class="text-center">
                  <button class="btn btn-outline-primary btn-sm" 
                          data-bs-toggle="modal" 
                          data-bs-target="#editSaleModal<?= $s['Sales_id'] ?>">
                    <i class="bi bi-pencil"></i>
                  </button>
                  <a href="?delete=<?= $s['Sales_id'] ?>" 
                     class="btn btn-outline-danger btn-sm"
                     onclick="return confirm('Delete this sale record?');">
                    <i class="bi bi-trash"></i>
                  </a>
                </td>
              </tr>

      
              <div class="modal fade" id="editSaleModal<?= $s['Sales_id'] ?>" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header bg-success text-white rounded-top">
                      <h5 class="modal-title"><i class="bi bi-pencil"></i> Edit Sale</h5>
                      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" action="">
                      <div class="modal-body">
                        <input type="hidden" name="sale_id" value="<?= $s['Sales_id'] ?>">
                        <div class="mb-3">
                          <label class="form-label fw-bold">Sale Date</label>
                          <input type="date" name="sale_date" class="form-control" value="<?= $s['Sales_date'] ?>" required>
                        </div>
                        <div class="mb-3">
                          <label class="form-label fw-bold">Total Amount (₱)</label>
                          <input type="number" step="0.01" name="total_amount" class="form-control" value="<?= $s['Total_amount'] ?>" required>
                        </div>
                      </div>
                      <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="edit_sale" class="btn btn-success">Save Changes</button>
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


<div class="modal fade" id="addSaleModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content rounded-4 border-0 shadow">
      <div class="modal-header bg-success text-white rounded-top">
        <h5 class="modal-title"><i class="bi bi-plus-circle"></i> Add New Sale</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="">
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-bold">Select Motorcycle</label>
              <select name="motorcycle_id" class="form-select" required>
                <option value="">-- Select Motorcycle --</option>
                <?php foreach ($motorcycles as $m): ?>
                  <option value="<?= $m['Motorcycle_id'] ?>"><?= htmlspecialchars($m['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">Select Customer</label>
              <select name="customer_id" class="form-select" required>
                <option value="">-- Select Customer --</option>
                <?php foreach ($customers as $c): ?>
                  <option value="<?= $c['User_id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">Sale Date</label>
              <input type="date" name="sale_date" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">Total Amount (₱)</label>
              <input type="number" name="total_amount" step="0.01" class="form-control" required>
            </div>
          </div>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" name="add_sale" class="btn btn-success">Save Sale</button>
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
    $('#salesTable').DataTable({
      pageLength: 8,
      order: [[0, "desc"]],
    });
  });
</script>
