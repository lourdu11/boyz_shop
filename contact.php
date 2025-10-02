<?php
include 'config.php';
include 'header.php';

// Handle contact form submission
$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    
    // Basic validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        // In a real application, you would send an email or save to database
        // For now, we'll just show a success message
        $success = true;
        
        // You could add code here to send an email:
        // mail('info@magizhchigarments.com', $subject, $message, "From: $email");
    }
}
?>

<!-- Contact Section -->
<section class="contact-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h2 class="text-center mb-5">Contact Us</h2>
                
                <?php if($success): ?>
                    <div class="alert alert-success text-center">
                        <h4>Thank You!</h4>
                        <p>Your message has been sent successfully. We'll get back to you soon.</p>
                        <a href="index.php" class="btn btn-primary">Return to Home</a>
                    </div>
                <?php else: ?>
                    <?php if($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <div class="card shadow">
                        <div class="card-body p-5">
                            <form method="POST">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Full Name *</label>
                                        <input type="text" class="form-control" id="name" name="name" required value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email Address *</label>
                                        <input type="email" class="form-control" id="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="subject" class="form-label">Subject *</label>
                                    <input type="text" class="form-control" id="subject" name="subject" required value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="message" class="form-label">Message *</label>
                                    <textarea class="form-control" id="message" name="message" rows="5" required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-lg">Send Message</button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Contact Information -->
        <div class="row mt-5">
            <div class="col-md-4 text-center mb-4">
                <div class="contact-info p-4">
                    <i class="fas fa-map-marker-alt fa-2x text-primary mb-3"></i>
                    <h5>Our Location</h5>
                    <p>123 Fashion Street<br>Style City, SC 12345</p>
                </div>
            </div>
            <div class="col-md-4 text-center mb-4">
                <div class="contact-info p-4">
                    <i class="fas fa-phone fa-2x text-primary mb-3"></i>
                    <h5>Phone Number</h5>
                    <p>+1 (234) 567-8900<br>+1 (234) 567-8901</p>
                </div>
            </div>
            <div class="col-md-4 text-center mb-4">
                <div class="contact-info p-4">
                    <i class="fas fa-envelope fa-2x text-primary mb-3"></i>
                    <h5>Email Address</h5>
                    <p>info@magizhchigarments.com<br>support@magizhchigarments.com</p>
                </div>
            </div>
        </div>
        
        <!-- Store Hours -->
        <div class="row mt-4">
            <div class="col-lg-6 mx-auto">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Store Hours</h5>
                        <div class="row">
                            <div class="col-6">
                                <p class="mb-1">Monday - Friday</p>
                                <p class="mb-1">Saturday</p>
                                <p class="mb-0">Sunday</p>
                            </div>
                            <div class="col-6">
                                <p class="mb-1">9:00 AM - 8:00 PM</p>
                                <p class="mb-1">10:00 AM - 6:00 PM</p>
                                <p class="mb-0">11:00 AM - 5:00 PM</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>