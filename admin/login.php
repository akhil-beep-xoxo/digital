<?php
require_once __DIR__ . '/../includes/config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!empty($_SESSION['admin_logged_in'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === $admin_user && $password === $admin_pass) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: dashboard.php');
        exit;
    }
    $error = 'Invalid username or password. Please try again.';
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Login | DIGITAL SERVICE 24</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="admin-login-page">
  <main class="admin-login-shell">
    <div class="admin-login-left">
      <a href="../index.php" class="login-brand">
        <span class="brand-icon"><i class="bi bi-lightning-charge-fill"></i></span>
        <span>DIGITAL SERVICE 24</span>
      </a>

      <div class="login-hero-text">
        <span class="secure-badge"><i class="bi bi-shield-check"></i> Secure Admin Area</span>
        <h1>Manage client queries with a clean professional dashboard.</h1>
        <p>Login to view all contact form messages stored from your website and keep track of new WhatsApp inquiries.</p>
      </div>

      <div class="login-feature-grid">
        <div class="login-feature"><i class="bi bi-database-check"></i><span>Saved Queries</span></div>
        <div class="login-feature"><i class="bi bi-whatsapp"></i><span>WhatsApp Leads</span></div>
        <div class="login-feature"><i class="bi bi-lock-fill"></i><span>Admin Only</span></div>
      </div>
    </div>

    <div class="admin-login-right">
      <div class="modern-login-card">
        <div class="text-center mb-4">
          <div class="login-avatar"><i class="bi bi-person-lock"></i></div>
          <h2>Admin Login</h2>
          <p>Enter your credentials to open the admin panel.</p>
        </div>

        <?php if ($error): ?>
          <div class="alert alert-danger login-alert" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo htmlspecialchars($error); ?>
          </div>
        <?php endif; ?>

        <form method="post" autocomplete="off">
          <div class="mb-3">
            <label class="form-label" for="username">Username</label>
            <div class="login-input-wrap">
              <i class="bi bi-person"></i>
              <input id="username" class="form-control" name="username" placeholder="Enter admin username" required autofocus>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label" for="password">Password</label>
            <div class="login-input-wrap">
              <i class="bi bi-key"></i>
              <input id="password" class="form-control" type="password" name="password" placeholder="Enter admin password" required>
            </div>
          </div>

          <button class="btn btn-primary w-100 login-submit" type="submit">
            <i class="bi bi-box-arrow-in-right me-2"></i>Login to Dashboard
          </button>
        </form>

        <div class="login-footer-links">
          <a href="../index.php"><i class="bi bi-arrow-left me-1"></i>Back to Website</a>
          <span>Protected Panel</span>
        </div>
      </div>
    </div>
  </main>
</body>
</html>
