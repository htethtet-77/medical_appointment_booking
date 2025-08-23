<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
</head>
<body class="bg-gray-50 antialiased flex flex-col min-h-screen">
    <?php require APPROOT . '/views/inc/navbar.php'; ?>
     <main class="flex-grow">
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

   <div class="max-w-4xl mx-auto">

    <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center mt-7">My Appointments</h1>

    <div class="mb-6">
        <!-- Tabs -->
        <ul id="appointmentTabs" class="flex border-b border-gray-300">
            <li class="mr-1">
                <button class="tab-btn bg-white inline-block py-2 px-4 font-semibold text-gray-700 border-b-2 border-transparent hover:text-[#0C969C]" data-tab="upcoming">
                    Upcoming
                </button>
            </li>
            <li class="mr-1">
                <button class="tab-btn bg-white inline-block py-2 px-4 font-semibold text-gray-700 border-b-2 border-transparent hover:text-[#0C969C]" data-tab="past">
                    Past
                </button>
            </li>
            <li class="mr-1">
                <button class="tab-btn bg-white inline-block py-2 px-4 font-semibold text-gray-700 border-b-2 border-transparent hover:text-[#0C969C]" data-tab="cancelled">
                    Cancelled
                </button>
            </li>
        </ul>
    </div>

    <?php
    $appointments = $data['appointments'] ?? [];
    $today = date('Y-m-d');

    $upcomingAppointments = array_filter($appointments, fn($a) => in_array($a['status_name'], ['Pending', 'Confirmed']) && $a['appointment_date'] >= $today);
    $pastAppointments     = array_filter($appointments, fn($a) => ($a['appointment_date'] < $today && $a['status_name'] !== 'Cancelled') || $a['status_name'] === 'Completed');
    $cancelledAppointments= array_filter($appointments, fn($a) => $a['status_name'] === 'Cancelled');

    $badgeColors = [
        'Pending'   => 'bg-yellow-100 text-yellow-700',
        'Confirmed' => 'bg-green-100 text-green-700',
        'Cancelled' => 'bg-red-100 text-red-700',
        'Completed' => 'bg-blue-100 text-blue-700',
    ];
    ?>

    <!-- Upcoming Tab -->
    <div id="upcoming" class="tab-content">
        <?php if($upcomingAppointments): ?>
            <?php foreach($upcomingAppointments as $appointment): ?>
                <div class="appointment-card flex flex-col sm:flex-row gap-4 items-center bg-white shadow-lg rounded-lg p-6 mb-4">
                    <div class="flex-shrink-0 w-32 h-32 bg-gray-100 rounded-lg flex items-center justify-center shadow-sm overflow-hidden">
                        <?php if (!empty($appointment['doctor_profile_image'])): ?>
                            <img src="/<?= htmlspecialchars($appointment['doctor_profile_image']) ?>" class="w-full h-full object-cover" alt="Doctor's Profile Image">
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
                        $colorClass = $badgeColors[$status] ?? 'bg-gray-100 text-gray-700';
                        ?>
                        <span class="text-lg font-semibold px-4 py-1.5 rounded-full <?= $colorClass ?> min-w-[120px] text-center">
                            <?= htmlspecialchars($status) ?>
                        </span>
                        <?php if($status === 'Pending'): ?>
                        <form action="<?= URLROOT ?>/appointment/delete/<?= htmlspecialchars($appointment['appointment_id'] ?? '') ?>" method="POST">
                            <button type="submit" class="text-red-500 hover:text-red-700 font-medium text-sm transition-colors duration-200">
                                <i class="fa-solid fa-trash-alt mr-1"></i> Cancel appointment
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
<div class="bg-white shadow-lg rounded-lg p-8 text-center">
                        <p class="text-gray-600 text-lg">You have no appointments yet. Start by booking one today!</p>
                        <a href="<?= URLROOT; ?>/patient/doctors" class="mt-4 inline-block bg-[#0C969C] text-white py-2 px-6 rounded-lg font-bold hover:bg-[#0a7c80] transition-colors duration-200">
                            Find a Doctor
                        </a>
                    </div>        <?php endif; ?>
    </div>

    <!-- Past Tab -->
    <div id="past" class="tab-content hidden">
        <?php if($pastAppointments): ?>
            <?php foreach($pastAppointments as $appointment): ?>
                <div class="appointment-card flex flex-col sm:flex-row gap-4 items-center bg-gray-50 shadow-lg rounded-lg p-6 mb-4 opacity-80">
                    <div class="flex-shrink-0 w-32 h-32 bg-gray-100 rounded-lg flex items-center justify-center shadow-sm overflow-hidden">
                        <?php if (!empty($appointment['doctor_profile_image'])): ?>
                            <img src="/<?= htmlspecialchars($appointment['doctor_profile_image']) ?>" class="w-full h-full object-cover" alt="Doctor's Profile Image">
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
                        $status = $appointment['status_name'] ?? 'Completed';
                        $colorClass = $badgeColors[$status] ?? 'bg-gray-100 text-gray-700';
                        ?>
                        <span class="text-lg font-semibold px-4 py-1.5 rounded-full <?= $colorClass ?> min-w-[120px] text-center">
                            <?= htmlspecialchars($status) ?>
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
<div class="bg-white shadow-lg rounded-lg p-8 text-center">
                        <p class="text-gray-600 text-lg">You have no appointments yet. Start by booking one today!</p>
                        <a href="<?= URLROOT; ?>/patient/doctors" class="mt-4 inline-block bg-[#0C969C] text-white py-2 px-6 rounded-lg font-bold hover:bg-[#0a7c80] transition-colors duration-200">
                            Find a Doctor
                        </a>
                    </div>        <?php endif; ?>
    </div>

    <!-- Cancelled Tab -->
    <div id="cancelled" class="tab-content hidden">
        <?php if($cancelledAppointments): ?>
            <?php foreach($cancelledAppointments as $appointment): ?>
                <div class="appointment-card flex flex-col sm:flex-row gap-4 items-center bg-red-50 shadow-lg rounded-lg p-6 mb-4 opacity-90">
                    <div class="flex-shrink-0 w-32 h-32 bg-gray-100 rounded-lg flex items-center justify-center shadow-sm overflow-hidden">
                        <?php if (!empty($appointment['doctor_profile_image'])): ?>
                            <img src="/<?= htmlspecialchars($appointment['doctor_profile_image']) ?>" class="w-full h-full object-cover" alt="Doctor's Profile Image">
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
                        <span class="text-lg font-semibold px-4 py-1.5 rounded-full <?= $badgeColors['Cancelled'] ?> min-w-[120px] text-center">
                            Cancelled
                        </span>
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
    
</div>

            <a href="<?= URLROOT; ?>/patient/doctors" class="add-appointment-btn" aria-label="Book a new appointment">
                <i class="fa-solid fa-plus"></i>
            </a>
        </main>
    <?php require APPROOT . '/views/inc/footer.php'; ?>

</body>
</html>

<script>
    const tabs = document.querySelectorAll('.tab-btn');
    const contents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('border-b-2','border-[#0C969C]','text-[#0C969C]'));
            tab.classList.add('border-b-2','border-[#0C969C]','text-[#0C969C]');

            const tabId = tab.dataset.tab;
            contents.forEach(c => c.classList.add('hidden'));
            document.getElementById(tabId).classList.remove('hidden');
        });
    });

    // Activate first tab by default
    tabs[0].click();
</script>
