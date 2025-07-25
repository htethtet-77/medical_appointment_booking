 <?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/doctorbar.php'; ?>

        <!-- Stats -->
        <div class="stats">
            <div class="stat-item">
                <div class="stat-number">5</div>
                <div class="stat-label">Today's Appointments</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">120</div>
                <div class="stat-label">Total Patients</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">15</div>
                <div class="stat-label">Total Appointment</div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <h2 class="section-title">Today's Appointments</h2>
            
            <div class="date-info">
                Thursday, July 24, 2025
            </div>

            <div class="appointments">
                <!-- Appointment 1 -->
                <div class="appointment">
                    <div class="time">9:00 AM</div>
                    <div class="patient-info">
                        <h4>John Doe</h4>
                        <p>Age: 45</p>
                        <p>Phone: (555) 123-4567</p>
                        <p>Type: Routine Checkup</p>
                        <div class="actions">
                            <button class="btn btn-primary">Confirm</button>
                            <button class="btn btn-cancel">Cancel</button>
                        </div>
                    </div>
                </div>

                <!-- Appointment 2 -->
                <div class="appointment">
                    <div class="time">10:30 AM</div>
                    <div class="patient-info">
                        <h4>Maria Santos</h4>
                        <p>Age: 32</p>
                        <p>Phone: (555) 987-6543</p>
                        <p>Type: Follow-up Visit</p>
                        <div class="actions">
                            <button class="btn btn-primary">Confirm</button>
                            <button class="btn btn-cancel">Cancel</button>
                        </div>
                    </div>
                </div>

                <!-- Appointment 3 -->
                <div class="appointment">
                    <div class="time">1:00 PM</div>
                    <div class="patient-info">
                        <h4>Robert Johnson</h4>
                        <p>Age: 58</p>
                        <p>Phone: (555) 456-7890</p>
                        <p>Type: Consultation</p>
                        <div class="actions">
                            <button class="btn btn-primary">Confirm</button>
                            <button class="btn btn-cancel">Cancel</button>
                        </div>
                    </div>
                </div>

                <!-- Appointment 4 -->
                <div class="appointment">
                    <div class="time">3:00 PM</div>
                    <div class="patient-info">
                        <h4>Lisa Wilson</h4>
                        <p>Age: 28</p>
                        <p>Phone: (555) 234-5678</p>
                        <p>Type: Annual Physical</p>
                        <div class="actions">
                            <button class="btn btn-primary">Confirm</button>
                            <button class="btn btn-cancel">Cancel</button>
                        </div>
                    </div>
                </div>

                <!-- Appointment 5 -->
                <div class="appointment">
                    <div class="time">4:30 PM</div>
                    <div class="patient-info">
                        <h4>Thomas Brown</h4>
                        <p>Age: 65</p>
                        <p>Phone: (555) 345-6789</p>
                        <p>Type: Cardiac Screening</p>
                        <div class="actions">
                            <button class="btn btn-primary">Confirm</button>
                            <button class="btn btn-cancel">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>