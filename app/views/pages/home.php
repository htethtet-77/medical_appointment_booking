<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/navbar.php'; ?>

<!-- Tailwind CSS CDN -->
<script src="https://cdn.tailwindcss.com"></script>


<!-- Hero Section -->
<section class="relative bg-cover bg-center py-32" style="background-image: url('<?= URLROOT ?>/images/app.jpg');">
    <div class="absolute inset-0 bg-gradient-to-r from-black/70 to-black/10"></div>

    <div class="relative max-w-6xl mx-auto px-1 text-left text-white">
        <div class="flex flex-col gap-4">
            <!-- Small badge / kicker -->
            <div class="text-sm md:text-base font-medium text-gray-200 flex items-center gap-2">
                <i class="fas fa-heart-pulse text-green-400"></i>
                    Your Health, Our Priority

            </div>

            <!-- Real-world headline (short + strong) -->
            <h1 class="text-2xl md:text-4xl font-bold leading-snug">
                Book Your Appointment Online
            </h1>

            <!-- Supporting copy (concise, scannable) -->
            <p class="text-base md:text-lg max-w-xl text-gray-100">
                Skip the waiting room. Find your doctor and book appointments instantly.
                It's Fast, Easy, Simple.
            </p>

            <?php
            $url = isset($_SESSION['current_user']) ? URLROOT . '/patient/doctors' : URLROOT . '/pages/register';
            ?>

            <!-- Primary CTA (compact) + Secondary link -->
            <div class="mt-2 flex items-center gap-3">
                <a href="<?= $url ?>"
                   class="inline-flex items-center px-6 py-2.5 bg-[#0C969C] text-white font-semibold rounded-full shadow hover:bg-opacity-90 transition">
                    <i class="fas fa-calendar-plus mr-2"></i>
                    Appointment Now
                </a>

             
            </div>

            <!-- Trust line (optional, subtle) -->
            <p class="text-xs md:text-sm text-gray-200/90 mt-2">
                Secure bookings • 
            </p>
        </div>
    </div>
</section>


<!-- Specialties Section -->
<section class="py-10 px-4 bg-white">
    <div class="max-w-6xl mx-auto text-center">
        <h2 class="text-3xl font-bold text-gray-800 mb-10">Categories of Specialities</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-10">
            
            <a href="<?= URLROOT ?>/patient/doctors?specialty=General Physician" 
               class="specialty-category-card block bg-white rounded-xl shadow p-6 flex flex-col items-center justify-center cursor-pointer hover:shadow-lg">
                <i class="fas fa-stethoscope specialty-category-icon text-[#6BA3BE] text-4xl mb-2"></i>
                <span class="font-semibold text-gray-700">General Physician</span>
            </a>

            <a href="<?= URLROOT ?>/patient/doctors?specialty=Pediatrician" 
               class="specialty-category-card block bg-white rounded-xl shadow p-6 flex flex-col items-center justify-center cursor-pointer hover:shadow-lg">
                <i class="fas fa-baby specialty-category-icon text-[#6BA3BE] text-4xl mb-2"></i>
                <span class="font-semibold text-gray-700">Pediatrician</span>
            </a>

            <a href="<?= URLROOT ?>/patient/doctors?specialty=Dermatologist" 
               class="specialty-category-card block bg-white rounded-xl shadow p-6 flex flex-col items-center justify-center cursor-pointer hover:shadow-lg">
                <i class="fas fa-hand-sparkles specialty-category-icon text-[#6BA3BE] text-4xl mb-2"></i>
                <span class="font-semibold text-gray-700">Dermatologist</span>
            </a>

            <a href="<?= URLROOT ?>/patient/doctors?specialty=Dentist" 
               class="specialty-category-card block bg-white rounded-xl shadow p-6 flex flex-col items-center justify-center cursor-pointer hover:shadow-lg">
                <i class="fas fa-tooth specialty-category-icon text-[#6BA3BE] text-4xl mb-2"></i>
                <span class="font-semibold text-gray-700">Dentist</span>
            </a>

        </div>
    </div>
</section>




<!-- Doctors Section -->
<!-- <section class="py-16 px-4 bg-gray-50">
    <div class="max-w-6xl mx-auto">
        <h2 class="text-3xl font-bold text-gray-800 mb-10 text-center">Find a Doctor</h2> -->

        <!-- Show All Button -->
        <!-- <div class="text-center mb-8">
            <button id="showAllDoctorsBtn" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded-full">
                
            </button>
        </div> -->

        <!-- <div id="doctorsGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6"> -->
            <!-- JS Injected Doctor Cards -->
        <!-- </div>
        <div class="see-more-button-container">
                <a href="<?php echo URLROOT;?>/patient/doctors" class="see-more-button">
                    See More <span class="">&rarr;</span>
                </a>
            </div>
    </div>
    
</section> -->
<section class="py-16 px-4 bg-gray-50">
    <div class="max-w-6xl mx-auto">
        <h2 class="text-3xl font-bold text-gray-800 mb-10 text-center">Find a Doctor</h2>

        <div id="doctorsGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <!-- JS Injected Doctor Cards -->
        </div>

       <div class="see-more-button-container mt-6 flex justify-end">
    <a href="<?= URLROOT; ?>/patient/doctors" 
       class="see-more-button px-3 py-2 bg-[#0C969C] text-white font-semibold rounded hover:bg-[#0A7B80] transition">
        See More <span>&rarr;</span>
    </a>
</div>


    </div>
</section>
<!-- How It Works Section -->
<section class="py-16 bg-gray-100">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="text-3xl font-bold text-gray-800 mb-12 text-center">How does it Works?</h2>
        <div class="flex flex-wrap justify-center gap-6">
            <div class="step bg-white rounded-xl p-6 flex flex-col items-start shadow hover:shadow-lg transition w-56">
                <i class="fas fa-user-plus text-2xl text-[#6BA3BE] mb-4"></i>
                <p class="font-medium text-gray-700">Register or Login</p>
            </div>
            <div class="step bg-white rounded-xl p-6 flex flex-col items-start shadow hover:shadow-lg transition w-56">
                <i class="fas fa-user-md text-2xl text-[#6BA3BE] mb-4"></i>
                <p class="font-medium text-gray-700">Choose Your Doctor</p>
            </div>
            <div class="step bg-white rounded-xl p-6 flex flex-col items-start shadow hover:shadow-lg transition w-56">
                <i class="fas fa-calendar-check text-2xl text-[#6BA3BE] mb-4"></i>
                <p class="font-medium text-gray-700">Book Appointment</p>
            </div>
            <div class="step bg-white rounded-xl p-6 flex flex-col items-start shadow hover:shadow-lg transition w-56">
                <i class="fas fa-hand-holding-medical text-2xl text-[#6BA3BE] mb-4"></i>
                <p class="font-medium text-gray-700">Get Treated</p>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const specialtyCards = document.querySelectorAll('.specialty-category-card');
    const doctorsGrid = document.getElementById('doctorsGrid');
    const allDoctors = <?php echo json_encode($data['doctors'] ?? []); ?>;

    function formatTime(timeStr) {
        if (!timeStr) return '';
        const date = new Date("1970-01-01T" + timeStr);
        return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    function renderDoctors(filter = null) {
        doctorsGrid.innerHTML = '';

        const doctorsToShow = filter
            ? allDoctors.filter(doc => doc.specialty === filter)
            : allDoctors;

        if (doctorsToShow.length === 0) {
            doctorsGrid.innerHTML = `<p class="col-span-full text-center text-gray-500">No doctors found for this specialty.</p>`;
            return;
        }

        doctorsToShow.forEach(doc => {
            const imagePath = doc.profile_image ? '<?= URLROOT ?>/' + doc.profile_image : '<?= URLROOT ?>/images/default_profile.jpg';
            const startTime = doc.start_time ? formatTime(doc.start_time) : '';
            const endTime = doc.end_time ? formatTime(doc.end_time) : '';

            const card = document.createElement('div');
            card.className = 'doctor-card bg-white rounded-xl p-6 flex flex-col items-center text-center shadow hover:shadow-lg transition';
            card.innerHTML = `
                <img src="${imagePath}" alt="Dr. ${doc.name}" class="w-24 h-24 object-cover mb-4 rounded-lg">
                <h3 class="text-xl font-semibold text-gray-800">Dr. ${doc.name}</h3>
                <p class="text-gray-600 mt-2">${doc.specialty}</p>
                <p class="text-gray-600 mt-2">${doc.degree ?? ''}</p>
                <p class="text-sm text-gray-500 mt-2">${doc.experience ?? 'Experience N/A'} Years Experience</p>
                <p class="text-sm text-blue-700 bg-blue-100 px-2 py-1 rounded font-medium mt-2">${startTime} - ${endTime}</p>
                <button class="view-profile-btn mt-4 px-4 py-2 bg-[#0C969C] text-white rounded-full font-medium">View Profile</button>
            `;
            doctorsGrid.appendChild(card);

            card.querySelector('.view-profile-btn').addEventListener('click', function() {
                const encodedId = btoa(doc.user_id);
                window.location.href = `<?= URLROOT ?>/patient/doctorprofile/${encodedId}`;
            });
        });
    }

    // Filter by specialty
    specialtyCards.forEach(card => {
        card.addEventListener('click', () => renderDoctors(card.dataset.specialty));
    });

    // Initial render (all doctors)
    renderDoctors();
});
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
