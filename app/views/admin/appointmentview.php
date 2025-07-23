<title><?php echo SITENAME;?></title>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/appointmentview.css?v=2">

<?php require APPROOT . '/views/inc/sidebar.php'; ?>

    <div class="app-container">
        <div class="appointment-header">
            <h1 class="appointment-title">All Appointments</h1>
            <div class="filters">
                <div class="filter-group">
                    <label for="selectedDate">Selected Date:</label>
                    <input type="date" id="selectedDate" class="date-input" value="2025-05-15">
                </div>
                <div class="filter-group">
                    <label for="statusFilter">Status:</label>
                    <select id="statusFilter" class="status-select">
                        <option value="all">All Appointments</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="pending">Pending</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="appointmentview-table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Patient Name</th>
                        <th>Doctor</th>
                        <th>Fees</th>
                        <th>Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>John Smith</td>
                        <td>Dr. Daniel</td>
                        <td class="fees">120$</td>
                        <td>09:00 AM</td>
                        <td>
                            <span class="status-badge status-confirmed">
                                confirmed
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Sarah Johnson</td>
                        <td>Dr. Emily</td>
                        <td class="fees">150$</td>
                        <td>10:30 AM</td>
                        <td>
                            <span class="status-badge status-pending">
                                pending
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Michael Brown</td>
                        <td>Dr. Daniel</td>
                        <td class="fees">120$</td>
                        <td>11:00 AM</td>
                        <td>
                            <span class="status-badge status-confirmed">
                                confirmed
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Lisa Wilson</td>
                        <td>Dr. Sarah</td>
                        <td class="fees">180$</td>
                        <td>02:00 PM</td>
                        <td>
                            <span class="status-badge status-cancelled">
                                cancelled
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>David Lee</td>
                        <td>Dr. Daniel</td>
                        <td class="fees">120$</td>
                        <td>03:30 PM</td>
                        <td>
                            <span class="status-badge status-confirmed">
                                confirmed
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>Emma Davis</td>
                        <td>Dr. Emily</td>
                        <td class="fees">150$</td>
                        <td>04:00 PM</td>
                        <td>
                            <span class="status-badge status-pending">
                                pending
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>7</td>
                        <td>Robert Taylor</td>
                        <td>Dr. Sarah</td>
                        <td class="fees">180$</td>
                        <td>04:30 PM</td>
                        <td>
                            <span class="status-badge status-confirmed">
                                confirmed
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>8</td>
                        <td>Jennifer Martinez</td>
                        <td>Dr. Daniel</td>
                        <td class="fees">120$</td>
                        <td>05:00 PM</td>
                        <td>
                            <span class="status-badge status-cancelled">
                                cancelled
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <script>
    const statusFilter = document.getElementById("statusFilter");
    const tableRows = document.querySelectorAll("tbody tr");

    statusFilter.addEventListener("change", function () {
        const selectedStatus = this.value.toLowerCase();

        tableRows.forEach(row => {
            const statusBadge = row.querySelector(".status-badge");
            const rowStatus = statusBadge ? statusBadge.textContent.trim().toLowerCase() : "";

            if (selectedStatus === "all" || rowStatus === selectedStatus) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
</script>

</body>
</html>