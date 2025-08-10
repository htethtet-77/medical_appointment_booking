<title><?php echo SITENAME; ?></title>
<?php require APPROOT . '/views/inc/navbar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/appointmentform.css">

<div class="appointmentform-container">
    <h2>Book Your Appointment</h2>

    <form action="<?php echo URLROOT; ?>/appointment/book" method="POST">

        <fieldset class="form-section">
            <legend>Your Information</legend>
            <div class="form-group">
                <label for="patientName">Full Name:</label>
                <input type="text" id="patientName" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="patientEmail">Email:</label>
                <input type="email" id="patientEmail" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="patientPhone">Phone Number:</label>
                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
            </div>
            <div class="form-group">
                <label for="gender">Gender:</label>
                <input type="text" id="gender" name="gender" value="<?php echo htmlspecialchars($user['gender']); ?>" required>
            </div>
        </fieldset>

        <fieldset class="form-section">
            <legend>Appointment Details</legend>
            <input type="hidden" name="doctor_id" value="<?php echo htmlspecialchars($data['doctor']['user_id'] ?? ''); ?>">

            <div class="form-group">
                <label>Selected Doctor:</label>
                <p class="selected-doctor-name">
                    Dr. <?= htmlspecialchars($data['doctor']['name'] ?? '') ?>
                    (<?= htmlspecialchars($data['doctor']['degree'] ?? '') ?>)
                </p>
            </div>

            <div class="form-group">
                <label for="appointmentDate">Appointment Date:</label>
                <input type="date" id="appointmentDate" name="appointment_date"
                       min="<?= date('Y-m-d'); ?>"
                       value="<?= htmlspecialchars($data['selected_date'] ?? date('Y-m-d')) ?>"
                       required
                       onchange="window.location.href='<?= URLROOT ?>/appointment/appointmentform/<?= htmlspecialchars($data['doctor']['user_id'] ?? '') ?>?date=' + this.value;">
            </div>

            <div class="form-group">
                <label for="appointmentTime">Doctor's Available Time:</label>
                <input type="text" id="appointmentTime" name="timeslot_id" readonly
                       value="<?php
                            $start = $data['doctor']['start_time'] ?? null;
                            $end = $data['doctor']['end_time'] ?? null;
                            if ($start && $end) {
                                echo htmlspecialchars(date("h:i A", strtotime($start)) . ' => ' . date("h:i A", strtotime($end)));
                            }
                        ?>">
            </div>

            <div class="form-group">
                <label for="timeslot">Select Available Timeslot:</label>
                    <select name="appointment_time" id="timeslot" required>
    <option value="">-- Select Time --</option>
    <?php if (!empty($data['appointment_time'])): ?>
        <?php foreach ($data['appointment_time'] as $slot): ?>
            <?php 
                $slotDateTime = DateTime::createFromFormat('H:i:s', $slot);
                if (!$slotDateTime) {
                    $slotDateTime = new DateTime($slot);
                }
            ?>
            <option value="<?= htmlspecialchars($slot) ?>">
                <?= htmlspecialchars($slotDateTime->format('g:i A')) ?>
            </option>
        <?php endforeach; ?>
    <?php else: ?>
        <option disabled>No timeslots available</option>
    <?php endif; ?>
</select>


                        </div>

            <div class="form-group">
                <label for="reason">Reason for Visit (Symptoms):</label>
                <textarea id="reason" name="reason" rows="4" placeholder="Briefly describe your symptoms or reason for visit..."></textarea>
            </div>
        </fieldset>

        <button type="submit" class="submit-btn">Book Appointment</button>
    </form>
<div class="back-button-container">
  <a href="<?= URLROOT ?>/patient/doctorprofile/<?= htmlspecialchars($data['doctor']['user_id']) ?>" 
     class="back-button" role="button" tabindex="0">
    ⬅ Back to Profile
  </a>
</div>

</div>
</body>
</html>
