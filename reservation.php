<?php
include 'includes/session_check.php';
require_login();
flash_message();

include 'includes/header.php';
include 'includes/head.php';
require 'db_con.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<div class='text-center mt-5 text-danger fw-bold'>Invalid motorcycle ID.</div>";
    exit;
}


$stmt = $pdo->prepare("SELECT * FROM motorcycle WHERE Motorcycle_id = ?");
$stmt->execute([$id]);
$moto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$moto) {
    echo "<div class='text-center mt-5 text-danger fw-bold'>Motorcycle not found.</div>";
    exit;
}


$imgStmt = $pdo->prepare("SELECT Image_path FROM motorcycle_image WHERE Motorcycle_id = ?");
$imgStmt->execute([$id]);
$gallery = $imgStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="main">

  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Reserve Motorcycle</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="index.php">Home</a></li>
          <li class="current">Reservation</li>
        </ol>
      </nav>
    </div>
  </div>


  <section id="property-details" class="property-details section">
    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="row g-4">


        <div class="col-lg-7">
          <div class="property-hero mb-5" data-aos="fade-up" data-aos-delay="200">
            <div class="swiper init-swiper">
              <div class="swiper-wrapper">
                <?php if ($gallery): ?>
                  <?php foreach ($gallery as $img): ?>
                    <div class="swiper-slide">
                      <img src="<?= htmlspecialchars($img['Image_path']) ?>" class="img-fluid rounded" alt="Motorcycle Image">
                    </div>
                  <?php endforeach; ?>
                <?php else: ?>
                  <div class="swiper-slide">
                    <img src="assets/img/motorcycles/placeholder.jpg" class="img-fluid rounded" alt="No image available">
                  </div>
                <?php endif; ?>
              </div>

              <div class="swiper-button-next"></div>
              <div class="swiper-button-prev"></div>

              <script type="application/json" class="swiper-config">
              {
                "loop": <?= (count($gallery) > 1) ? 'true' : 'false' ?>,
                "autoplay": { "delay": 3500 },
                "navigation": {
                  "nextEl": ".swiper-button-next",
                  "prevEl": ".swiper-button-prev"
                }
              }
              </script>
            </div>
          </div>

     
          <div class="property-info" data-aos="fade-up" data-aos-delay="300">
            <h2 class="fw-bold mb-3"><?= htmlspecialchars($moto['Brand'] . ' ' . $moto['Model']) ?></h2>
            <ul class="list-unstyled text-muted mb-4">
              <li><i class="bi bi-palette"></i> <strong>Color:</strong> <?= htmlspecialchars($moto['Color']) ?></li>
              <li><i class="bi bi-gear"></i> <strong>Engine:</strong> <?= htmlspecialchars($moto['Engine_type']) ?></li>
              <li><i class="bi bi-cash-stack"></i> <strong>Price:</strong> ₱<?= number_format($moto['Price'], 2) ?></li>
              <li><i class="bi bi-info-circle"></i> <strong>Status:</strong> <?= htmlspecialchars($moto['Status']) ?></li>
            </ul>

            <p class="fst-italic text-secondary border-top pt-3">
              Interested in this motorcycle? Fill out the form to reserve it. Our staff will contact you for confirmation.
            </p>
          </div>
        </div>

   
        <div class="col-lg-5">
          <div class="bg-white p-4 rounded shadow" data-aos="fade-up" data-aos-delay="400">
            <h4 class="fw-bold mb-3"><i class="bi bi-calendar-check"></i> Reservation Form</h4>

            <form id="reservationForm" method="post">
              <input type="hidden" name="motorcycle_id" value="<?= $moto['Motorcycle_id'] ?>">

              <div class="mb-3">
                <label for="fullname" class="form-label">Full Name</label>
                <input type="text" id="fullname" name="fullname" class="form-control" required>
              </div>

              <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" required>
              </div>

              <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="text" id="phone" name="phone" class="form-control" required>
              </div>

              <div class="mb-3">
                <label for="date" class="form-label">Preferred Reservation Date</label>
                <input type="date" id="date" name="date" class="form-control" required>
              </div>

              <div class="mb-3">
                <label for="message" class="form-label">Additional Notes</label>
                <textarea id="message" name="message" rows="3" class="form-control"
                  placeholder="Any special requests or notes..."></textarea>
              </div>

              <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-send"></i> Submit Reservation
              </button>
            </form>
          </div>
        </div>

      </div>
    </div>
  </section>
</main>


<script>
document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('reservationForm');

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(form);

    try {
      const res = await fetch('submit_reservation.php', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });

      const data = await res.json().catch(() => null);
      if (!res.ok || !data) {
        alert('Server error. Please try again later.');
        console.error('Bad Response:', await res.text());
        return;
      }

      alert(data.message);
      if (data.status === 'success') window.location.href = 'motorcycle.php';
    } catch (err) {
      console.error(err);
      alert('An unexpected error occurred. Please try again.');
    }
  });
});
</script>

<?php include 'includes/footer.php'; ?>
