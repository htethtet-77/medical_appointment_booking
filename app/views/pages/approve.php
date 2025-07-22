<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Approval Status - Mediplus</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome CDN for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f8f8; /* Light gray background for the whole page */
        }
        .submit-btn {
            background-color: #0C969C;
            color: white;
            font-weight: 600;
            padding: 12px 32px;
            border-radius: 9999px; /* Fully rounded */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s;
            border: none;
            cursor: pointer;
        }
        .submit-btn:hover {
            background-color: #0A7075; /* Darker shade of teal for hover */
        }
        /* Style for the simulate approval button */
        .simulate-btn {
            background-color: #0C969C; /* Primary teal color */
            color: white;
            font-weight: 600;
            padding: 12px 32px;
            border-radius: 9999px; /* Fully rounded */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s;
            border: none;
            cursor: pointer;
        }
        .simulate-btn:hover {
            background-color: #0A7075; /* Darker shade of teal for hover */
        }
    </style>
</head>
<body class="antialiased">

    <!-- Header Section -->
    <header class="bg-[#0A7075] shadow-md py-4">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <!-- Logo/Site Title -->
            <div class="text-2xl font-bold text-white">Mediplus</div>
            <!-- Navigation Menu for Desktop -->
            <nav class="hidden md:flex space-x-6 text-white font-medium items-center">
                <a href="#" class="hover:text-gray-300 transition duration-300">HOME</a>
                <a href="#" class="hover:text-gray-300 transition duration-300">DOCTORS</a>
                <a href="#" class="hover:text-gray-300 transition duration-300">APPOINTMENT</a>
                <a href="#" class="hover:text-gray-300 transition duration-300">CONTACT</a>
                <a href="#" class="hover:text-gray-300 transition duration-300"><i class="fa-solid fa-user mr-1"></i>LOGIN</a>
            </nav>
            <!-- Mobile Menu Button (Hamburger Icon) - Hidden on desktop -->
            <button class="md:hidden text-white text-2xl focus:outline-none">
                &#9776; <!-- Unicode for hamburger icon -->
            </button>
        </div>
    </header>

    <!-- Doctor Profile Main Section (Visible on both approval states as per screenshot) -->
    <section id="doctorProfileSection" class="bg-gray-200 py-10 px-4">
        <div class="container mx-auto max-w-4xl bg-white rounded-xl shadow-lg p-8 flex flex-col md:flex-row items-center md:items-start gap-8">
            <!-- Doctor Image Container -->
            <div class="flex-shrink-0 w-48 h-48 bg-gray-100 rounded-lg flex items-center justify-center shadow-md">
                <!-- Generic Placeholder Icon -->
                <svg class="w-24 h-24 text-gray-400" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
            </div>

            <!-- Doctor Details -->
            <div class="text-center md:text-left flex-grow">
                <h1 class="text-4xl font-bold text-gray-800 mb-2">Dr. Daniel</h1>
                <p class="text-xl text-gray-600 font-semibold mb-4">General Physician</p>

                <!-- Key Information Badges -->
                <div class="flex flex-col items-center md:items-start space-y-2 text-gray-700 text-lg">
                    <span class="flex items-center">
                        <i class="fa-solid fa-graduation-cap text-black mr-2"></i>MBBS,MD
                    </span>
                    <span class="flex items-center">
                        <i class="fa-solid fa-briefcase text-black mr-2"></i>10 years of Experience
                    </span>
                    <span class="flex items-center">
                        <i class="fa-solid fa-dollar-sign text-black mr-2"></i>Consultation Fee:120$
                    </span>
                    <span class="flex items-center">
                        <i class="fa-solid fa-map-marker-alt text-black mr-2"></i>Location - New York,NY
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Appointment Approval Status Section -->
    <section class="py-10 px-4">
        <div class="container mx-auto max-w-4xl">
            <!-- Waiting for Admin Approval State -->
            <div id="waitingApproval" class="bg-[#6BA3BE] rounded-xl shadow-lg p-8 text-center text-white">
                <i class="fa-solid fa-clock text-6xl mb-6"></i> <!-- Clock icon for waiting -->
                <h2 class="text-3xl font-bold mb-4">Waiting for Admin Approval</h2>
                <p class="text-lg mb-8">
                    Your request is under review. You will be notified once it is approved.
                </p>
                <!-- This button is for demonstration/testing purposes only. In a real app, admin approves from backend. -->
                <button id="simulateApprovalBtn" class="simulate-btn px-10 py-4">
                    Simulate Admin Approval (DEV)
                </button>
            </div>

            <!-- Admin Approval Granted State (Hidden by default) -->
            <div id="approvalGranted" class="hidden bg-[#6BA3BE] rounded-xl shadow-lg p-8 text-center text-white">
                <i class="fa-solid fa-check-circle text-6xl mb-6"></i> <!-- Check icon for granted -->
                <h2 class="text-3xl font-bold mb-4">Admin Approval Granted</h2>
                <p class="text-lg mb-8">
                    Your account has been approved by an admins. The appointment is confirmed.
                </p>
                <!-- Modified button class to use submit-btn directly -->
                <a href="<?php echo URLROOT;?>/pages/home" id="submitApprovedBtn" class="submit-btn px-10 py-4">
                Submit
                </a>
            </div>
        </div>
    </section>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get references to elements
            const simulateApprovalBtn = document.getElementById('simulateApprovalBtn');
            const waitingApprovalDiv = document.getElementById('waitingApproval');
            const approvalGrantedDiv = document.getElementById('approvalGranted');
            const submitApprovedBtn = document.getElementById('submitApprovedBtn'); // Get the submit button for granted state

            // --- Function to switch between approval states ---
            function showApprovalStatus(isApproved) {
                if (isApproved) {
                    waitingApprovalDiv.classList.add('hidden');
                    approvalGrantedDiv.classList.remove('hidden');
                } else {
                    waitingApprovalDiv.classList.remove('hidden');
                    approvalGrantedDiv.classList.add('hidden');
                }
            }

            // --- Event Listeners ---
            // Simulate admin approval button (for testing)
            if (simulateApprovalBtn) {
                simulateApprovalBtn.addEventListener('click', function() {
                    showApprovalStatus(true); // Show "Approval Granted"
                });
            }

            // Handler for the "Submit" button in "Admin Approval Granted" state
            if (submitApprovedBtn) {
                submitApprovedBtn.addEventListener('click', function() {
                    redirect('/pages/home'); // Replace with actual navigation
                    // For now, it will just switch back to "Waiting for Admin Approval" for demonstration
                    showApprovalStatus(false);
                });
            }


            // Initially show "Waiting for Admin Approval"
            showApprovalStatus(false);
        });
    </script>
</body>
</html>
