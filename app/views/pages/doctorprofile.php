<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo SITENAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <?php require APPROOT . '/views/inc/navbar.php'; ?>
</head>
<body>

<section id="doctorProfileSection" class="bg-gray-200 py-10 px-4">
    <div class="container mx-auto max-w-4xl bg-white rounded-xl shadow-lg p-8 flex flex-col md:flex-row items-center md:items-start gap-8">

        <!-- Doctor Image -->
        <div class="flex-shrink-0 w-48 h-48 rounded-lg overflow-hidden shadow-md bg-gray-100 flex items-center justify-center text-gray-400 text-6xl">
            <?php if (!empty($data['doctor']['profile_image'])) : ?>
                <img src="/<?= htmlspecialchars($data['doctor']['profile_image'])?>" alt="Doctor Image" class="w-full h-full object-cover" />
            <?php else : ?>
                <i class="fa-solid fa-user-doctor"></i>
            <?php endif; ?>
        </div>

        <!-- Doctor Details -->
        <div class="flex-grow text-center md:text-left">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">
                Dr. <?php echo htmlspecialchars($data['doctor']['name']); ?>
            </h1>
            <p class="text-lg md:text-xl text-gray-600 font-semibold mb-4">
                <?php echo htmlspecialchars($data['doctor']['specialty'] ?? 'General Physician'); ?>
            </p>

            <div class="flex flex-col items-center md:items-start space-y-2 text-gray-700 text-base md:text-lg">
                <span class="flex items-center">
                    <i class="fa-solid fa-graduation-cap text-black mr-2"></i>
                    <?php echo htmlspecialchars($data['doctor']['degree'] ?? 'MBBS, MD'); ?>
                </span>
                <span class="flex items-center">
                    <i class="fa-solid fa-briefcase text-black mr-2"></i>
                    <?php echo htmlspecialchars($data['doctor']['experience'] ?? 'Experience N/A'); ?>
                </span>
                <span class="flex items-center">
                    <i class="fa-solid fa-dollar-sign text-black mr-2"></i>
                    Consultation Fee: <?php echo htmlspecialchars($data['doctor']['fee'] ?? 'N/A'); ?>
                </span>
                <span class="flex items-center">
                    <i class="fa-solid fa-map-marker-alt text-black mr-2"></i>
                    Location - <?php echo htmlspecialchars($data['doctor']['location'] ?? 'N/A'); ?>
                </span>
            </div>
        </div>
    </div>
</section>

<section id="contentSections" class="py-10 px-4">
    <div class="container mx-auto max-w-4xl grid grid-cols-1 md:grid-cols-2 gap-8">

        <div class="bg-white shadow-lg rounded-xl p-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">About the Doctor</h2>
            <p class="text-gray-700 leading-relaxed text-base">
                <?php echo nl2br(htmlspecialchars($data['doctor']['bio'] ?? 'No description available.')); ?>
            </p>
        </div>

        <div class="bg-white shadow-lg rounded-xl p-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Availability</h2>
            <div class="space-y-2 text-gray-700 text-base mb-6">
                <?php
                if (!empty($data['availability'])) {
                    $availability = json_decode($data['doctor']['availability'], true);
                    if (is_array($availability)) {
                        foreach ($availability as $day => $time) {
                            echo '<p class="flex justify-between"><span>' . htmlspecialchars($day) . ':</span> <span>' . htmlspecialchars($time) . '</span></p>';
                        }
                    } else {
                        echo '<p>' . htmlspecialchars($data['availability']) . '</p>';
                    }
                } else {
                    echo '<p>Availability information not provided.</p>';
                }
                ?>
            </div>
            <a href="<?php echo URLROOT; ?>/pages/appointmentform" id="bookAppointmentBtn" class="bg-[#0C969C] hover:bg-opacity-90 text-white font-semibold py-3 px-8 rounded-full shadow-lg transition duration-300">
                Book Appointment
            </a>
        </div>

        <div class="bg-white shadow-lg rounded-xl p-6 md:col-span-2">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Services</h2>
            <ul class="list-disc list-inside space-y-2 text-gray-700 text-base">
         
            </ul>
        </div>

    </div>
</section>

<?php require APPROOT . '/views/inc/footer.php'; ?>

</body>
</html>
