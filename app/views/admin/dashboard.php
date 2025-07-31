<?php require APPROOT . '/views/inc/sidebar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/dash.css">

<div class="dashboard-container">
    <div class="container">
        <div class="stats">
            <div class="stat-item">
                <div class="stat-number"><?= $data['todaysAppointments'] ?? 0 ?></div>
                <div class="stat-label">Today's Appointments</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?= $data['totalPatients'] ?? 0 ?></div>
                <div class="stat-label">Total Patients</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?= $data['totalAppointments'] ?? 0 ?></div>
                <div class="stat-label">Total Appointments</div>
            </div>
        </div>

        <div class="main-content">
            <div class="content-body">
               <?php if (!empty($data['appointmentsByDate'])): ?>
    <?php foreach ($data['appointmentsByDate'] as $date => $appointments): ?>
        <div class="date-section">
            <div class="date-header today">
                <div class="date-title">
                    <span>📅</span>
                    <span>Today - <?= date('F j, Y', strtotime($date)) ?></span>
                    <span class="date-badge">Current</span>
                </div>
                <div class="appointment-count">
                    <?= count($appointments) ?> appointment<?= count($appointments) > 1 ? 's' : '' ?>
                </div>
            </div>

            <div class="appointments-table">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Patient Name</th>
                            <th>Contact Information</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($appointments as $appointment): ?>
                            <tr>
                                <td><?= date('g:i A', strtotime($appointment['start_time'])) ?></td>
                                <td><?= htmlspecialchars($appointment['patient_name']) ?></td>
                                <td>
                                    <div><?= htmlspecialchars($appointment['patient_email']) ?></div>
                                    <div><?= htmlspecialchars($appointment['patient_phone']) ?></div>
                                </td>
                                <td><?= htmlspecialchars($appointment['reason']) ?></td>
                                <td>
                                    <?php
                                        $status = $appointment['status_name'] ?? 'Unknown';
                                        $badgeClasses = [
                                            'Pending'   => 'badge badge-pending',
                                            'Confirmed' => 'badge badge-confirmed',
                                            'Cancelled' => 'badge badge-rejected',
                                            'Completed' => 'badge badge-completed',
                                            'Unknown'   => 'badge badge-unknown'
                                        ];
                                        $colorClass = $badgeClasses[$status] ?? $badgeClasses['Unknown'];
                                    ?>
                                    <span class="<?= $colorClass ?> status-badge"><?= htmlspecialchars($status) ?></span>
                                </td>
                                <td>
                                 
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="empty-state">
        <p>No appointments today.</p>
    </div>
<?php endif; ?>

            </div>
        </div>
    </div>
</div>

<script>
const statusFilter = document.getElementById("statusFilter");
if(statusFilter){
    const tableRows = document.querySelectorAll("tbody tr");
    statusFilter.addEventListener("change", function () {
        const selectedStatus = this.value.toLowerCase();
        tableRows.forEach(row => {
            const statusBadge = row.querySelector(".status-badge");
            const rowStatus = statusBadge ? statusBadge.textContent.trim().toLowerCase() : "";
            row.style.display = (selectedStatus === "all" || rowStatus === selectedStatus) ? "" : "none";
        });
    });
}
</script>
