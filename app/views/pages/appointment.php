<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?></title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/appointment.css">
</head>
<body class="antialiased">
    <?php require APPROOT . '/views/inc/navbar.php'; ?>

    <!-- Main Content -->
    <main class="py-10 px-4 min-h-[calc(100vh-160px)]">
        <div class="container mx-auto max-w-4xl">
            <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">My Appointments</h1>

            <!-- Show current user ID -->
            <div id="userIdDisplay" class="text-sm text-gray-600 mb-4">
                User ID: <span id="currentUserId" class="font-mono">
                    0000<?= htmlspecialchars($user['id'] ?? '') ?>
                </span>
            </div>
            <!-- <form action="<?php echo URLROOT;?>/appointment/appointmentsList" method="POST">
            <input type="hidden" name="userId" value="<?php echo htmlspecialchars($user['id']); ?>">
            </form> -->

<!-- <pre><?php print_r($data['appointments']); ?></pre> -->
 <!-- <pre><?php print_r($data); ?></pre> -->

            <!-- Appointment List -->
            <div id="appointmentsList" class="space-y-6" >
                <?php if (!empty($data['appointments']) && is_array($data['appointments'])): ?>
                    <?php foreach ($data['appointments'] as $appointment): ?>
                        <div class="appointment-card flex gap-4 items-center bg-white shadow-md rounded-lg p-4">
                            <!-- Doctor Image -->
                            <div class="flex-shrink-0 w-24 h-24 bg-gray-100 rounded-lg flex items-center justify-center shadow-sm overflow-hidden">
                                <?php if (!empty($appointment['doctor_profile_image'])): ?>
                                    <img src="/<?= htmlspecialchars($appointment['doctor_profile_image']) ?>" 
                                         class="w-full h-full object-cover" 
                                         alt="Doctor Image">
                                <?php else: ?>
                                    <i class="fa-solid fa-user-doctor text-gray-400 text-5xl"></i>
                                <?php endif; ?>
                            </div>

                            <!-- Appointment Details -->
                            <div class="flex-grow">
                                <h3 class="text-xl font-semibold text-gray-800">
                                    Dr. <?= htmlspecialchars($appointment['doctor_name'] ?? '') ?>
                                </h3>
                                <p class="text-gray-600"><?= htmlspecialchars($appointment['specialty'] ?? '') ?></p>
                                <p class="text-gray-700 mt-2">
                                    <?= htmlspecialchars($appointment['day'] ?? '') ?>, 
                                    <?= !empty($appointment['start_time']) ? date("h:i A", strtotime($appointment['start_time'])) : '' ?>
                                    -
                                    <?= !empty($appointment['end_time']) ? date("h:i A", strtotime($appointment['end_time'])) : '' ?>
                                </p>
                                <p class="text-gray-700">
                                    Consultation Fee: 
                                    <span class="font-bold text-[#0C969C]">
                                        <?= htmlspecialchars($appointment['fee'] ?? '0') ?>
                                    </span>
                                </p>
                                <p class="text-gray-500 text-sm">
                                    Reason: <?= htmlspecialchars($appointment['reason'] ?? '') ?>
                                </p>

                                <!-- Delete Button -->
                                <div class="flex space-x-2 mt-3">
                                    <form action="<?= URLROOT ?>/appointment/delete/<?= htmlspecialchars($appointment['id'] ?? '') ?>" method="POST">
                                        <button type="submit" 
                                            class="text-red-500 hover:text-red-700 font-medium text-sm">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center text-gray-600">You have no appointments yet.</p>
                <?php endif; ?>
            </div>

            <!-- Add New Appointment -->
            <a href="<?= URLROOT; ?>/patient/doctors" class="add-appointment-btn">+</a>
        </div>
    </main>

    <?php require APPROOT . '/views/inc/footer.php'; ?>
</body>
</html>
