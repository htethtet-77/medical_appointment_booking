<link rel="icon" type="image/png" href="<?php echo URLROOT; ?>/images/icons/favicon.ico"/>
<title><?php echo SITENAME; ?></title>
<?php require APPROOT . '/views/inc/header.php'; ?>

<?php require APPROOT . '/views/inc/navbar.php'; ?>
<?php
// Assuming you store login status in $_SESSION['user_id']
$url = isset($_SESSION['current_user']) ? URLROOT . '/patient/doctors' : URLROOT . '/pages/register';
?>
<!-- Add Font Awesome if not already included -->

<body class="antialiased">

    <section class="hero-section">
    <div class="gradient-overlay"></div>
    <div class="hero-content">
        <!-- Add this badge above your title -->
        <div class="hero-badge">
            <i class="fas fa-heart-pulse" style="color: #4ade80;"></i>
            Your Health, Our Priority
        </div>
        
        <h1>Book Your Appointment Online</h1>
        <p>
            Skip the waiting room and find your perfect doctor. <br>Book appointments instantly with verified healthcare professionals. <br>It's Fast, Easy, and Secure.
        </p>
       <a href="<?php echo $url; ?>" class="hero-button">
    <i class="fas fa-calendar-plus"></i>
    Book Appointment Now
</a>
    </div>
</section>
    <section class="specialties-section">
        <div class="home">
            <h2>Categories of Specialities</h2>
            <div class="specialty-grid">
                <div class="specialty-category-card" data-specialty="General Physician">
                    <i class="fas fa-stethoscope specialty-category-icon"></i>
                    <span class="font-semibold text-gray-700">General Physician</span>
                </div>
                <div class="specialty-category-card" data-specialty="Pediatrician">
                    <i class="fas fa-baby specialty-category-icon"></i>
                    <span class="font-semibold text-gray-700">Pediatrician</span>
                </div>
                <div class="specialty-category-card" data-specialty="Dermatologist">
                    <i class="fas fa-hand-sparkles specialty-category-icon"></i>
                    <span class="font-semibold text-gray-700">Dermatologist</span>
                </div>
                <div class="specialty-category-card" data-specialty="Dentist">
                    <i class="fas fa-tooth specialty-category-icon"></i>
                    <span class="font-semibold text-gray-700">Dentist</span>
                </div>
            </div>
        </div>
    </section>

    <section class="doctors-section">
        <div class="home">
            <h2>Featured Doctors or Clinics</h2>
            <div class="doctor-grid">
                <div class="doctor-card">
                    <div class="doctor-image-container">
                        <img src="https://plus.unsplash.com/premium_photo-1661764878654-3d0fc2eefcca?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTd8fGRvY3RvcnxlbnwwfHwwfHx8MA%3D%3D" alt="Dr. Daniel Smith">
                    </div>
                    <h3>Dr. Daniel Smith</h3>
                    <p>General Physician</p>
                    <!-- <p>10 Years Experience</p> -->
                    <!-- <p>12:00 PM - 04:00 PM</p> -->
                  
                </div>
                <div class="doctor-card">
                    <div class="doctor-image-container">
                        <img src="https://plus.unsplash.com/premium_photo-1661580574627-9211124e5c3f?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OXx8ZG9jdG9yfGVufDB8fDB8fHww" alt="Dr. Sarah Johnson">
                    </div>
                    <h3>Dr. Sarah Johnson</h3>
                    <p>Pediatrician</p>
                   
                </div>
                <div class="doctor-card">
                    <div class="doctor-image-container">
                        <img src="https://media.istockphoto.com/id/2158610739/photo/handsome-male-doctor-with-stethoscope-over-neck-working-while-looking-at-camera-in-the.webp?a=1&b=1&s=612x612&w=0&k=20&c=dELGikTuQn2AvwjAywyFQcGIPHt-0R9BUUie-q5dPBc=" alt="Dr. Michael Chen">
                    </div>
                    <h3>Dr. Michael Chen</h3>
                    <p>Pediatrician</p>
                  
                </div>
                <div class="doctor-card">
                    <div class="doctor-image-container">
                        <img src="https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTJ8fGRvY3RvcnxlbnwwfHwwfHx8MA%3D%3D" alt="Dr. Emily Davis">
                    </div>
                    <h3>Dr. Emily Davis</h3>
                    <p>Dermatologist</p>
                   
                </div>
                <div class="doctor-card">
                    <div class="doctor-image-container">
                        <img src="https://plus.unsplash.com/premium_photo-1658506671316-0b293df7c72b?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8ZG9jdG9yfGVufDB8fDB8fHww" alt="Dr. James Wilson">
                    </div>
                    <h3>Dr. James Wilson</h3>
                    <p>Dermatologist</p>
                    
                </div>
                <div class="doctor-card">
                    <div class="doctor-image-container">
                        <img src="https://images.unsplash.com/photo-1638202993928-7267aad84c31?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8ZG9jdG9yfGVufDB8fDB8fHww" alt="Dr. Lisa Brown">
                    </div>
                    <h3>Dr. Lisa Brown</h3>
                    <p>Dentist</p>
                   
                </div>
            </div>
            <div class="see-more-button-container">
                <a href="<?php echo URLROOT;?>/patient/doctors" class="see-more-button">
                    See More <span class="">&rarr;</span>
                </a>
            </div>
        </div>
    </section>



    <?php require APPROOT . '/views/inc/footer.php'; ?>

</body>


</html>