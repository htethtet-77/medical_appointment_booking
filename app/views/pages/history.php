 <?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/navbar.php'; ?>

    <main class="history-container">
        <h1 class="page-title">John's Appointment</h1>

        <div class="appointment-table-container">
            <table class="appointment-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Doctor</th>
                        <th>Specialty</th>
                        <th>Fees</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td data-label="Date">15 May 2025</td>
                        <td data-label="Time">10:00AM</td>
                        <td data-label="Doctor">Dr. Denial</td>
                        <td data-label="Specialty">General Physician</td>
                        <td data-label="Fees">120$</td>
                        <td data-label="Status" class="status-confirmed">Confirmed</td>
                    </tr>
                    <tr>
                        <td data-label="Date">17 May 2025</td>
                        <td data-label="Time">10:00AM</td>
                        <td data-label="Doctor">Dr. Denial</td>
                        <td data-label="Specialty">General Physician</td>
                        <td data-label="Fees">120$</td>
                        <td data-label="Status" class="status-cancel">Cancel</td>
                    </tr>
                    <tr>
                        <td data-label="Date">17 May 2025</td>
                        <td data-label="Time">10:30AM</td>
                        <td data-label="Doctor">Dr. Denial</td>
                        <td data-label="Specialty">General Physician</td>
                        <td data-label="Fees">120$</td>
                        <td data-label="Status" class="status-pending">Pending</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>

