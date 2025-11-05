  <header id="header" class="header d-flex align-items-center sticky-top">
      <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

        <a href="index.html" class="logo d-flex align-items-center">
          <!-- Uncomment the line below if you also wish to use an image logo -->
          <!-- <img src="assets/img/logo.webp" alt=""> -->
          <a href="index.html" class="logo d-flex align-items-center">
  <img src="assets/img/motorcycles/motorcycle.png" 
       alt="Motorcycle Icon" 
       style="width:32px; height:32px; margin-right:8px;">
  <h1 class="sitename">Motorcycle Dealership</h1>
</a>

        </a>

        <nav id="navmenu" class="navmenu">
        <ul class="d-flex align-items-center mb-0">
          <?php $active = basename($_SERVER['PHP_SELF']); ?>
          <li><a href="admin_dashboard.php" class="<?= $active == 'admin_dashboard.php' ? 'active text-success fw-bold' : '' ?>"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
          <li><a href="motorcycle2.php" class="<?= $active == 'motorcycle.php' ? 'active text-success fw-bold' : '' ?>"><i class="bi bi-motorcycle"></i> Motorcycles</a></li>
          <li><a href="sales.php" class="<?= $active == 'sales.php' ? 'active text-success fw-bold' : '' ?>"><i class="bi bi-cash-coin"></i> Sales</a></li>
          <li><a href="reservation2.php" class="<?= $active == 'reservation.php' ? 'active text-success fw-bold' : '' ?>"><i class="bi bi-calendar-check"></i> Reservations</a></li>
          <li><a href="messages.php" class="<?= $active == 'messages.php' ? 'active text-success fw-bold' : '' ?>"><i class="bi bi-envelope"></i> Messages</a></li>


          <!-- Profile Dropdown -->
          <li class="dropdown">
            <a href="#" class="d-flex align-items-center dropdown-toggle" data-bs-toggle="dropdown">
              <i class="bi bi-person-circle me-1"></i> Admin
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
              <li><a class="dropdown-item" href="profile2.php"><i class="bi bi-person"></i> Profile</a></li>
              <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
            </ul>
          </li>
        </ul>

        <!-- Mobile Toggle -->
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      </div>
    </header>