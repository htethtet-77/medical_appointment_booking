<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/doctorbar.php'; ?>
        <main class="history">
            <h2 class="section-title">Upcoming Appointment</h2>

            <div class="date-selector">
                <label for="selected-date">Selected Date :</label>
                <div class="date-input-wrapper">
                    <span class="material-icons">calendar_today</span>
                    <input type="text" id="display-date" value="May 15, 2025" readonly>
                    <input type="date" id="selected-date" value="2025-05-15">
                </div>
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
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.getElementById('selected-date');
            const displayDate = document.getElementById('display-date');

            // Set initial display date based on the hidden input's value
            function formatDateForDisplay(dateString) {
                const date = new Date(dateString);
                const options = { month: 'long', day: 'numeric', year: 'numeric' };
                return date.toLocaleDateString('en-US', options);
            }

            displayDate.value = formatDateForDisplay(dateInput.value);

            // Update display date when a new date is selected
            dateInput.addEventListener('change', function() {
                displayDate.value = formatDateForDisplay(this.value);
            });
        });
    </script>
</body>
</html>