<?php
require_once __DIR__ . '/includes/config.php';

$errors = [];
$formData = ['name'=>'','email'=>'','phone'=>'','service'=>'','message'=>''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($formData as $key => $value) {
        $formData[$key] = trim($_POST[$key] ?? '');
    }

    if ($formData['name'] === '') $errors[] = 'Please enter your name.';
    if (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email address.';
    if ($formData['phone'] === '') $errors[] = 'Please enter your phone number.';
    if ($formData['service'] === '') $errors[] = 'Please select a service.';
    if ($formData['message'] === '') $errors[] = 'Please enter your project details.';

    if (!$errors) {
        $stmt = $pdo->prepare('INSERT INTO contact_messages (name, email, phone, service, message) VALUES (:name, :email, :phone, :service, :message)');
        $stmt->execute($formData);

        $whatsappText = "New Inquiry - DIGITAL SERVICE 24\n\n"
            . "Name: {$formData['name']}\n"
            . "Email: {$formData['email']}\n"
            . "Phone: {$formData['phone']}\n"
            . "Service: {$formData['service']}\n\n"
            . "Message:\n{$formData['message']}";

        $cleanNumber = preg_replace('/\D+/', '', $whatsapp_number);
        header('Location: https://wa.me/' . $cleanNumber . '?text=' . rawurlencode($whatsappText));
        exit;
    }
}

include __DIR__ . '/includes/header.php';
?>
<section class="page-banner"><div class="container text-center"><div class="kicker">Contact Us</div><h1>Let’s discuss your project</h1><p>Your query will be saved in admin and opened in WhatsApp.</p></div></section>
<section class="section"><div class="container"><div class="row g-4">
  <div class="col-lg-7"><div class="contact-card contact-panel h-100"><h4 class="mb-3">Send Us a Message</h4>
    <?php if ($errors): ?><div class="alert alert-danger"><strong>Please fix:</strong><ul class="mb-0"><?php foreach ($errors as $error): ?><li><?php echo htmlspecialchars($error); ?></li><?php endforeach; ?></ul></div><?php endif; ?>
    <form method="post">
      <div class="row g-3">
        <div class="col-md-6"><label class="form-label">Your Name</label><input name="name" class="form-control" value="<?php echo htmlspecialchars($formData['name']); ?>" required></div>
        <div class="col-md-6"><label class="form-label">Your Email</label><input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($formData['email']); ?>" required></div>
        <div class="col-md-6"><label class="form-label">Phone Number</label><input name="phone" class="form-control" value="<?php echo htmlspecialchars($formData['phone']); ?>" required></div>
        <div class="col-md-6"><label class="form-label">Select Service</label><select name="service" class="form-select" required><option value="">Choose a service</option><?php foreach (['Digital Marketing','Software Development','Website Development','Social Media Management'] as $s): ?><option value="<?php echo htmlspecialchars($s); ?>" <?php echo $formData['service']===$s?'selected':''; ?>><?php echo htmlspecialchars($s); ?></option><?php endforeach; ?></select></div>
        <div class="col-12"><label class="form-label">Project Details</label><textarea name="message" class="form-control" rows="6" required><?php echo htmlspecialchars($formData['message']); ?></textarea></div>
        <div class="col-12"><button class="btn btn-primary" type="submit"><i class="bi bi-whatsapp me-2"></i>Save & Send to WhatsApp</button></div>
      </div>
    </form>
  </div></div>
  <div class="col-lg-5"><div class="contact-card contact-panel h-100"><h4>Contact Details</h4><p><b>Phone:</b> <?php echo htmlspecialchars($company_phone); ?></p><p><b>Email:</b> <?php echo htmlspecialchars($company_email); ?></p><p><a class="btn btn-success" href="https://wa.me/<?php echo htmlspecialchars(preg_replace('/\D+/', '', $whatsapp_number)); ?>" target="_blank">Chat on WhatsApp</a></p></div></div>
</div></div></section>
<?php include __DIR__ . '/includes/footer.php'; ?>
