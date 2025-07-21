<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Mediplus</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome CDN for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/contactus.css">
</head>
<body class="antialiased">

            <?php require APPROOT . '/views/inc/navbar.php'; ?>
      

    <!-- Main Content Section: Contact Us -->
    <main class="py-10 px-4">
        <div class="container mx-auto max-w-6xl bg-white rounded-xl shadow-lg p-8 md:p-12">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Contact Us</h1>
            <p class="text-gray-600 text-lg mb-8">Have any questions? Feel free to contact us</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <!-- Contact Form Section -->
                <div class="order-2 md:order-1">
                    <form action="#" method="POST" class="space-y-6">
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
                                <span>+1 234 567 8900</span>
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
                                <span>123 Main Street, NY</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer Section -->
    <section class="py-6 px-4 bg-white mt-10">
        <div class="container mx-auto">
            <!-- Footer Icons and Text Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center mb-4">
                <!-- Secure & Confidential -->
                <div class="flex flex-col items-center">
                    <svg class="w-10 h-10 text-black mb-1" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 10h-1V7c0-2.76-2.24-5-5-5S7 4.24 7 7v3H6c-1.1 0-2 .9-2 2v8c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-8c0-1.1-.9-2-2-2zm-9 0V7c0-1.66 1.34-3 3-3s3 1.34 3 3v3H9zm9 10H6v-8h12v8z"/>
                    </svg>
                    <p class="text-sm font-medium text-black">Secure &</p>
                    <p class="text-sm font-medium text-black -mt-1">Confidential</p>
                </div>
                <!-- 24/7 Appointment Access -->
                <div class="flex flex-col items-center">
                    <svg class="w-10 h-10 text-black mb-1" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm.01 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-.5-13h1V7h-1zm.5 2c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm.5 4h-1V9h-1V6h3v5.5zm-.5 0h1V9h-1zm0 0h1V9h-1V6h3v5.5zM12.5 12.5h-1V7h-1V6h3v6.5z"/>
                    </svg>
                    <p class="text-sm font-medium text-black">24/7</p>
                    <p class="text-sm font-medium text-black -mt-1">Appointment Access</p>
                </div>
                <!-- Multiple Payment Option -->
                <div class="flex flex-col items-center">
                    <svg class="w-10 h-10 text-black mb-1" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
                    </svg>
                    <p class="text-sm font-medium text-black">Multiple</p>
                    <p class="text-sm font-medium text-black -mt-1">Payment Option</p>
                </div>
            </div>

            <!-- Trusted by Users Text -->
            <div class="text-center mb-4">
                <p class="text-sm font-medium text-gray-800">Trusted by 10,000+ users</p>
            </div>

            <!-- Social Media Icons -->
            <div class="flex justify-center space-x-3 mb-4">
                <a href="#" class="text-black hover:opacity-75 transition duration-300">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33V22H12c5.523 0 10-4.477 10-10z" clip-rule="evenodd"></path>
                    </svg>
                </a>
                <a href="#" class="text-black hover:opacity-75 transition duration-300">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8.29 20.251c1.267.768 2.628 1.166 4.072 1.166C17.5 21.417 22 17.5 22 12c0-.28-.013-.556-.039-.83-.162-.976-.395-1.933-.704-2.859A1.002 1.002 0 0019.263 7H20V5h-1.737a1 1 0 00-.974-.83C15.86 4.14 13.9 3 12 3c-4.992 0-9 4.008-9 9 0 1.258.337 2.457.946 3.535l-.039-.039a7.973 7.973 0 00-1.637 1.545A1.002 1.002 0 002.392 20.25a.999.999 0 00.974.83H4v2h2v-2h2.29zM12 21.417c-1.785 0-3.486-.676-4.782-1.895A9 9 0 0012 3c3.21 0 5.992 2.23 6.643 5.344A9.998 9.998 0 0019 12c0 3.204-2.228 5.995-5.343 6.645A9.997 9.997 0 0012 21.417z"></path>
                    </svg>
                </a>
                <a href="#" class="text-black hover:opacity-75 transition duration-300">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21.948 12.001c0-.756-.067-1.503-.192-2.234h-9.593v4.484h5.367c-.232 1.171-.877 2.167-1.783 2.916l4.062 3.167c2.373-2.186 3.74-5.267 3.74-9.333z" fill="#4285F4"></path>
                        <path d="M12.363 22.001c3.238 0 5.952-1.072 7.936-2.916l-4.062-3.167c-1.127.75-2.57 1.198-3.874 1.198-2.977 0-5.502-1.996-6.417-4.671H1.896l-3.955 3.078c1.94 3.844 5.94 6.576 10.395 6.576z" fill="#34A853"></path>
                        <path d="M5.946 14.329c-.21-.63-.326-1.298-.326-1.992s.116-1.362.326-1.992V8.307L1.896 5.23c-.765 1.527-1.202 3.29-1.202 5.171s.437 3.644 1.202 5.171l4.05-3.155z" fill="#FBBC05"></path>
                        <path d="M12.363 2.001c2.115 0 3.992.733 5.485 2.146l3.62-3.52c-1.984-1.844-4.708-2.916-7.936-2.916-4.455 0-8.455 2.732-10.395 6.576l4.05 3.155c.915-2.675 3.44-4.671 6.417-4.671z" fill="#EA4335"></path>
                    </svg>
                </a>
            </div>

            <!-- Copyright Text -->
            <div class="text-center">
                <p class="text-xs text-gray-800">&copy;2025 MediPlus Care. All Rights Reserved.</p>
            </div>
        </div>
    </section>
