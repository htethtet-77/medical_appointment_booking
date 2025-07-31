<title><?php echo SITENAME; ?></title>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/appointmentview.css?v=2">
<?php require APPROOT . '/views/inc/sidebar.php'; ?>

<div class="app-container">
    <div class="appointment-header">
        <h1 class="appointment-title">All Appointments</h1>
        <div class="filters">
       
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
                <?php if(!empty($data['appointments'])): ?>
                    <?php foreach($data['appointments'] as $appointment): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($appointment['appointment_id']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['patient_name']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['doctor_name']); ?></td>
                            <td class="fees"><?php echo htmlspecialchars($appointment['fee']) ; ?></td>
                            <td><?php echo htmlspecialchars(date('h:i A', strtotime($appointment['start_time']))); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo strtolower($appointment['status_name']); ?>">
                                    <?php echo htmlspecialchars($appointment['status_name']); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align:center;">No appointments found.</td>
                    </tr>
                <?php endif; ?>
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
