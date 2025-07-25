<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/navbar.php'; ?>
<!-- Tailwind CSS CDN -->
<script src="https://cdn.tailwindcss.com"></script>

<main class="py-10 px-4">
    <div class="container mx-auto max-w-6xl">
        <h1 class="text-3xl font-bold text-gray-800 mb-10 text-center">
            FIND A DOCTOR THAT'S RIGHT FOR YOU
        </h1>

        <!-- Specialty Categories -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-10">
            <div class="specialty-category-card" data-specialty="General Physician">
                <i class="fas fa-stethoscope specialty-category-icon text-blue-500"></i>
                <span class="font-semibold text-gray-700">General Physician</span>
            </div>
            <div class="specialty-category-card" data-specialty="Pediatrician">
                <i class="fas fa-baby specialty-category-icon text-pink-500"></i>
                <span class="font-semibold text-gray-700">Pediatrician</span>
            </div>
            <div class="specialty-category-card" data-specialty="Dermatologist">
                <i class="fas fa-hand-sparkles specialty-category-icon text-yellow-500"></i>
                <span class="font-semibold text-gray-700">Dermatologist</span>
            </div>
            <div class="specialty-category-card" data-specialty="Dentist">
                <i class="fas fa-tooth specialty-category-icon text-green-500"></i>
                <span class="font-semibold text-gray-700">Dentist</span>
            </div>
        </div>

        <!-- Show All Button -->
        <div class="text-center mb-8">
            <button id="showAllDoctorsBtn" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded-full">
                Show All Doctors
            </button>
        </div>

        <!-- Doctors Grid -->
        <div id="doctorsGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <!-- JS Injected Doctor Cards -->
        </div>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const specialtyCards = document.querySelectorAll('.specialty-category-card');
        const showAllBtn = document.getElementById('showAllDoctorsBtn');
        const doctorsGrid = document.getElementById('doctorsGrid');
        const allDoctors = <?php echo json_encode($data['doctors'] ?? []); ?>;

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
                const card = document.createElement('div');
                card.className = 'doctor-card';

                const imagePath = '<?= URLROOT ?>/' + doc.profile_image;

                card.innerHTML = `
                    <div class="doctor-image-placeholder">
                        <img src="${imagePath}" alt="Doctor Image" class="w-24 h-24 object-cover" />
                    </div>


                    <h3 class="text-xl font-semibold text-gray-800">Dr. ${doc.name}</h3>
                    <p class="text-gray-600">${doc.specialty}</p>
                    <p class="text-sm text-gray-500">${doc.experience ?? 'Experience N/A'} Years Experience</p>
                    <button class="view-profile-btn" data-doctor-id="${doc.email}">
                        View Profile
                    </button>
                `;
                doctorsGrid.appendChild(card);
            });


            // View Profile Button Click Handler
            document.querySelectorAll('.view-profile-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.dataset.doctorId;
                    window.location.href = `<?php echo URLROOT; ?>/pages/doctorprofile/${id}`;
                });
            });
        }

        specialtyCards.forEach(card => {
            card.addEventListener('click', () => {
                renderDoctors(card.dataset.specialty);
            });
        });

        showAllBtn.addEventListener('click', () => renderDoctors());

        // Initial render
        renderDoctors();
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
</body>
</html>
