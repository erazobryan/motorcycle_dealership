<?php

include 'includes/session_check.php';
require_login();
include 'db_con.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply_message'])) {
    $id = (int) $_POST['message_id'];
    $reply = trim($_POST['reply_message']);

    if ($reply !== '') {
        $stmt = $pdo->prepare("UPDATE messages SET reply = ?, replied_at = NOW() WHERE id = ?");
        if ($stmt->execute([$reply, $id])) {
            $_SESSION['flash'] = ['type' => 'success', 'text' => 'Reply sent successfully.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'text' => 'Failed to save reply.'];
        }
    } else {
        $_SESSION['flash'] = ['type' => 'error', 'text' => 'Reply cannot be empty.'];
    }

    
    header("Location: messages.php");
    exit;
}


include 'includes/header.php';
include 'includes/head2.php';
?>

<main class="main-content p-4">
  <div class="container">
    <h2 class="mb-4">Customer Messages</h2>

    <?php if (!empty($_SESSION['flash'])): ?>
      <div class="alert alert-<?= $_SESSION['flash']['type'] === 'success' ? 'success' : 'danger' ?>">
        <?= htmlspecialchars($_SESSION['flash']['text']) ?>
      </div>
      <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>


    <div class="table-responsive bg-white shadow-sm rounded p-3">
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Subject</th>
            <th>Message</th>
            <th>Reply</th>
            <th>Replied At</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $stmt = $pdo->query("SELECT * FROM messages ORDER BY date_sent DESC");
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
              $reply = $row['reply'] ?? '';
              $repliedAt = $row['replied_at'] ?? '';
          ?>
            <tr>
              <td><?= (int)$row['id'] ?></td>
              <td><?= htmlspecialchars($row['name'] ?? 'N/A') ?></td>
              <td><?= htmlspecialchars($row['subject'] ?? 'N/A') ?></td>
              <td><?= nl2br(htmlspecialchars($row['message'] ?? '')) ?></td>
              <td><?= $reply ? nl2br(htmlspecialchars($reply)) : '<span class="text-muted">No reply yet</span>' ?></td>
              <td><?= $repliedAt ?: '-' ?></td>
              <td>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#replyModal<?= $row['id'] ?>">
                  Reply
                </button>
              </td>
            </tr>

      
            <div class="modal fade" id="replyModal<?= $row['id'] ?>" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <form method="POST">
                    <div class="modal-header">
                      <h5 class="modal-title">Reply to <?= htmlspecialchars($row['name'] ?? 'Customer') ?></h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <input type="hidden" name="message_id" value="<?= (int)$row['id'] ?>">
                      <div class="mb-3">
                        <label class="form-label fw-bold">Customer Message</label>
                        <div class="border p-2 bg-light small"><?= nl2br(htmlspecialchars($row['message'] ?? '')) ?></div>
                      </div>
                      <div class="mb-3">
                        <label class="form-label fw-bold">Your Reply</label>
                        <textarea name="reply_message" class="form-control" rows="4" required><?= htmlspecialchars($reply) ?></textarea>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                      <button type="submit" class="btn btn-success">Send Reply</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>

<?php include 'includes/footer.php'; ?>
