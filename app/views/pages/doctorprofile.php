<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title><?php echo htmlspecialchars(SITENAME); ?></title>

<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com"></script>
<!-- FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
</head>
<body class="bg-gray-100 font-inter">

<?php require APPROOT . '/views/inc/navbar.php'; ?>

<section id="doctorProfileSection" class="py-12 px-4">
    <div class="container mx-auto max-w-5xl bg-white rounded-2xl shadow-xl p-10 flex flex-col md:flex-row items-center md:items-start gap-10">

        <div class="flex-shrink-0 w-48 h-48 md:w-56 md:h-56 rounded-xl overflow-hidden shadow-lg bg-gray-200 flex items-center justify-center text-gray-400 text-6xl">
            <?php if (!empty($data['doctor']['profile_image'])) : ?>
                <img src="/<?php echo htmlspecialchars($data['doctor']['profile_image']); ?>" alt="Doctor Image" class="w-full h-full object-cover" />
            <?php else : ?>
                <i class="fa-solid fa-user-doctor"></i>
            <?php endif; ?>
        </div>

        <div class="flex-grow text-center md:text-left">
            <h1 class="text-4xl font-bold text-gray-900 mb-3">
                Dr. <?php echo htmlspecialchars($data['doctor']['name']); ?>
            </h1>
            <p class="text-xl text-teal-600 font-semibold mb-6">
                <?php echo htmlspecialchars($data['doctor']['specialty'] ?? 'General Physician'); ?>
            </p>

            <div class="space-y-3 text-gray-700 text-lg">
                <div class="flex items-center justify-center md:justify-start">
                    <i class="fa-solid fa-graduation-cap text-teal-600 mr-3"></i>
                    <span><?php echo htmlspecialchars($data['doctor']['degree'] ?? 'MBBS, MD'); ?></span>
                </div>
                <div class="flex items-center justify-center md:justify-start">
                    <i class="fa-solid fa-briefcase text-teal-600 mr-3"></i>
                    <span>Experience: <?php echo htmlspecialchars($data['doctor']['experience'] ?? 'N/A'); ?> Years</span>
                </div>
                <div class="flex items-center justify-center md:justify-start">
                    <i class="fa-solid fa-dollar-sign text-teal-600 mr-3"></i>
                    <span>Consultation Fee: <?php echo htmlspecialchars($data['doctor']['fee'] ?? 'N/A'); ?></span>
                </div>
                <div class="flex items-center justify-center md:justify-start">
                    <i class="fa-solid fa-map-marker-alt text-teal-600 mr-3"></i>
                    <span>Location: <?php echo htmlspecialchars($data['doctor']['address'] ?? 'N/A'); ?></span>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="contentSections" class="py-12 px-4">
    <div class="container mx-auto max-w-5xl grid grid-cols-1 md:grid-cols-2 gap-10">

        <div class="bg-white shadow-lg rounded-2xl p-8 hover:shadow-xl transition duration-300">
            <h2 class="text-2xl font-bold text-gray-800 mb-5">About the Doctor</h2>
            <p class="text-gray-700 leading-relaxed text-base">
                <?php echo nl2br(htmlspecialchars($data['doctor']['bio'] ?? 'No description available.')); ?>
            </p>
        </div>

        <div class="bg-white shadow-lg rounded-2xl p-8 hover:shadow-xl transition duration-300">
            <h2 class="text-2xl font-bold text-gray-800 mb-5">Availability</h2>
            <div class="space-y-3 text-gray-700 text-lg mb-6">
                <?php
                $start = $data['doctor']['start_time'] ?? null;
                $end = $data['doctor']['end_time'] ?? null;
                echo '<p class="font-semibold text-teal-600">' . htmlspecialchars($data['doctor']['day'] ?? '') . '</p>';
                if ($start && $end) {
                    echo '<p>' . date("h:i A", strtotime($start)) . ' - ' . date("h:i A", strtotime($end)) . '</p>';
                }
                ?>
            </div>

            <h3 class="text-xl font-bold text-gray-700 mb-4">Available Time Slot by Date</h3>
          <form id="availabilityForm" method="GET" class="mb-6">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($data['doctor']['user_id']); ?>">
    <input type="date" name="date" value="<?php echo htmlspecialchars($data['selected_date']); ?>" 
           class="border p-2 rounded-lg shadow-sm">
    <button type="submit" 
            class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700">
        Check Availability
    </button>
</form>

<!-- <div id="slotsContainer" class="flex flex-wrap gap-3">
    <?php foreach ($data['appointment_time'] as $slot): ?>
        <span class="bg-teal-100 text-teal-800 rounded-lg p-3 text-center font-medium">
            <?php echo htmlspecialchars($slot); ?>
        </span>
    <?php endforeach; ?>
</div> -->

          <?php 
          $doctor_id = htmlspecialchars($data['doctor']['user_id']); ?>
            <div id="slotsContainer" class="flex flex-wrap gap-3">
               <?php
            $selectedDate = $data['selected_date'] ?? date('Y-m-d');
            $now = new DateTime();
            $today = $now->format('Y-m-d');
            ?>

            <?php if (!empty($data['appointment_time'])): ?>
                <?php
                $hasAvailableSlots = false;
                foreach ($data['appointment_time'] as $slot):
                    // Combine date and time for comparison
                    $slotDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $selectedDate . ' ' . $slot);
                    if (!$slotDateTime) {
                        $slotDateTime = new DateTime($selectedDate . ' ' . $slot);
                    }

                    // Show slot only if in future OR selected date is in future
                    if ($selectedDate > $today || ($selectedDate == $today && $slotDateTime > $now)):
                        $hasAvailableSlots = true;
                        ?>
                        <span class="bg-teal-100 text-teal-800 rounded-lg p-3 text-center font-medium">
                            <?php echo htmlspecialchars(date("h:i A", strtotime($slot))); ?>
                        </span>
                    <?php
                    endif;
                endforeach;

                if (!$hasAvailableSlots) {
                    echo '<span class="text-gray-500">No timeslots available</span>';
                }
                ?>
            <?php else: ?>
                <span class="text-gray-500">No timeslots available</span>
            <?php endif; ?>

                        </div>


            <a href="<?php echo URLROOT; ?>/appointment/appointmentform/<?php echo $doctor_id; ?>"
               class="bg-teal-600 hover:bg-teal-700 text-white font-semibold py-3 px-10 rounded-full shadow-lg transition duration-300 inline-block text-center mt-6">
                <i class="fa-solid fa-calendar-check mr-2"></i> Book Appointment
            </a>
        </div>

        <div class="bg-white shadow-lg rounded-2xl p-8 md:col-span-2 hover:shadow-xl transition duration-300">
            <h2 class="text-2xl font-bold text-gray-800 mb-5">Verification</h2>
            <div class="grid sm:grid-cols-2 gap-6">
                <div class="bg-gray-50 rounded-xl p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Verification</h3>
                    <ul class="space-y-3 text-gray-600">
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-circle-check text-green-600"></i>
                            <span>Medical License Verified</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-circle-check text-green-600"></i>
                            <span>Background Check Completed</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-circle-check text-green-600"></i>
                            <span>Hospital Credentials Verified</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    <!-- Back Button -->
    <div class="md:col-span-2">
      <a href="<?= URLROOT ?>/patient/doctors/<?= htmlspecialchars($doctor_id) ?>" 
         class="inline-block px-4 py-2 mt-4 rounded-md bg-green-600 hover:bg-green-700 text-white font-medium transition">
        ⬅ Back
      </a>
    </div>

    </div>
</section>

<?php require APPROOT . '/views/inc/footer.php'; ?>

</body>
</html>
<script>
document.getElementById('availabilityForm').addEventListener('submit', function(e){
    e.preventDefault(); // prevent page refresh

    const form = e.target;
    const doctorId = form.querySelector('input[name="id"]').value;
    const selectedDate = form.querySelector('input[name="date"]').value;

    fetch(`/patient/doctorprofile/${doctorId}?date=${selectedDate}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        const container = document.getElementById('slotsContainer');
        container.innerHTML = '';

        if(data.appointment_time && data.appointment_time.length > 0){
            data.appointment_time.forEach(slot => {
                const span = document.createElement('span');
                span.className = 'bg-teal-100 text-teal-800 rounded-lg p-3 text-center font-medium';
                span.textContent = slot;
                container.appendChild(span);
            });
        } else {
            container.innerHTML = '<span class="text-gray-500">No timeslots available</span>';
        }
    })
    .catch(err => console.error('Error fetching slots:', err));
});
</script>
