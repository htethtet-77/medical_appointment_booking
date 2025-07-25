<title><?php echo SITENAME;?></title>
<?php require APPROOT . '/views/inc/navbar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/appointmentform.css?v=2">
    <form action="<?php echo URLROOT; ?>/appointment/appointmentform" method="POST" >

    <div class="appointmentform-container">
        <h2>Book Your Appointment</h2>
            <input type="hidden" name="doctorId" value="doc_alex_smith_123"> 

            <fieldset class="form-section">
                <legend>Your Information</legend>
                <div class="form-group">
                    <label for="patientName">Full Name:</label>
                    <input type="text" id="patientName" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="patientEmail">Email:</label>
                    <input type="email" id="patientEmail" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"required>
                </div>
                <div class="form-group">
                    <label for="patientPhone">Phone Number:</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                </div>
            </fieldset>

            <fieldset class="form-section">
                <legend>Appointment Details</legend>
                
                <!-- <div class="form-group">
                    <label>Selected Doctor:</label>
                    <p class="selected-doctor-name">Dr. Alex Smith (General Physician)</p>
                </div> -->
                 <div class="form-group">
                    <label for="appointmentDate">Select Date:</label>
                    <input type="text" id="appointmentDate" name="doc" required>
                </div>

                <div class="form-group">
                    <label for="appointmentDate">Select Date:</label>
                    <input type="date" id="appointmentDate" name="date" min="2025-07-24" required>
                </div>

                <div class="form-group">
                    <label for="appointmentTime">Select Time Slot:</label>
                    <select id="appointmentTime" name="time" required>
                        <option value="">-- Please select a time --</option>
                        <option value="09:00">09:00 AM</option>
                        <option value="09:30">09:30 AM</option>
                        <option value="10:00">10:00 AM</option>
                        <option value="10:30">10:30 AM</option>
                        <option value="11:00">11:00 AM</option>
                        <option value="11:30">11:30 AM</option>
                        <option value="13:00">01:00 PM</option>
                        <option value="13:30">01:30 PM</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="reason">Reason for Visit (optional):</label>
                    <textarea id="reason" name="reason" rows="4" placeholder="Briefly describe your symptoms or reason for visit..."></textarea>
                </div>
            </fieldset>

            <form action="<?php echo URLROOT; ?>/pages/appointment" method="POST">
                <!-- other form inputs here -->
                <button type="submit" class="submit-btn">Book Appointment</button>
            </form>        
        </form>
    </div>
</body>
</html>