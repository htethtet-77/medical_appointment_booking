 <?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/doctorbar.php'; ?>

        <main class="history">
            <h2 class="section-title">Upcoming Appointment</h2>

            <div class="appointment-filter-section">
                <button class="filter-button active">Today</button>
            </div>

            <table class="appointment-table">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Patient Name</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td data-label="Time">9:00 AM</td>
                        <td data-label="Patient Name">Patient Name</td>
                        <td data-label="Status" class="status-confirmed">Confirmed</td>
                    </tr>
                    <tr>
                        <td data-label="Time">10:00 AM</td>
                        <td data-label="Patient Name">Patient Name</td>
                        <td data-label="Status" class="status-confirmed">Confirmed</td>
                    </tr>
                    <tr>
                        <td data-label="Time">1:00 PM</td>
                        <td data-label="Patient Name">Patient Name</td>
                        <td data-label="Status" class="status-confirmed">Confirmed</td>
                    </tr>
                    <tr>
                        <td data-label="Time">3:00 PM</td>
                        <td data-label="Patient Name">Patient Name</td>
                        <td data-label="Status" class="status-confirmed">Confirmed</td>
                    </tr>
                </tbody>
            </table>
        </main>
