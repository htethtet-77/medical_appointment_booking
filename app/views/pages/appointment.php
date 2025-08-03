<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        /* Custom styles to enhance visibility and layout */
        .appointment-card {
            transition: all 0.3s ease;
            cursor: pointer;
            border-left: 5px solid #0C969C; /* Added a colored border */
        }
        .appointment-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .appointment-card img {
            border-radius: 8px; /* Rounded corners for the image */
        }
        .add-appointment-btn {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #0C969C;
            color: white;
            font-size: 2.5rem;
            border-radius: 50%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            z-index: 100;
        }
        .add-appointment-btn:hover {
            transform: scale(1.1);
            background-color: #0a7c80;
        }
    </style>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/appointment.css">
</head>
<body class="bg-gray-50 antialiased">
    <?php require APPROOT . '/views/inc/navbar.php'; ?>
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

    <main class="py-10 px-4 min-h-[calc(100vh-160px)]">
        <div class="container mx-auto max-w-4xl">
            <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">My Appointments </h1>
            
           <div id="userIdDisplay" class="text-sm text-gray-600 mb-4 text-left">
            User: <span id="currentUserId" class="font-bold">
                <?= htmlspecialchars($user['name'] ?? 'Guest') ?>
            </span>
        </div>

            <div id="appointmentsList" class="space-y-6">
                <?php if (!empty($data['appointments']) && is_array($data['appointments'])): ?>
                    <?php foreach ($data['appointments'] as $appointment): ?>
                        <div class="appointment-card flex flex-col sm:flex-row gap-4 items-center bg-white shadow-lg rounded-lg p-6">
                            <div class="flex-shrink-0 w-32 h-32 bg-gray-100 rounded-lg flex items-center justify-center shadow-sm overflow-hidden">
                                <?php if (!empty($appointment['doctor_profile_image'])): ?>
                                    <img src="/<?= htmlspecialchars($appointment['doctor_profile_image']) ?>" 
                                         class="w-full h-full object-cover" 
                                         alt="Doctor's Profile Image">
                                <?php else: ?>
                                    <i class="fa-solid fa-user-doctor text-gray-400 text-6xl"></i>
                                <?php endif; ?>
                            </div>

                            <div class="flex-grow text-center sm:text-left">
                                <h3 class="text-2xl font-bold text-gray-900">
                                    Dr. <?= htmlspecialchars($appointment['doctor_name'] ?? 'N/A') ?>
                                </h3>
                                <p class="text-gray-600 font-medium"><?= htmlspecialchars($appointment['specialty'] ?? 'General Practitioner') ?></p>
                                <div class="mt-4 space-y-1 text-gray-700">
                                    <p>
                                        <i class="fa-regular fa-calendar-alt mr-2 text-[#0C969C]"></i>
                                        **Appointment Date:** <?= !empty($appointment['appointment_date']) ? date("F j, Y", strtotime($appointment['appointment_date'])) : 'N/A' ?>
                                    </p>
                                    <p>
                                        <i class="fa-regular fa-clock mr-2 text-[#0C969C]"></i>
                                        **Appointment Time:** <?= !empty($appointment['appointment_time']) ? date("h:i A", strtotime($appointment['appointment_time'])) : 'N/A' ?>
                                    </p>
                                    <p>
                                        <i class="fa-solid fa-coins mr-2 text-[#0C969C]"></i>
                                        **Consultation Fee:** <span class="font-bold text-[#0C969C]">
                                            $<?= htmlspecialchars($appointment['fee'] ?? '0') ?>
                                        </span>
                                    </p>
                                    <!-- <p class="text-sm text-gray-500 italic mt-2">
                                        Reason: <?= htmlspecialchars($appointment['reason'] ?? 'Not specified') ?>
                                    </p> -->
                                </div>
                            </div>
                            
                            <div class="flex-shrink-0 flex flex-col items-center sm:items-end space-y-3 mt-4 sm:mt-0">
                                <?php
                                $status = $appointment['status_name'] ?? 'Unknown';
                                $badgeColors = [
                                    'Pending'   => 'bg-yellow-100 text-yellow-700',
                                    'Confirmed' => 'bg-green-100 text-green-700',
                                    'Cancelled' => 'bg-red-100 text-red-700',
                                    'Completed' => 'bg-blue-100 text-blue-700',
                                ];
                                $colorClass = $badgeColors[$status] ?? 'bg-gray-100 text-gray-700';
                                ?>
                                <span class="text-lg font-semibold px-4 py-1.5 rounded-full <?= $colorClass ?> min-w-[120px] text-center">
                                    <?= htmlspecialchars($status) ?>
                                </span>

                                <form action="<?= URLROOT ?>/appointment/delete/<?= htmlspecialchars($appointment['appointment_id'] ?? '') ?>" method="POST" ;>
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-medium text-sm transition-colors duration-200">
                                        <i class="fa-solid fa-trash-alt mr-1"></i> Cancel appointment
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="bg-white shadow-lg rounded-lg p-8 text-center">
                        <p class="text-gray-600 text-lg">You have no appointments yet. Start by booking one today!</p>
                        <a href="<?= URLROOT; ?>/patient/doctors" class="mt-4 inline-block bg-[#0C969C] text-white py-2 px-6 rounded-lg font-bold hover:bg-[#0a7c80] transition-colors duration-200">
                            Find a Doctor
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <a href="<?= URLROOT; ?>/patient/doctors" class="add-appointment-btn" aria-label="Book a new appointment">
                <i class="fa-solid fa-plus"></i>
            </a>
        </div>
    </main>

    <?php require APPROOT . '/views/inc/footer.php'; ?>
</body>
</html>