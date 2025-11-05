<?php
include 'includes/session_check.php';
include 'includes/header.php';
include 'includes/head.php';
include 'db_con.php';
?>

<main class="main">

  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Contact Us</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="index.php">Home</a></li>
          <li class="current">Contact</li>
        </ol>
      </nav>
    </div>
  </div>


  <section id="contact" class="contact section py-5">
    <div class="container" data-aos="fade-up">

      <div class="row g-4 mb-5">
        <div class="col-md-4">
          <div class="contact-info-card text-center p-4 shadow-sm rounded bg-white h-100">
            <div class="icon-box mb-3 text-primary fs-2"><i class="bi bi-geo-alt"></i></div>
            <h5>Location</h5>
            <p class="text-muted mb-0">482 Pine Street, Seattle, Washington 98101</p>
          </div>
        </div>

        <div class="col-md-4">
          <div class="contact-info-card text-center p-4 shadow-sm rounded bg-white h-100">
            <div class="icon-box mb-3 text-primary fs-2"><i class="bi bi-telephone"></i></div>
            <h5>Call Us</h5>
            <p class="text-muted mb-1">+1 (206) 555-0192</p>
            <p class="text-muted mb-0">+1 (206) 555-1234</p>
          </div>
        </div>

        <div class="col-md-4">
          <div class="contact-info-card text-center p-4 shadow-sm rounded bg-white h-100">
            <div class="icon-box mb-3 text-primary fs-2"><i class="bi bi-envelope"></i></div>
            <h5>Email</h5>
            <p class="text-muted mb-1">connect@example.com</p>
            <p class="text-muted mb-0">support@example.com</p>
          </div>
        </div>
      </div>


    
      <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="200">
        <div class="col-lg-8">
          <div class="contact-form-wrapper p-5 bg-white shadow-sm rounded">
            <h2 class="text-center mb-4 fw-bold">Get in Touch</h2>
            <form id="contactForm">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Full Name</label>
                  <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Subject</label>
                  <input type="text" name="subject" class="form-control" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Phone</label>
                  <input type="text" name="phone" class="form-control">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Email Address</label>
                  <input type="email" name="email" class="form-control" required>
                </div>
                <div class="col-12">
                  <label class="form-label">Message</label>
                  <textarea name="message" class="form-control" rows="5" required></textarea>
                </div>
                <div class="col-12 text-center">
                  <button type="submit" class="btn btn-primary px-5 mt-3">
                    <i class="bi bi-send"></i> Send Message
                  </button>
                </div>
              </div>
            </form>

          
            <div id="responseMsg" class="mt-3 text-center fw-semibold"></div>
          </div>
        </div>
      </div>

    
      <div class="row justify-content-center mt-5">
        <div class="col-lg-8">
          <div class="p-4 bg-white shadow-sm rounded">
            <h4 class="mb-3 text-center">Your Previous Messages & Replies</h4>
            <?php
            $stmt = $pdo->query("SELECT * FROM messages ORDER BY date_sent DESC");
            if ($stmt->rowCount() > 0):
            ?>
              <div class="table-responsive">
                <table class="table table-bordered align-middle">
                  <thead class="table-light">
                    <tr>
                      <th>Subject</th>
                      <th>Message</th>
                      <th>Reply</th>
                      <th>Replied At</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                      <tr>
                        <td><?= htmlspecialchars($row['subject']) ?></td>
                        <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
                        <td><?= $row['reply'] ? nl2br(htmlspecialchars($row['reply'])) : '<span class="text-muted">No reply yet</span>' ?></td>
                        <td><?= $row['replied_at'] ?: '-' ?></td>
                      </tr>
                    <?php endwhile; ?>
                  </tbody>
                </table>
              </div>
            <?php else: ?>
              <p class="text-center text-muted">No messages yet.</p>
            <?php endif; ?>
          </div>
        </div>
      </div>

    </div>
  </section>
</main>


<script>
document.getElementById('contactForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  const form = e.target;
  const formData = new FormData(form);
  const responseMsg = document.getElementById('responseMsg');

  responseMsg.textContent = "Sending...";
  responseMsg.className = "text-info mt-3";

  try {
    const res = await fetch('forms/contact.php', { method: 'POST', body: formData });
    const data = await res.json();

    if (data.status === 'success') {
      responseMsg.textContent = data.message;
      responseMsg.className = "text-success mt-3";
      form.reset();
    } else {
      responseMsg.textContent = "Error: " + data.message;
      responseMsg.className = "text-danger mt-3";
    }
  } catch (err) {
    responseMsg.textContent = "Unexpected error: " + err.message;
    responseMsg.className = "text-danger mt-3";
  }
});
</script>

<?php include 'includes/footer.php'; ?>
</body>
</html>
