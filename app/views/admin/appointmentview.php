 <?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/sidebar.php'; ?>

    <main class="view-container">
        <h1 class="page-title">All Appointment</h1>

        <div class="filters">
            <div class="filter-group">
                <label for="selectedDate">Selected Date :</label>
                <div class="custom-datepicker">
                    <input type="date" id="selectedDate" name="selectedDate" value="2025-05-15">
                </div>
            </div>

            <div class="filter-group">
                <div class="custom-select">
                    <select id="appointmentStatus" name="appointmentStatus">
                        <option value="All" selected>All</option> <option value="Confirmed">Confirmed</option>
                        <option value="Pending">Pending</option>
                        <option value="Cancel">Cancel</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="appointment-table-container">
            <table class="appointment-table">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Patient Name</th>
                        <th>Doctor</th>
                        <th>Fees</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr data-status="Confirmed">
                        <td data-label="id">1</td>
                        <td data-label="Patient Name">Patient One</td>
                        <td data-label="Doctor">Dr. Daniel</td>
                        <td data-label="Fees">120$</td>
                        <td data-label="Action" class="action-cell status-confirmed">Confirmed</td>
                    </tr>
                    <tr data-status="Confirmed">
                        <td data-label="id">4</td>
                        <td data-label="Patient Name">Patient Two</td>
                        <td data-label="Doctor">Dr. Daniel</td>
                        <td data-label="Fees">120$</td>
                        <td data-label="Action" class="action-cell status-confirmed">Confirmed</td>
                    </tr>
                    <tr data-status="Pending">
                        <td data-label="id">5</td>
                        <td data-label="Patient Name">Patient Three</td>
                        <td data-label="Doctor">Dr. Smith</td>
                        <td data-label="Fees">150$</td>
                        <td data-label="Action" class="action-cell status-pending">Pending</td>
                    </tr>
                    <tr data-status="Cancel">
                        <td data-label="id">6</td>
                        <td data-label="Patient Name">Patient Four</td>
                        <td data-label="Doctor">Dr. Jane</td>
                        <td data-label="Fees">100$</td>
                        <td data-label="Action" class="action-cell status-cancel">Cancel</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusDropdown = document.getElementById('appointmentStatus');
            const datePicker = document.getElementById('selectedDate');
            const tableRows = document.querySelectorAll('.appointment-table tbody tr'); // Get all rows

            // Add a data-status attribute to each row based on its content for easier filtering
            tableRows.forEach(row => {
                const statusCell = row.querySelector('.action-cell');
                if (statusCell) {
                    const statusText = statusCell.textContent.trim();
                    row.setAttribute('data-status', statusText);
                }
            });

            function filterTable() {
                const selectedStatus = statusDropdown.value;
                const selectedDate = datePicker.value; // Get the date in YYYY-MM-DD format

                tableRows.forEach(row => {
                    const rowStatus = row.getAttribute('data-status');
                    const rowDateText = row.children[0].textContent; // Assuming Date is the first column (adjust index if needed)

                    // Convert table date (e.g., "15 May 2025") to YYYY-MM-DD for comparison
                    const parts = rowDateText.split(' ');
                    // Simple month mapping (you might need a more robust solution for all months)
                    const monthMap = {
                        'Jan': '01', 'Feb': '02', 'Mar': '03', 'Apr': '04', 'May': '05', 'Jun': '06',
                        'Jul': '07', 'Aug': '08', 'Sep': '09', 'Oct': '10', 'Nov': '11', 'Dec': '12'
                    };
                    const formattedRowDate = parts[2] + '-' + monthMap[parts[1]] + '-' + (parts[0].length === 1 ? '0' + parts[0] : parts[0]);

                    const statusMatch = (selectedStatus === 'All' || rowStatus === selectedStatus);
                    const dateMatch = (selectedDate === '' || formattedRowDate === selectedDate); // Check if date filter is empty or matches

                    if (statusMatch && dateMatch) {
                        row.style.display = ''; // Show the row
                    } else {
                        row.style.display = 'none'; // Hide the row
                    }
                });
            }

            // Add event listeners
            statusDropdown.addEventListener('change', filterTable);
            datePicker.addEventListener('change', filterTable); // Listen for date changes

            // Initial filter when the page loads
            filterTable();
        });
    </script>
</body>
</html>