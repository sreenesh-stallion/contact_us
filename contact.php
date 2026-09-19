<?php
session_start();

// Generate a secure CSRF token if one does not exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Initialize form states
$errors = [];
$success = false;
$name = '';
$email = '';
$subject = '';
$message = '';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Honeypot check: Bots fill hidden fields; real users don't
    if (!empty($_POST['website'])) {
        // Silently treat as processed to fool bot scrapers
        $success = true;
    } else {
        // 2. CSRF Token Verification
        if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            $errors['csrf'] = 'Session security token expired or invalid. Please refresh the page and try again.';
        }

        // 3. Collect & Sanitize Form Inputs
        $name    = trim($_POST['name'] ?? '');
        $email   = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        // 4. Validate Inputs
        if (empty($name)) {
            $errors['name'] = 'Full Name is required.';
        } elseif (mb_strlen($name) < 2) {
            $errors['name'] = 'Name must be at least 2 characters long.';
        }

        if (empty($email)) {
            $errors['email'] = 'Email address is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        }

        if (empty($subject)) {
            $errors['subject'] = 'Please select a subject.';
        }

        if (empty($message)) {
            $errors['message'] = 'Message cannot be empty.';
        } elseif (mb_strlen($message) < 10) {
            $errors['message'] = 'Message should contain at least 10 characters.';
        }

        // 5. Action upon Successful Validation
        if (empty($errors)) {
            /*
             * Example email dispatch in production:
             *
             * $to = 'your-email@example.com';
             * $mail_subject = "Contact Form: $subject";
             * $mail_body = "From: $name <$email>\n\nSubject: $subject\n\nMessage:\n$message";
             * $headers = [
             *     'From' => 'no-reply@yourdomain.com',
             *     'Reply-To' => $email,
             *     'X-Mailer' => 'PHP/' . phpversion()
             * ];
             * mail($to, $mail_subject, $mail_body, $headers);
             */

            $success = true;

            // Rotate CSRF token on success
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

            // Clear values for fresh form
            $name = '';
            $email = '';
            $subject = '';
            $message = '';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Get in touch with us for inquiries, partnerships, and support. We are here to help.">
  <title>Contact Us | Get In Touch</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <!-- Ambient background glow elements -->
  <div class="ambient-glow ambient-glow-1"></div>
  <div class="ambient-glow ambient-glow-2"></div>
  <div class="ambient-glow ambient-glow-3"></div>

  <main class="contact-wrapper">

    <!-- Left Column: Contact Details & Info -->
    <aside class="contact-info-panel">
      <div>
        <div class="badge">
          <span class="badge-dot"></span>
          24/7 Availability
        </div>

        <div class="info-header">
          <h1>Let's start a conversation</h1>
          <p>Have a question, proposal, or project in mind? Fill out the form or reach out to us directly through any of our channels.</p>
        </div>

        <div class="contact-cards">
          <!-- Email -->
          <div class="contact-card-item">
            <div class="icon-box" aria-hidden="true">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
            </div>
            <div class="card-content">
              <h3>Direct Email</h3>
              <p><a href="mailto:support@example.com">support@example.com</a></p>
            </div>
          </div>

          <!-- Phone -->
          <div class="contact-card-item">
            <div class="icon-box" aria-hidden="true">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
              </svg>
            </div>
            <div class="card-content">
              <h3>Call Us</h3>
              <p><a href="tel:+15551234567">+1 (555) 123-4567</a></p>
            </div>
          </div>

          <!-- Location -->
          <div class="contact-card-item">
            <div class="icon-box" aria-hidden="true">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
            </div>
            <div class="card-content">
              <h3>Headquarters</h3>
              <p>100 Innovation Way, Suite 400<br>San Francisco, CA 94107</p>
            </div>
          </div>
        </div>
      </div>

      <div class="response-guarantee">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
        </svg>
        <span>Guaranteed response within <strong>24 business hours</strong></span>
      </div>
    </aside>

    <!-- Right Column: Contact Form -->
    <section class="contact-form-panel">
      <div class="form-header">
        <h2>Send Us a Message</h2>
        <p>Fill out the details below and our team will get back to you shortly.</p>
      </div>

      <!-- Feedback Alerts -->
      <?php if ($success): ?>
        <div class="alert alert-success" role="alert">
          <svg class="alert-icon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <div>
            <strong>Thank you! Your message has been sent successfully.</strong>
            <p style="margin-top: 4px; font-size: 0.88rem; opacity: 0.9;">We have received your inquiry and our team will review it as soon as possible.</p>
          </div>
        </div>
      <?php endif; ?>

      <?php if (!empty($errors['csrf'])): ?>
        <div class="alert alert-error" role="alert">
          <svg class="alert-icon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <div>
            <strong>Error:</strong> <?php echo htmlspecialchars($errors['csrf']); ?>
          </div>
        </div>
      <?php endif; ?>

      <form action="contact.php" method="POST" class="contact-form" novalidate id="contactForm">
        <!-- Hidden CSRF Token -->
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

        <!-- Honeypot field (hidden from genuine users to trap spam bots) -->
        <div class="hidden-field" aria-hidden="true">
          <label for="website">Website (Leave blank)</label>
          <input type="text" name="website" id="website" tabindex="-1" autocomplete="off">
        </div>

        <!-- Name & Email Row -->
        <div class="form-row">
          <!-- Full Name -->
          <div class="form-group">
            <label for="name" class="form-label">
              <span>Your Name</span>
              <span class="required" aria-hidden="true">*</span>
            </label>
            <div class="input-container">
              <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
              <input 
                type="text" 
                name="name" 
                id="name" 
                class="form-control <?php echo isset($errors['name']) ? 'has-error' : ''; ?>" 
                placeholder="Jane Doe" 
                value="<?php echo htmlspecialchars($name); ?>" 
                required 
                autocomplete="name"
              >
            </div>
            <?php if (isset($errors['name'])): ?>
              <span class="field-error"><?php echo htmlspecialchars($errors['name']); ?></span>
            <?php endif; ?>
          </div>

          <!-- Email Address -->
          <div class="form-group">
            <label for="email" class="form-label">
              <span>Email Address</span>
              <span class="required" aria-hidden="true">*</span>
            </label>
            <div class="input-container">
              <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
              </svg>
              <input 
                type="email" 
                name="email" 
                id="email" 
                class="form-control <?php echo isset($errors['email']) ? 'has-error' : ''; ?>" 
                placeholder="jane@example.com" 
                value="<?php echo htmlspecialchars($email); ?>" 
                required 
                autocomplete="email"
              >
            </div>
            <?php if (isset($errors['email'])): ?>
              <span class="field-error"><?php echo htmlspecialchars($errors['email']); ?></span>
            <?php endif; ?>
          </div>
        </div>

        <!-- Subject -->
        <div class="form-group">
          <label for="subject" class="form-label">
            <span>Subject</span>
            <span class="required" aria-hidden="true">*</span>
          </label>
          <div class="input-container">
            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
            </svg>
            <select 
              name="subject" 
              id="subject" 
              class="form-control <?php echo isset($errors['subject']) ? 'has-error' : ''; ?>" 
              required
            >
              <option value="" disabled <?php echo empty($subject) ? 'selected' : ''; ?>>Select a topic...</option>
              <option value="General Inquiry" <?php echo $subject === 'General Inquiry' ? 'selected' : ''; ?>>General Inquiry</option>
              <option value="Sales & Pricing" <?php echo $subject === 'Sales & Pricing' ? 'selected' : ''; ?>>Sales & Pricing</option>
              <option value="Technical Support" <?php echo $subject === 'Technical Support' ? 'selected' : ''; ?>>Technical Support</option>
              <option value="Project Collaboration" <?php echo $subject === 'Project Collaboration' ? 'selected' : ''; ?>>Project Collaboration</option>
              <option value="Feedback / Other" <?php echo $subject === 'Feedback / Other' ? 'selected' : ''; ?>>Feedback / Other</option>
            </select>
          </div>
          <?php if (isset($errors['subject'])): ?>
            <span class="field-error"><?php echo htmlspecialchars($errors['subject']); ?></span>
          <?php endif; ?>
        </div>

        <!-- Message Body -->
        <div class="form-group">
          <label for="message" class="form-label">
            <span>Your Message</span>
            <span class="required" aria-hidden="true">*</span>
          </label>
          <textarea 
            name="message" 
            id="message" 
            class="form-control <?php echo isset($errors['message']) ? 'has-error' : ''; ?>" 
            rows="5" 
            placeholder="Tell us about your project, query, or how we can help..." 
            required
          ><?php echo htmlspecialchars($message); ?></textarea>
          <div class="char-counter" id="charCounter">0 characters (minimum 10)</div>
          <?php if (isset($errors['message'])): ?>
            <span class="field-error"><?php echo htmlspecialchars($errors['message']); ?></span>
          <?php endif; ?>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-submit" id="submitBtn">
          <span>Send Message</span>
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
          </svg>
        </button>

        <p class="privacy-notice">
          We respect your privacy. Your information is strictly protected and never shared with third parties.
        </p>
      </form>
    </section>

  </main>

  <script>
    // Live character counter for message textarea
    const messageInput = document.getElementById('message');
    const charCounter = document.getElementById('charCounter');

    function updateCounter() {
      const len = messageInput.value.length;
      charCounter.textContent = `${len} character${len === 1 ? '' : 's'} (minimum 10)`;
      if (len >= 10) {
        charCounter.style.color = '#34d399';
      } else {
        charCounter.style.color = '#64748b';
      }
    }

    if (messageInput) {
      messageInput.addEventListener('input', updateCounter);
      updateCounter();
    }
  </script>
</body>
</html>
