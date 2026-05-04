<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$current = basename($_SERVER['PHP_SELF']);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>DIGITAL SERVICE 24</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top border-bottom">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">DIGITAL SERVICE 24</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
        <li class="nav-item"><a class="nav-link <?php echo $current==='index.php'?'active':''; ?>" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link <?php echo $current==='about.php'?'active':''; ?>" href="about.php">About</a></li>
        <li class="nav-item"><a class="nav-link <?php echo $current==='portfolio.php'?'active':''; ?>" href="portfolio.php">Portfolio</a></li>
        <li class="nav-item"><a class="nav-link <?php echo $current==='why-us.php'?'active':''; ?>" href="why-us.php">Why Us</a></li>
        <li class="nav-item"><a class="btn btn-primary btn-sm" href="contact.php">Contact</a></li>
        <li class="nav-item"><a class="btn btn-outline-dark btn-sm" href="admin/login.php">Admin</a></li>
      </ul>
    </div>
  </div>
</nav>
