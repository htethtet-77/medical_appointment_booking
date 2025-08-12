<title><?php echo SITENAME; ?></title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome CDN for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/contactus.css">
<?php require APPROOT . '/views/inc/navbar.php'; ?>
      

    <!-- Main Content Section: Contact Us -->
    <main class="py-10 px-4">
        <div class="container mx-auto max-w-6xl bg-white rounded-xl shadow-lg p-8 md:p-12">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Contact Us</h1>
            <p class="text-gray-600 text-lg mb-8">Have any questions? Feel free to contact us</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <!-- Contact Form Section -->
                <div class="order-2 md:order-1">
                    <form action="<?php echo URLROOT; ?>/patient/sendMessage " method="POST" class="space-y-6">
                        <div>
                            <label for="fullName" class="sr-only">Full Name</label>
                            <input type="text" id="fullName" name="fullName" placeholder="Full Name" class="form-input-field">
                        </div>
                        <div>
                            <label for="emailAddress" class="sr-only">Email Address</label>
                            <input type="email" id="emailAddress" name="emailAddress" placeholder="Email Address" class="form-input-field">
                        </div>
                        <div>
                            <label for="subject" class="sr-only">Subject</label>
                            <input type="text" id="subject" name="subject" placeholder="Subject" class="form-input-field">
                        </div>
                        <div>
                            <label for="message" class="sr-only">Message</label>
                            <textarea id="message" name="message" rows="6" placeholder="Message" class="form-input-field resize-y"></textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="send-message-btn">
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Contact Details Section -->
                <div class="order-1 md:order-2">
                    <div class="space-y-8 mt-4 md:mt-0 ml-20">
                        <!-- Phone -->
                        <div class="contact-info-item">
                            <i class="fa-solid fa-phone contact-info-icon"></i>
                            <div class="contact-info-text">
                                <strong>Phone</strong>
                                <span>+959955078926</span>
                            </div>
                        </div>
                        <!-- Email -->
                        <div class="contact-info-item">
                            <i class="fa-solid fa-envelope contact-info-icon"></i>
                            <div class="contact-info-text">
                                <strong>Email</strong>
                                <span>appointment@gmail.com</span>
                            </div>
                        </div>
                        <!-- Address -->
                        <div class="contact-info-item">
                            <i class="fa-solid fa-map-marker-alt contact-info-icon"></i>
                            <div class="contact-info-text">
                                <strong>Address</strong>
                                <span>123 Main Street, Yangon</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

       <?php require APPROOT . '/views/inc/footer.php'; ?>
