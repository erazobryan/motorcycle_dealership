<?php
include 'includes/session_check.php';
require_login(); 
flash_message();

include 'includes/header.php';
include 'includes/head.php';
include 'db_con.php';


$id = $_GET['id'] ?? null;
if (!$id) {
    die("<div class='container text-center mt-5'><h3>Invalid motorcycle ID.</h3></div>");
}


$stmt = $pdo->prepare("SELECT * FROM motorcycle WHERE Motorcycle_id = ?");
$stmt->execute([$id]);
$moto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$moto) {
    die("<div class='container text-center mt-5'><h3>Motorcycle not found.</h3></div>");
}


$images = $pdo->prepare("SELECT Image_path FROM motorcycle_image WHERE Motorcycle_id = ?");
$images->execute([$id]);
$gallery = $images->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="main">


  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0"><?= htmlspecialchars($moto['Brand'] . ' ' . $moto['Model']) ?> Details</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="index.php">Home</a></li>
          <li class="current"><?= htmlspecialchars($moto['Brand'] . ' ' . $moto['Model']) ?></li>
        </ol>
      </nav>
    </div>
  </div>


  <section id="property-details" class="property-details section">
    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="row">

    
        <div class="col-lg-7">

   
          <div class="property-hero mb-5" data-aos="fade-up" data-aos-delay="200">
            <div class="hero-image-container">
              <div class="property-gallery-slider swiper init-swiper">
                <script type="application/json" class="swiper-config">
                  {
                    "loop": true,
                    "speed": 600,
                    "autoplay": {"delay": 5000},
                    "navigation": {"nextEl": ".swiper-button-next", "prevEl": ".swiper-button-prev"},
                    "thumbs": {"swiper": ".property-thumbnails-slider"}
                  }
                </script>
                <div class="swiper-wrapper">
                  <?php foreach ($gallery as $img): ?>
                    <div class="swiper-slide">
                      <img src="<?= htmlspecialchars($img['Image_path']) ?>" class="img-fluid hero-image" alt="Motorcycle Image">
                    </div>
                  <?php endforeach; ?>

                  <?php if (empty($gallery)): ?>
                    <div class="swiper-slide">
                      <img src="assets/img/motorcycles/placeholder.jpg" class="img-fluid hero-image" alt="No Image Available">
                    </div>
                  <?php endif; ?>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
              </div>
            </div>

         
            <div class="thumbnail-gallery mt-3">
              <div class="property-thumbnails-slider swiper init-swiper">
                <script type="application/json" class="swiper-config">
                  {
                    "loop": true,
                    "spaceBetween": 10,
                    "slidesPerView": 4,
                    "freeMode": true,
                    "watchSlidesProgress": true,
                    "breakpoints": {
                      "576": {"slidesPerView": 5},
                      "768": {"slidesPerView": 6}
                    }
                  }
                </script>
                <div class="swiper-wrapper">
                  <?php foreach ($gallery as $img): ?>
                    <div class="swiper-slide">
                      <img src="<?= htmlspecialchars($img['Image_path']) ?>" class="img-fluid thumbnail-img" alt="Thumbnail">
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
          </div>

      
          <div class="property-info mb-5" data-aos="fade-up" data-aos-delay="300">
            <div class="property-header">
              <h2 class="property-title"><?= htmlspecialchars($moto['Brand'] . ' ' . $moto['Model']) ?></h2>
              <div class="property-meta">
                <span class="listing-id">Motorcycle ID: #<?= htmlspecialchars($moto['Motorcycle_id']) ?></span>
              </div>
            </div>

            <div class="pricing-section">
              <div class="main-price text-success fs-3">
                ₱<?= number_format($moto['Price'], 2) ?>
              </div>
              <div class="price-breakdown">
                <span class="text-muted">Engine Type: <?= htmlspecialchars($moto['Engine_type']) ?></span><br>
                <span class="text-muted">Color: <?= htmlspecialchars($moto['Color']) ?></span><br>
                <span class="text-muted">Status: <?= htmlspecialchars($moto['Status']) ?></span>
              </div>
            </div>

            <div class="mt-4">
              <p><strong>Description:</strong></p>
              <p>
                <?= !empty($moto['Description']) 
                    ? nl2br(htmlspecialchars($moto['Description'])) 
                    : 'No description available for this motorcycle.' ?>
              </p>
            </div>
          </div>

        </div>

    
        <div class="col-lg-5">
          <div class="sticky-sidebar">

        
            <div class="agent-card mb-4" data-aos="fade-up" data-aos-delay="350">
              <div class="agent-header">
                <div class="agent-avatar">
                  <img src="assets/img/person/person-f-12.webp" class="img-fluid" alt="Agent Photo">
                </div>
                <div class="agent-info">
                  <h4>Motorcycle Dealer</h4>
                  <p class="agent-role">Authorized Seller</p>
                </div>
              </div>
              <div class="agent-contact">
                <div class="contact-item">
                  <i class="bi bi-telephone"></i>
                  <span>+63 912 345 6789</span>
                </div>
                <div class="contact-item">
                  <i class="bi bi-envelope"></i>
                  <span>sales@motorcyclesph.com</span>
                </div>
              </div>
              <div class="agent-actions mt-3">
                <button class="btn btn-outline-primary w-100" onclick="window.location.href='contact.php'">
                  <i class="bi bi-chat-dots"></i> Message Dealer
                </button>
              </div>

            </div>

           
            <div class="actions-card mb-4" data-aos="fade-up" data-aos-delay="250">
              <div class="action-buttons">
                <a href="reservation.php?id=<?= $moto['Motorcycle_id'] ?>" 
                   class="btn btn-primary btn-lg w-100 mb-3">
                  <i class="bi bi-calendar-check"></i> Reserve Now
                </a>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include 'includes/footer.php'; ?>
