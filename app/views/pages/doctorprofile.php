<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dr. Daniel's Profile - Mediplus</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome CDN for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/doctorprofile.css">
</head>
<body class="antialiased">

    <!-- Header Section -->
    <header class="bg-[#0A7075] shadow-md py-4">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <!-- Navigation Menu for Desktop -->
          <?php require APPROOT . '/views/inc/navbar.php'; ?>
            <!-- Mobile Menu Button (Hamburger Icon) - Hidden on desktop -->
            <button class="md:hidden text-white text-2xl focus:outline-none">
                &#9776; <!-- Unicode for hamburger icon -->
            </button>
        </div>
    </header>

    <!-- Doctor Profile Main Section -->
<section id="doctorProfileSection" class="bg-gray-200 py-10 px-4">
    <div class="container mx-auto max-w-4xl bg-white rounded-xl shadow-lg p-8 flex flex-col md:flex-row items-center md:items-start gap-8">
        
        <!-- Doctor Image Container -->
        <div class="flex-shrink-0 w-48 h-48 rounded-lg overflow-hidden shadow-md">
            <img src="<?php echo URLROOT; ?>/images/doctor.jpg" alt="Doctor Image" class="w-full h-full object-cover">
        </div>

        <!-- Doctor Details -->
        <div class="flex-grow text-center md:text-left">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Dr. Daniel</h1>
            <p class="text-lg md:text-xl text-gray-600 font-semibold mb-4">General Physician</p>

            <!-- Key Information Badges -->
            <div class="flex flex-col items-center md:items-start space-y-2 text-gray-700 text-base md:text-lg">
                <span class="flex items-center">
                    <i class="fa-solid fa-graduation-cap text-black mr-2"></i> MBBS, MD
                </span>
                <span class="flex items-center">
                    <i class="fa-solid fa-briefcase text-black mr-2"></i> 10 years of Experience
                </span>
                <span class="flex items-center">
                    <i class="fa-solid fa-dollar-sign text-black mr-2"></i> Consultation Fee: $120
                </span>
                <span class="flex items-center">
                    <i class="fa-solid fa-map-marker-alt text-black mr-2"></i> Location - New York, NY
                </span>
            </div>
        </div>
    </div>
</section>


    <!-- Content Sections: About, Availability, Services (This section will be hidden) -->
    <section id="contentSections" class="py-10 px-4">
        <div class="container mx-auto max-w-4xl grid grid-cols-1 md:grid-cols-2 gap-8">

            <!-- About the Doctor Section -->
            <div class="bg-white shadow-lg rounded-xl p-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">About the Doctor</h2>
                <p class="text-gray-700 leading-relaxed text-base">
                    Dr. Daniel is a trusted general physician with over 10
                    years of experience in diagnosing and managing a wide range of medical conditions. She is committed
                    to providing personalized, preventive, and holistic care to patients of all ages, helping them maintain
                    long-term wellness and manage chronic illnesses effectively.
                </p>
            </div>

            <!-- Availability Section (This will only display the button, the availability times will be hidden) -->
            <div class="bg-white shadow-lg rounded-xl p-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Availability</h2>
                <div class="space-y-2 text-gray-700 text-base mb-6">
                    <p class="flex justify-between"><span>Monday:</span> <span>9:00AM-1PM</span></p>
                    <p class="flex justify-between"><span>Tuesday:</span> <span>9:00AM-1PM</span></p>
                    <p class="flex justify-between"><span>Wednesday:</span> <span>9:00AM-1PM</span></p>
                    <p class="flex justify-between"><span>Thursday:</span> <span>9:00AM-1PM</span></p>
                </div>
                <button id="bookAppointmentBtn" class="w-full bg-[#0C969C] hover:bg-opacity-90 text-white font-semibold py-3 px-8 rounded-full shadow-lg transition duration-300">
                    Book Appointment
                </button>
            </div>

            <!-- Services Section -->
            <div class="bg-white shadow-lg rounded-xl p-6 md:col-span-2">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Services</h2>
                <ul class="list-disc list-inside space-y-2 text-gray-700 text-base">
                    <li>Acne treatment and scar management</li>
                    <li>Anti-aging treatments (Botox, fillers, etc.)</li>
                    <li>Laser treatments</li>
                </ul>
            </div>

        </div>
    </section>

    <!-- Appointment Booking Section (Initially Hidden, now appears below profile) -->
    <section id="appointmentBookingSection" class="hidden bg-gray-200 py-10 px-4">
        <div class="container mx-auto max-w-4xl bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Choose Your Time slot</h2>

            <div class="flex flex-col md:flex-row gap-8">
                <!-- Date Picker Section -->
                <div class="md:w-1/2">
                    <div class="text-sm text-gray-500 mb-1">Date</div> <!-- Added "Date" label -->
                    <div class="flex items-center mb-4 border border-gray-300 rounded-md overflow-hidden">
                        <input type="text" id="selectedDateInput" class="flex-grow border-none focus:ring-0 px-3 py-2 text-lg" value="08/17/2025" readonly>
                        <button id="calendarIcon" class="px-3 py-2 text-gray-600 hover:text-gray-800 transition duration-200">
                            <i class="fa-solid fa-calendar-days text-xl"></i>
                        </button>
                    </div>
                    <div class="text-xs text-gray-400 mb-2">MM/DD/YYYY</div> <!-- Added MM/DD/YYYY label -->


                    <div id="calendarContainer" class="border border-gray-300 rounded-lg p-4">
                        <!-- Month/Year Navigation -->
                        <div class="flex justify-between items-center mb-4">
                            <button id="prevMonth" class="px-2 py-1 rounded-full hover:bg-gray-200 transition duration-200">&lt;</button>
                            <span id="currentMonthYear" class="font-semibold text-lg text-gray-700"></span>
                            <button id="nextMonth" class="px-2 py-1 rounded-full hover:bg-gray-200 transition duration-200">&gt;</button>
                        </div>
                        <!-- Day Headers -->
                        <div class="datepicker-grid font-medium text-center text-gray-500 mb-2">
                            <span>S</span>
                            <span>M</span>
                            <span>T</span>
                            <span>W</span>
                            <span>T</span>
                            <span>F</span>
                            <span>S</span>
                        </div>
                        <!-- Days Grid -->
                        <div id="calendarDays" class="datepicker-grid text-gray-800">
                            <!-- Days will be dynamically generated here by JS -->
                        </div>
                        <!-- Cancel/OK buttons -->
                        <div class="flex justify-end space-x-4 mt-6">
                            <button id="cancelDate" class="text-gray-600 hover:text-gray-800 font-medium px-4 py-2 rounded-md">Cancel</button>
                            <button id="okDate" class="text-[#0C969C] hover:text-[#0A7075] font-medium px-4 py-2 rounded-md">OK</button>
                        </div>
                    </div>
                </div>

                <!-- Time Slots Section -->
                <div class="md:w-1/2">
                    <div id="timeSlotsContainer" class="grid grid-cols-2 gap-4">
                        <!-- Time Slot Buttons (example) -->
                        <button class="time-slot-btn">9:00 AM</button>
                        <button class="time-slot-btn">9:30 AM</button>
                        <button class="time-slot-btn">10:00 AM</button>
                        <button class="time-slot-btn">10:30 AM</button>
                        <button class="time-slot-btn">11:00 AM</button>
                        <button class="time-slot-btn">12:00 PM</button>
                        <button class="time-slot-btn">12:30 PM</button>
                        <button class="time-slot-btn">1:00 PM</button>
                    </div>
                    <button id="submitAppointment" class="w-full submit-btn mt-8">
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Appointment Fee / Confirmation Section (Initially Hidden) -->
    <section id="appointmentFeeSection" class="hidden bg-gray-200 py-10 px-4">
        <div class="container mx-auto max-w-4xl bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Your Appointment Fee</h2>

            <div class="p-6 border border-gray-200 rounded-lg mb-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Appointment Summary</h3>
                <div class="text-gray-700 space-y-1">
                    <p><strong>Dr. Daniel</strong></p>
                    <p>General Physician</p>
                    <p>10 years of Experience</p>
                    <p>Consultation Fee: <span class="font-bold text-lg text-[#0C969C]">$120</span></p>
                    <p id="summaryDateTime"></p> <!-- Placeholder for selected date and time -->
                </div>
            </div>

            <hr class="my-6 border-gray-200">

            <div class="space-y-4">
                <input type="text" placeholder="Full Name" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#0C969C]">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="email" placeholder="Email" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#0C969C]">
                    <input type="tel" placeholder="Phone Number" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#0C969C]">
                </div>
                <input type="text" placeholder="Address" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#0C969C]">
            </div>

            <div class="mt-6 flex items-center space-x-4">
                <span class="text-gray-700 font-medium">Payment Method :</span>
                <button class="kbz-button" id="kbzPayButton1">
                    <img src="https://placehold.co/24x24/d3d3d3/666666?text=KBZ" alt="KBZ Pay">
                    KBZ Pay
                </button>
                <button class="kbz-button" id="kbzPayButton2">
                    <img src="https://placehold.co/24x24/d3d3d3/666666?text=KBZ" alt="KBZ Bank">
                    KBZ Bank
                </button>
                <button class="kbz-button" id="chooseFileButton">
                    Choose file
                </button>
            </div>

           <a href="adminapprove.html"  class="w-full submit-btn mt-8" role="button">
            Confirm
            </a>
        </div>
    </section>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bookAppointmentBtn = document.getElementById('bookAppointmentBtn');
            const doctorProfileSection = document.getElementById('doctorProfileSection'); // Get the doctor profile section
            const contentSections = document.getElementById('contentSections'); // Get the element to hide
            const appointmentBookingSection = document.getElementById('appointmentBookingSection');
            const appointmentFeeSection = document.getElementById('appointmentFeeSection');
            const selectedDateInput = document.getElementById('selectedDateInput');
            const calendarIcon = document.getElementById('calendarIcon');
            const currentMonthYearSpan = document.getElementById('currentMonthYear');
            const prevMonthBtn = document.getElementById('prevMonth');
            const nextMonthBtn = document.getElementById('nextMonth');
            const calendarDaysContainer = document.getElementById('calendarDays');
            const cancelDateBtn = document.getElementById('cancelDate');
            const okDateBtn = document.getElementById('okDate');
            const timeSlotsContainer = document.getElementById('timeSlotsContainer');
            const submitAppointmentBtn = document.getElementById('submitAppointment');
            const summaryDateTime = document.getElementById('summaryDateTime');
            const confirmPaymentBtn = document.getElementById('confirmPaymentBtn');

            let currentMonth = new Date().getMonth();
            let currentYear = new Date().getFullYear();
            let selectedDate = null;
            let selectedTimeSlot = null;

            function renderCalendar(month, year) {
                calendarDaysContainer.innerHTML = '';
                currentMonthYearSpan.innerHTML = `${new Date(year, month).toLocaleString('default', { month: 'short' })} <span class="text-gray-500">${year}</span>`;

                const firstDayOfMonth = new Date(year, month, 1).getDay();
                const daysInMonth = new Date(year, month + 1, 0).getDate();

                for (let i = 0; i < firstDayOfMonth; i++) {
                    const emptyDay = document.createElement('div');
                    emptyDay.classList.add('datepicker-day', 'day-empty');
                    calendarDaysContainer.appendChild(emptyDay);
                }

                for (let day = 1; day <= daysInMonth; day++) {
                    const dayElement = document.createElement('div');
                    dayElement.classList.add('datepicker-day');
                    dayElement.textContent = day;

                    const today = new Date();
                    const currentDayDate = new Date(year, month, day);
                    currentDayDate.setHours(0, 0, 0, 0);
                    today.setHours(0, 0, 0, 0);

                    if (currentDayDate < today) {
                        dayElement.classList.add('day-inactive');
                    } else if (day === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
                        dayElement.classList.add('day-today');
                    }

                    if (selectedDate && day === selectedDate.getDate() && month === selectedDate.getMonth() && year === selectedDate.getFullYear()) {
                        dayElement.classList.add('day-selected');
                    }

                    dayElement.addEventListener('click', function() {
                        if (this.classList.contains('day-inactive')) {
                            return;
                        }
                        const previouslySelected = calendarDaysContainer.querySelector('.day-selected');
                        if (previouslySelected) {
                            previouslySelected.classList.remove('day-selected');
                        }
                        this.classList.add('day-selected');
                        selectedDate = new Date(year, month, parseInt(this.textContent));
                        updateSelectedDateInput();
                    });
                    calendarDaysContainer.appendChild(dayElement);
                }
            }

            function updateSelectedDateInput() {
                if (selectedDate) {
                    const month = (selectedDate.getMonth() + 1).toString().padStart(2, '0');
                    const day = selectedDate.getDate().toString().padStart(2, '0');
                    const year = selectedDate.getFullYear();
                    selectedDateInput.value = `${month}/${day}/${year}`;
                } else {
                    selectedDateInput.value = '';
                }
            }

            prevMonthBtn.addEventListener('click', function() {
                currentMonth--;
                if (currentMonth < 0) {
                    currentMonth = 11;
                    currentYear--;
                }
                renderCalendar(currentMonth, currentYear);
            });

            nextMonthBtn.addEventListener('click', function() {
                currentMonth++;
                if (currentMonth > 11) {
                    currentMonth = 0;
                    currentYear++;
                }
                renderCalendar(currentMonth, currentYear);
            });

            bookAppointmentBtn.addEventListener('click', function() {
                contentSections.classList.add('hidden');
                appointmentBookingSection.classList.remove('hidden');
                appointmentFeeSection.classList.add('hidden'); // Ensure fee section is hidden

                if (!selectedDate) {
                    selectedDate = new Date();
                }
                currentMonth = selectedDate.getMonth();
                currentYear = selectedDate.getFullYear();
                renderCalendar(currentMonth, currentYear);
                updateSelectedDateInput();
            });

            timeSlotsContainer.addEventListener('click', function(event) {
                if (event.target.classList.contains('time-slot-btn')) {
                    const previouslySelected = timeSlotsContainer.querySelector('.time-slot-btn.selected');
                    if (previouslySelected) {
                        previouslySelected.classList.remove('selected');
                    }
                    event.target.classList.add('selected');
                    selectedTimeSlot = event.target.textContent;
                }
            });

            cancelDateBtn.addEventListener('click', function() {
                appointmentBookingSection.classList.add('hidden');
                contentSections.classList.remove('hidden');
                selectedDate = null;
                selectedTimeSlot = null;
                const selectedTimeBtn = timeSlotsContainer.querySelector('.time-slot-btn.selected');
                if (selectedTimeBtn) {
                    selectedTimeBtn.classList.remove('selected');
                }
            });

            okDateBtn.addEventListener('click', function() {
                // No change needed for this button's functionality
            });

            submitAppointmentBtn.addEventListener('click', function() {
                if (selectedDate && selectedTimeSlot) {
                    appointmentBookingSection.classList.add('hidden');
                    doctorProfileSection.classList.add('hidden'); // Hide doctor profile section
                    appointmentFeeSection.classList.remove('hidden');

                    const formattedDate = selectedDate.toLocaleDateString('en-US', { day: '2-digit', month: 'short', year: 'numeric' });
                    summaryDateTime.textContent = `${formattedDate}, ${selectedTimeSlot}`;

                } else {
                    alert('Please select both a date and a time slot.');
                }
            });

            document.getElementById('kbzPayButton1').addEventListener('click', function() {
                document.getElementById('kbzPayButton1').classList.add('selected');
                document.getElementById('kbzPayButton2').classList.remove('selected');
            });

            document.getElementById('kbzPayButton2').addEventListener('click', function() {
                document.getElementById('kbzPayButton2').classList.add('selected');
                document.getElementById('kbzPayButton1').classList.remove('selected');
            });
            
            document.getElementById('chooseFileButton').addEventListener('click', function() {
                alert('Choose file functionality would be implemented here.');
            });


            confirmPaymentBtn.addEventListener('click', function() {
                alert('Payment Confirmed! This is where payment processing would occur.');
                appointmentFeeSection.classList.add('hidden');
                doctorProfileSection.classList.remove('hidden'); // Show doctor profile section again
                contentSections.classList.remove('hidden');
                selectedDate = null;
                selectedTimeSlot = null;
                const selectedTimeBtn = timeSlotsContainer.querySelector('.time-slot-btn.selected');
                if (selectedTimeBtn) {
                    selectedTimeBtn.classList.remove('selected');
                }
            });

            renderCalendar(currentMonth, currentYear);
        });
    </script>
    <?php require APPROOT . '/views/inc/footer.php'; ?>
</body>
</html>
<!-- <div class="profile-card">
    <img src="<?php echo URLROOT; ?>/public/images/<?php echo $data['doctor']->getImage(); ?>" alt="">
    <h2><?php echo $data['doctor']->getName(); ?></h2>
    <p>Specialty: <?php echo $data['doctor']->getSpecialty(); ?></p>
    <p>Experience: <?php echo $data['doctor']->getExperience(); ?> years</p>
    <p>Phone: <?php echo $data['doctor']->getPhone(); ?></p>
    <p><?php echo $data['doctor']->getDescription(); ?></p>
</div> -->
