<title><?php echo SITENAME;?></title>
<?php require APPROOT . '/views/inc/navbar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/appointmentform.css?v=2">
    <form action="<?php echo URLROOT; ?>/appointment/book" method="POST" >

    <div class="appointmentform-container">
        <h2>Book Your Appointment</h2>

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
                 <div class="form-group">
                    <label for="patientPhone">Gender:</label>
                    <input type="text" id="gender" name="gender" value="<?php echo htmlspecialchars($user['gender']); ?>" required>
                </div>
            </fieldset>

            <fieldset class="form-section">
                <legend>Appointment Details</legend>
            <input type="hidden" name="doctor_id" value="<?php echo htmlspecialchars($data['doctor']['user_id']??''); ?>">

                <div class="form-group">
                    <label>Selected Doctor:</label>
                    <p class="selected-doctor-name">
                   
                    Dr. <?=  htmlspecialchars($data['doctor']['name']??'') ?>
                    (<?= htmlspecialchars($data['doctor']['degree']??'')  ?>)
               

                    </p>
                </div>
                 <!-- <div class="form-group">
                    <label for="appointmentDate">Doctor Fee</label>
                     <input type="text" id="doctorFee" name="doctor_fee" value="<?= htmlspecialchars($data['doctor']['fee'] ?? '') ?>" >
                </div> -->

                <div class="form-group">
                    <label for="appointmentDate"> Available Day:</label>
                    <input type="text" id="appointmentDate" name="date" value="<?= htmlspecialchars($data['doctor']['day'] ?? '')?>">
                </div>

               <div class="form-group">
                <label for="appointmentTime">Doctor's Available Time :</label>
                <input type="text" id="appointmentTime" name="time" 
                    value="<?php
                        $start = $data['doctor']['start_time'] ?? null;
                        $end   = $data['doctor']['end_time'] ?? null;

                        if ($start && $end) {
                            echo htmlspecialchars(date("h:i A", strtotime($start)) . ' => ' . date("h:i A", strtotime($end)));
                        }
                    ?>" readonly>
            </div>



                  <div class="form-group">
                <label for="timeslot">Select Available Timeslot:</label>
                <select name="timeslot" id="timeslot" required>
                    <option value="">-- Select Time --</option>
                    <?php if (!empty($data['appointment_time'])): ?>
                        <?php foreach ($data['appointment_time'] as $slot): ?>
                            <option value="<?= htmlspecialchars($slot) ?>">
                                <?= htmlspecialchars($slot) ?>
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

            
                <!-- other form inputs here -->
<button type="submit" class="submit-btn">Book Appointment</button>
       
               
        </form>
    </div>
</body>
</html>