 <?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/navbar.php'; ?>
<!-- Tailwind CSS CDN -->
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="antialiased">
    <!-- Main Content Area -->
    <main class="py-10 px-4">
        <div class="container mx-auto max-w-6xl">
            <h1 class="text-3xl font-bold text-gray-800 mb-10 text-center">FIND A DOCTOR THAT'S RIGHT FOR YOU</h1>

            <!-- Specialty Categories Section -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-10">
                <!-- General Medicine -->
                <div class="specialty-category-card" data-specialty="General Medicine">
                    <i class="fas fa-stethoscope specialty-category-icon"></i>
                    <span class="font-semibold text-gray-700">General Medicine</span>
                </div>
                <!-- Pediatrics -->
                <div class="specialty-category-card" data-specialty="Pediatrics">
                    <i class="fas fa-baby specialty-category-icon"></i>
                    <span class="font-semibold text-gray-700">Pediatrics</span>
                </div>
                <!-- Dermatology -->
                <div class="specialty-category-card" data-specialty="Dermatology">
                    <i class="fas fa-hand-sparkles specialty-category-icon"></i>
                    <span class="font-semibold text-gray-700">Dermatology</span>
                </div>
                <!-- Dentistry -->
                <div class="specialty-category-card" data-specialty="Dentistry">
                    <i class="fas fa-tooth specialty-category-icon"></i>
                    <span class="font-semibold text-gray-700">Dentistry</span>
                </div>
            </div>

            <!-- "All Doctors" Button -->
            <div class="text-center mb-8">
                <button id="showAllDoctorsBtn" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded-full transition duration-300">
                    Show All Doctors
                </button>
            </div>

            <!-- Doctors Grid Section -->
            <div id="doctorsGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <!-- Doctor cards will be dynamically inserted here -->
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const specialtyCategoryCards = document.querySelectorAll('.specialty-category-card');
            const doctorsGrid = document.getElementById('doctorsGrid');
            const showAllDoctorsBtn = document.getElementById('showAllDoctorsBtn');

            // Sample doctor data (in a real app, this would come from a database)
            const allDoctors = [
                { name: "Dr. Daniel", specialty: "General Medicine", fee: 120, experience: "10 years", location: "New York,NY" },
                { name: "Dr. Sarah Lee", specialty: "Pediatrics", fee: 150, experience: "8 years", location: "Los Angeles,CA" },
                { name: "Dr. Emily White", specialty: "Dermatology", fee: 180, experience: "12 years", location: "Chicago,IL" },
                { name: "Dr. Alex Green", specialty: "Dentistry", fee: 100, experience: "7 years", location: "Houston,TX" },
                { name: "Dr. Michael Brown", specialty: "General Medicine", fee: 130, experience: "9 years", location: "Phoenix,AZ" },
                { name: "Dr. Jessica Taylor", specialty: "Pediatrics", fee: 160, experience: "6 years", location: "Philadelphia,PA" },
                { name: "Dr. David Miller", specialty: "Dermatology", fee: 190, experience: "15 years", location: "San Antonio,TX" },
                { name: "Dr. Sophia Clark", specialty: "Dentistry", fee: 110, experience: "5 years", location: "San Diego,CA" },
                { name: "Dr. Robert Johnson", specialty: "General Medicine", fee: 140, experience: "11 years", location: "Dallas,TX" },
            ];

            // Function to render doctor cards based on specialty filter
            function renderDoctors(filterSpecialty = null) {
                doctorsGrid.innerHTML = ''; // Clear current doctors

                const filteredDoctors = filterSpecialty
                    ? allDoctors.filter(doctor => doctor.specialty === filterSpecialty)
                    : allDoctors;

                if (filteredDoctors.length === 0) {
                    doctorsGrid.innerHTML = '<p class="col-span-full text-center text-gray-500 mt-8">No doctors found for this specialty.</p>';
                    return;
                }

                filteredDoctors.forEach(doctor => {
                    const doctorCard = document.createElement('div');
                    doctorCard.classList.add('doctor-card');
                    doctorCard.innerHTML = `
                        <div class="doctor-image-placeholder">
                            <i class="fas fa-image"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Dr. ${doctor.name.replace('Dr. ', '')}</h3>
                        <p class="text-gray-600">${doctor.specialty}</p>
                        <button class="view-profile-btn" data-doctor-name="Dr. ${doctor.name.replace('Dr. ', '')}" data-specialty="${doctor.specialty}">View Profile</button>
                    `;
                    doctorsGrid.appendChild(doctorCard);
                });

                // Re-attach event listeners to newly rendered buttons
                attachViewProfileListeners();
            }

            // Function to attach event listeners to "View Profile" buttons
            function attachViewProfileListeners() {
                const viewProfileButtons = document.querySelectorAll('.view-profile-btn');
                viewProfileButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const doctorName = this.dataset.doctorName;
                        const doctorSpecialty = this.dataset.specialty;
                        // Pass other doctor details if needed
                        window.location.href = `doctorprofile.html?name=${encodeURIComponent(doctorName)}&specialty=${encodeURIComponent(doctorSpecialty)}`;
                    });
                });
            }

            // Event listeners for specialty category cards
            specialtyCategoryCards.forEach(card => {
                card.addEventListener('click', function() {
                    const specialty = this.dataset.specialty;
                    renderDoctors(specialty);
                });
            });

            // Event listener for "Show All Doctors" button
            showAllDoctorsBtn.addEventListener('click', function() {
                renderDoctors(); // Call without filter to show all
            });

            // Initial render of all doctors on page load
            renderDoctors();
        });
    </script>
    <?php require APPROOT . '/views/inc/footer.php'; ?>
</body>
</html>
