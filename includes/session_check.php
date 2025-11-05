<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Require login for a page
 */
function require_login($role = null) {
    if (empty($_SESSION['username'])) {
        $_SESSION['msg'] = 'Please login first';
        header('Location: login.php');
        exit;
    }

    // If role is specified, enforce it
    if ($role && ($_SESSION['role'] != $role)) {
        $_SESSION['msg'] = 'Bawal ka doon.';
        header('Location: login.php');
        exit;
    }
}

/**
 * Show flash messages
 */
function flash_message() {
    if (!empty($_SESSION['msg'])) {
        echo <<<HTML
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>{$_SESSION['msg']}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        HTML;
        unset($_SESSION['msg']);
    }

    if (!empty($_SESSION['msg_success'])) {
        echo <<<HTML
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{$_SESSION['msg_success']}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        HTML;
        unset($_SESSION['msg_success']);
    }
}

/**
 * Get current user info
 */
function current_user() {
    if (!empty($_SESSION['username'])) {
        return [
            'username' => $_SESSION['username'],
            'role' => $_SESSION['role'],
            'user_id' => $_SESSION['user_id'] ?? null,
            'email' => $_SESSION['email'] ?? null
        ];
    }
    return null;
}
