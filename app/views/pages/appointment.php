<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Appointments - Mediplus</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome CDN for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/appointment.css">
</head>
<body class="antialiased">
          <?php require APPROOT . '/views/inc/navbar.php'; ?>

     <!-- Main Content Section: My Appointments -->
    <main class="py-10 px-4 min-h-[calc(100vh-160px)]"> <!-- Min-height to push footer down -->
        <div class="container mx-auto max-w-4xl">
            <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">My Appointments</h1>

            <!-- Display current user ID for multi-user debugging -->
            <div id="userIdDisplay" class="text-sm text-gray-600 mb-4">
                User ID: <span id="currentUserId" class="font-mono">Loading...</span>
            </div>

            <!-- Appointments List -->
            <div id="appointmentsList" class="space-y-6">
                <!-- Example Appointment Card (will be replaced by dynamic data) -->
                <div class="appointment-card">
                    <!-- Doctor Image Container -->
                    <div class="flex-shrink-0 w-24 h-24 bg-gray-100 rounded-lg flex items-center justify-center shadow-sm">
                        <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                    </div>
                    <!-- Appointment Details -->
                    <div class="flex-grow">
                        <h3 class="text-xl font-semibold text-gray-800">Dr. Daniel</h3>
                        <p class="text-gray-600">General Physician</p>
                        <p class="text-gray-700 mt-2">15 May 2025, 10:00 AM</p>
                        <p class="text-gray-700">Consultation Fee: <span class="font-bold text-[#0C969C]">$120</span></p>
                        <div class="flex space-x-2 mt-3">
                            <button class="text-red-500 hover:text-red-700 font-medium text-sm" onclick="deleteAppointment('example-id')">Delete</button>
                        </div>
                    </div>
                </div>
                <!-- Appointments will be rendered here dynamically -->
            </div>
                    <a href="<?php echo URLROOT; ?>/pages/doctors" class=" add-appointment-btn">+</a>

        </div>
    </main>
    <?php require APPROOT . '/views/inc/footer.php';    ?>
</body>
</html>
