<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/navbar.php'; ?>
<!-- Tailwind CSS CDN -->
<script src="https://cdn.tailwindcss.com"></script>
 <?php
       
        if (isset($_SESSION['error'])) {
            echo '
            <div class="max-w-lg mx-auto mt-6 flex items-center justify-between p-4 border border-red-300 text-red-700 bg-red-100 rounded-lg shadow-md transition transform hover:scale-[1.01]">
                <span class="font-medium">'.htmlspecialchars($_SESSION['error']).'</span>
                <button onclick="this.parentElement.remove();" 
                    class="text-red-700 hover:text-red-900 font-bold text-xl leading-none">&times;</button>
            </div>';
            unset($_SESSION['error']);
        }

        if (isset($_SESSION['success'])) {
            echo '
            <div class="max-w-lg mx-auto mt-6 flex items-center justify-between p-4 border border-green-300 text-green-700 bg-green-100 rounded-lg shadow-md transition transform hover:scale-[1.01]">
                <span class="font-medium">'.htmlspecialchars($_SESSION['success']).'</span>
                <button onclick="this.parentElement.remove();" 
                    class="text-green-700 hover:text-green-900 font-bold text-xl leading-none">&times;</button>
            </div>';
            unset($_SESSION['success']);
        }
        ?>
<main class="py-10 px-4">
    <div class="container mx-auto max-w-6xl">
        <h1 class="text-3xl font-bold text-gray-800 mb-10 text-center">
            FIND A DOCTOR THAT'S RIGHT FOR YOU
        </h1>

        <!-- Specialty Categories -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-10">
            <div class="specialty-category-card" data-specialty="General Physician">
                <i class="fas fa-stethoscope specialty-category-icon text-500"></i>
                <span class="font-semibold text-gray-700">General Physician</span>
            </div>
            <div class="specialty-category-card" data-specialty="Pediatrician">
                <i class="fas fa-baby specialty-category-icon text-500"></i>
                <span class="font-semibold text-gray-700">Pediatrician</span>
            </div>
            <div class="specialty-category-card" data-specialty="Dermatologist">
                <i class="fas fa-hand-sparkles specialty-category-icon text-500"></i>
                <span class="font-semibold text-gray-700">Dermatologist</span>
            </div>
            <div class="specialty-category-card" data-specialty="Dentist">
                <i class="fas fa-tooth specialty-category-icon text-500"></i>
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
        console.log("Doctors Data:", allDoctors);


        function renderDoctors(filter = null) {
            doctorsGrid.innerHTML = '';

            const doctorsToShow = filter
                ? allDoctors.filter(doc => doc.specialty === filter)
                : allDoctors;

            if (doctorsToShow.length === 0) {
                doctorsGrid.innerHTML = `<p class="col-span-full text-center text-gray-500">No doctors found for this specialty.</p>`;
                return;
            }
            function formatTime(timeStr) {
                if (!timeStr) return '';
                const date = new Date("1970-01-01T" + timeStr);
                return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            }

            doctorsToShow.forEach(doc => {
                const card = document.createElement('div');
                card.className = 'doctor-card';

                const imagePath = '<?= URLROOT ?>/' + doc.profile_image;
                const startTime = doc.start_time ? formatTime(doc.start_time) : '';
                const endTime = doc.end_time ? formatTime(doc.end_time) : '';
                card.innerHTML = `
                    <div class="doctor-image-placeholder">
                        <img src="${imagePath}" alt="Doctor Image" class="w-24 h-24 object-cover" />
                    </div>

                    <h3 class="text-xl font-semibold text-gray-800">Dr. ${doc.name}</h3>
                    <p class="text-green-600">${doc.specialty}</p>
                    <p class="text-gray-600">${doc.degree}</p>
                    <p class="text-sm text-gray-500">${doc.experience ?? 'Experience N/A'} Years Experience</p>
                    <p class="text-sm text-blue-700 bg-blue-100 p-2 rounded font-medium">${startTime} - ${endTime}</p>
                    <button class="view-profile-btn" data-doctor-id="${doc.user_id}">
                        View Profile
                    </button>
                `;
                

                doctorsGrid.appendChild(card);
            });



            // View Profile Button Click Handler
            document.querySelectorAll('.view-profile-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.dataset.doctorId;
                    const encodedId = btoa(id); // encode
                    window.location.href = `<?php echo URLROOT; ?>/patient/doctorprofile/${encodedId}`;
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
