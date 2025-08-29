<?php use function Asus\Medical\helpers\csrfInput;
?>
<title><?php echo SITENAME; ?></title>
<?php require APPROOT . '/views/inc/navbar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/appointmentform.css">
<div class="appointmentform-container">
   <?php
if (isset($_SESSION['error'])) {
    echo '
    <div class="alert alert-error">
        <span>' . htmlspecialchars($_SESSION['error']) . '</span>
        <button onclick="this.parentElement.style.display=\'none\'">&times;</button>
    </div>';
    unset($_SESSION['error']);
}

if (isset($_SESSION['success'])) {
    echo '
    <div class="alert alert-success">
        <span>' . htmlspecialchars($_SESSION['success']) . '</span>
        <button onclick="this.parentElement.style.display=\'none\'">&times;</button>
    </div>';
    unset($_SESSION['success']);
}
?>

<style>
/* Base alert style */
.alert {
    max-width: 600px;
    margin: 20px auto;
    padding: 12px 16px;
    border-radius: 6px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-family: Arial, sans-serif;
    font-size: 14px;
}

/* Error alert */
.alert-error {
    border: 1px solid #f5c6cb;
    background-color: #f8d7da;
    color: #721c24;
}

/* Success alert */
.alert-success {
    border: 1px solid #c3e6cb;
    background-color: #d4edda;
    color: #155724;
}

/* Close button */
.alert button {
    background: none;
    border: none;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    color: inherit;
}
.alert button:hover {
    opacity: 0.7;
}
</style>

    <h2>Book Your Appointment</h2>
    <form action="<?php echo URLROOT; ?>/appointment/book" method="POST">
    <?= csrfInput(); ?>
        <input type="hidden" name="g-recaptcha-response" id="recaptchaResponse">
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
       required>
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
         <!-- Google reCAPTCHA -->
            <!-- <div class="form-group">
                <div class="g-recaptcha" data-sitekey="6Ldt3aorAAAAAFE1cumOeq5KAxWJgLSLBIpLaWa-"></div>
            </div> -->
        <button type="submit" class="submit-btn">Book Appointment</button>
    </form>
<div class="back-button-container">
    <a href="<?= URLROOT ?>/patient/doctorprofile/<?= base64_encode($data['doctor']['user_id']) ?>" 
    class="back-button">⬅ Back to Profile</a>

</div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>

</body>
</html>
<script>
document.getElementById('appointmentDate').addEventListener('change', function() {
    const date = this.value;
    const doctorId = "<?= htmlspecialchars($data['doctor']['user_id'] ?? '') ?>";
    const timeslotSelect = document.getElementById('timeslot');
    // Clear current options
    timeslotSelect.innerHTML = '<option>Loading...</option>';
    fetch(`<?= URLROOT ?>/appointment/getslots?doctor_id=${doctorId}&date=${date}`)
        .then(response => response.json())
        .then(data => {
            timeslotSelect.innerHTML = ''; // clear previous
            if (data.length > 0) {
                timeslotSelect.innerHTML = '<option value="">-- Select Time --</option>';
                data.forEach(slot => {
                    const option = document.createElement('option');
                    const slotTime = new Date(`1970-01-01T${slot}`);
                    option.value = slot;
                    option.text = slotTime.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true });
                    timeslotSelect.appendChild(option);
                });
            } else {
                const option = document.createElement('option');
                option.text = 'No timeslots available';
                option.disabled = true;
                timeslotSelect.appendChild(option);
            }
        })
        .catch(err => {
            timeslotSelect.innerHTML = '<option disabled>Error loading slots</option>';
            console.error(err);
        });
});
function refreshCsrfToken() {
    fetch('<?= URLROOT ?>/appointment/refreshCsrfToken')
        .then(res => res.json())
        .then(data => {
            const tokenInput = document.getElementById('csrf_token');
            if (tokenInput) tokenInput.value = data.csrf_token;
            console.log("CSRF token refreshed:", data.csrf_token);
        })
        .catch(err => console.error("CSRF token refresh failed:", err));
}

// Refresh on page load
refreshCsrfToken();

// Refresh every 4 minutes (before 5 min expiry)
setInterval(refreshCsrfToken, 4 * 60 * 1000);
// setInterval(refreshCsrfToken, 60000);

</script>
<!-- Include Google reCAPTCHA script -->
<script src="https://www.google.com/recaptcha/api.js?render=<?php echo RECAPTCHA_V3_SITEKEY; ?>"></script>
<script>
grecaptcha.ready(function() {
    grecaptcha.execute('<?php echo RECAPTCHA_V3_SITEKEY; ?>', {action: 'appointment'}).then(function(token) {
        document.getElementById('recaptchaResponse').value = token;
    });
});

</script>
