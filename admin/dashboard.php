<?php
require_once __DIR__ . '/../includes/config.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['admin_logged_in'])) { header('Location: login.php'); exit; }

$messages = $pdo->query('SELECT * FROM contact_messages ORDER BY created_at DESC')->fetchAll();
$totalMessages = count($messages);
$todayMessages = 0;
$services = [];
foreach ($messages as $m) {
    if (date('Y-m-d', strtotime($m['created_at'])) === date('Y-m-d')) $todayMessages++;
    $services[$m['service']] = ($services[$m['service']] ?? 0) + 1;
}
$topService = $services ? array_keys($services, max($services))[0] : 'No data';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin Dashboard | DIGITAL SERVICE 24</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root{--bg:#f4f7fb;--card:#ffffff;--text:#111827;--muted:#64748b;--primary:#1266ff;--line:#dbe4f0;--soft:#eef5ff;--danger:#ef4444;}
    *{box-sizing:border-box}
    body{margin:0;background:linear-gradient(135deg,#f7fbff 0%,#eef4ff 45%,#f9fbff 100%);font-family:Inter,Segoe UI,Arial,sans-serif;color:var(--text);min-height:100vh;}
    .admin-shell{display:flex;min-height:100vh;}
    .sidebar{width:270px;background:#0f172a;color:#fff;padding:24px 18px;position:fixed;left:0;top:0;bottom:0;box-shadow:10px 0 30px rgba(15,23,42,.12);}
    .brand{display:flex;align-items:center;gap:12px;margin-bottom:30px;}
    .brand-icon{width:46px;height:46px;border-radius:16px;background:linear-gradient(135deg,#1266ff,#5eead4);display:grid;place-items:center;font-size:22px;box-shadow:0 12px 30px rgba(18,102,255,.35);}
    .brand h3{font-size:18px;margin:0;font-weight:800;letter-spacing:.4px;}.brand span{font-size:12px;color:#cbd5e1;}
    .nav-box{border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.06);border-radius:18px;padding:10px;margin-bottom:20px;}
    .nav-link-custom{display:flex;align-items:center;gap:10px;color:#e5e7eb;text-decoration:none;padding:12px 14px;border-radius:14px;font-weight:600;}
    .nav-link-custom.active{background:#fff;color:#0f172a;}.nav-link-custom:hover{background:rgba(255,255,255,.12);color:#fff;}
    .side-help{font-size:13px;color:#cbd5e1;line-height:1.7;margin-top:24px;padding:16px;border-radius:18px;background:rgba(255,255,255,.06);}
    .main{margin-left:270px;width:calc(100% - 270px);padding:28px;}
    .topbar{display:flex;align-items:center;justify-content:space-between;gap:18px;margin-bottom:24px;}
    .title-area h1{font-size:32px;font-weight:850;margin:0;letter-spacing:-.7px;}.title-area p{margin:6px 0 0;color:var(--muted);}
    .logout-btn{border:1px solid #fecaca;color:#dc2626;background:#fff;border-radius:14px;padding:11px 18px;text-decoration:none;font-weight:750;box-shadow:0 8px 20px rgba(220,38,38,.08)}
    .logout-btn:hover{background:#fff1f2;color:#b91c1c;}
    .stats-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px;margin-bottom:22px;}
    .stat-card{background:rgba(255,255,255,.86);border:1px solid var(--line);border-radius:22px;padding:20px;box-shadow:0 12px 30px rgba(15,23,42,.07);backdrop-filter: blur(10px);}
    .stat-icon{width:48px;height:48px;border-radius:16px;background:var(--soft);color:var(--primary);display:grid;place-items:center;font-size:22px;margin-bottom:12px;}.stat-card h3{font-size:30px;font-weight:850;margin:0}.stat-card p{margin:3px 0 0;color:var(--muted);font-weight:600}
    .panel{background:rgba(255,255,255,.92);border:1px solid var(--line);border-radius:24px;box-shadow:0 14px 38px rgba(15,23,42,.08);overflow:hidden;}
    .panel-head{display:flex;justify-content:space-between;align-items:center;gap:16px;padding:20px 22px;border-bottom:1px solid var(--line);}
    .panel-head h2{font-size:20px;margin:0;font-weight:850}.search-box{max-width:360px;width:100%;position:relative}.search-box i{position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#94a3b8}.search-box input{width:100%;border:1px solid var(--line);border-radius:14px;padding:12px 14px 12px 40px;outline:none}.search-box input:focus{border-color:#1266ff;box-shadow:0 0 0 4px rgba(18,102,255,.10)}
    .messages-list{padding:18px;display:grid;gap:16px;}.message-card{border:1px solid var(--line);border-radius:20px;background:#fff;padding:18px;box-shadow:0 8px 22px rgba(15,23,42,.04);}
    .message-top{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;border-bottom:1px solid #eef2f7;padding-bottom:14px;margin-bottom:14px;}.person{display:flex;gap:12px;align-items:center}.avatar{width:48px;height:48px;border-radius:16px;background:linear-gradient(135deg,#1266ff,#7c3aed);color:#fff;display:grid;place-items:center;font-weight:850;font-size:18px}.person h4{font-size:18px;font-weight:850;margin:0}.person span{color:var(--muted);font-size:13px}.badge-service{background:#eef5ff;color:#0f5be8;border:1px solid #cfe0ff;border-radius:999px;padding:8px 12px;font-weight:750;font-size:13px;white-space:nowrap;}
    .detail-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px;margin-bottom:14px}.detail-box{border:1px solid #edf2f7;border-radius:15px;padding:12px 14px;background:#fbfdff;min-width:0}.detail-box label{display:block;font-size:12px;color:var(--muted);font-weight:800;text-transform:uppercase;letter-spacing:.4px;margin-bottom:4px}.detail-box a,.detail-box div{color:#111827;text-decoration:none;font-weight:650;word-break:break-word;}
    .message-full{background:#f8fbff;border:1px solid #e6eef8;border-radius:16px;padding:14px 16px;line-height:1.7;white-space:pre-wrap;word-break:break-word;}.message-label{font-size:12px;color:var(--muted);font-weight:850;text-transform:uppercase;letter-spacing:.5px;margin-bottom:7px}.actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:14px}.action-btn{border:1px solid var(--line);background:#fff;border-radius:12px;padding:9px 13px;text-decoration:none;color:#111827;font-weight:750;font-size:14px}.action-btn.whatsapp{border-color:#bbf7d0;color:#15803d;background:#f0fdf4}.action-btn.email{border-color:#bfdbfe;color:#1d4ed8;background:#eff6ff}
    .empty{padding:55px 20px;text-align:center;color:var(--muted)}.empty i{font-size:54px;color:#cbd5e1;margin-bottom:10px}
    @media(max-width:900px){.sidebar{position:relative;width:100%;height:auto}.admin-shell{display:block}.main{margin-left:0;width:100%;padding:18px}.stats-grid,.detail-grid{grid-template-columns:1fr}.topbar,.panel-head,.message-top{flex-direction:column;align-items:stretch}.title-area h1{font-size:25px}}
  </style>
</head>
<body>
<div class="admin-shell">
  <aside class="sidebar">
    <div class="brand">
      <div class="brand-icon"><i class="bi bi-shield-lock-fill"></i></div>
      <div><h3>DIGITAL SERVICE 24</h3><span>Admin Control Panel</span></div>
    </div>
    <div class="nav-box">
      <a class="nav-link-custom active" href="dashboard.php"><i class="bi bi-chat-square-text-fill"></i> Contact Messages</a>
      <a class="nav-link-custom" href="../index.php"><i class="bi bi-house-door-fill"></i> Back to Website</a>
      <a class="nav-link-custom" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
    <div class="side-help">
      <strong>Tip:</strong><br>
      New contact form queries will be saved here and also opened in WhatsApp from the contact page.
    </div>
  </aside>

  <main class="main">
    <div class="topbar">
      <div class="title-area">
        <h1>Contact Messages</h1>
        <p>View full client details, service requirement, phone, email and complete message.</p>
      </div>
      <a class="logout-btn" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
    </div>

    <section class="stats-grid">
      <div class="stat-card"><div class="stat-icon"><i class="bi bi-inbox-fill"></i></div><h3><?php echo $totalMessages; ?></h3><p>Total Messages</p></div>
      <div class="stat-card"><div class="stat-icon"><i class="bi bi-calendar-check-fill"></i></div><h3><?php echo $todayMessages; ?></h3><p>Today Messages</p></div>
      <div class="stat-card"><div class="stat-icon"><i class="bi bi-award-fill"></i></div><h3 style="font-size:20px"><?php echo htmlspecialchars($topService); ?></h3><p>Top Service</p></div>
    </section>

    <section class="panel">
      <div class="panel-head">
        <h2><i class="bi bi-list-check me-2 text-primary"></i>All Client Queries</h2>
        <div class="search-box"><i class="bi bi-search"></i><input id="searchInput" type="text" placeholder="Search name, email, phone, service or message..."></div>
      </div>

      <div class="messages-list" id="messagesList">
        <?php if(!$messages): ?>
          <div class="empty"><i class="bi bi-chat-dots"></i><h4>No messages yet</h4><p>When someone submits the contact form, the full query will appear here.</p></div>
        <?php endif; ?>

        <?php foreach($messages as $m):
          $initial = strtoupper(substr(trim($m['name'] ?? 'U'), 0, 1));
          $phoneClean = preg_replace('/[^0-9]/', '', $m['phone'] ?? '');
          $waText = rawurlencode('Hello ' . ($m['name'] ?? '') . ', thank you for contacting DIGITAL SERVICE 24 regarding ' . ($m['service'] ?? 'your project') . '.');
        ?>
          <article class="message-card search-item">
            <div class="message-top">
              <div class="person">
                <div class="avatar"><?php echo htmlspecialchars($initial); ?></div>
                <div>
                  <h4><?php echo htmlspecialchars($m['name']); ?></h4>
                  <span><i class="bi bi-clock me-1"></i><?php echo date('d M Y, h:i A', strtotime($m['created_at'])); ?></span>
                </div>
              </div>
              <span class="badge-service"><i class="bi bi-briefcase me-1"></i><?php echo htmlspecialchars($m['service']); ?></span>
            </div>

            <div class="detail-grid">
              <div class="detail-box"><label>Email</label><a href="mailto:<?php echo htmlspecialchars($m['email']); ?>"><?php echo htmlspecialchars($m['email']); ?></a></div>
              <div class="detail-box"><label>Phone</label><a href="tel:<?php echo htmlspecialchars($m['phone']); ?>"><?php echo htmlspecialchars($m['phone']); ?></a></div>
              <div class="detail-box"><label>Date</label><div><?php echo date('d M Y, h:i A', strtotime($m['created_at'])); ?></div></div>
            </div>

            <div class="message-label">Full Message</div>
            <div class="message-full"><?php echo nl2br(htmlspecialchars($m['message'])); ?></div>

            <div class="actions">
              <?php if($phoneClean): ?><a class="action-btn whatsapp" target="_blank" href="https://wa.me/<?php echo htmlspecialchars($phoneClean); ?>?text=<?php echo $waText; ?>"><i class="bi bi-whatsapp me-1"></i>Reply on WhatsApp</a><?php endif; ?>
              <a class="action-btn email" href="mailto:<?php echo htmlspecialchars($m['email']); ?>"><i class="bi bi-envelope-fill me-1"></i>Email Client</a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </section>
  </main>
</div>
<script>
  const searchInput = document.getElementById('searchInput');
  const items = document.querySelectorAll('.search-item');
  if(searchInput){
    searchInput.addEventListener('input', function(){
      const q = this.value.toLowerCase().trim();
      items.forEach(card => card.style.display = card.innerText.toLowerCase().includes(q) ? '' : 'none');
    });
  }
</script>
</body>
</html>
