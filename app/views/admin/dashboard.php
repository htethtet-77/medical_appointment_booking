<title><?php echo SITENAME; ?></title>
<?php require APPROOT . '/views/inc/sidebar.php'; ?>
    

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/dashboard.css">

<div class="dashboard-container">
    <div class="container">
        <div class="stats">
        <a href="<?= URLROOT; ?>/admin/dashboard" class="stat-item">
            <div class="stat-number"><?= $data['todaysAppointments'] ?? 0 ?></div>
            <div class="stat-label">Today's Appointments</div>
        </a>
        
        <a href="<?= URLROOT; ?>/admin/patientlist" class="stat-item">
            <div class="stat-number"><?= $data['totalPatients'] ?? 0 ?></div>
            <div class="stat-label">Total Patients</div>
        </a>
        
        <a href="<?= URLROOT; ?>/admin/appointmentview" class="stat-item">
            <div class="stat-number"><?= $data['totalAppointments'] ?? 0 ?></div>
            <div class="stat-label">Total Appointments</div>
        </a>
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
                            <th class="date-time-header">Date </th>
                            <th>Time</th>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Reason</th>
                            <th class="status">Status</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($appointments as $appointment): ?>
                            <tr>
                                <td class="date-time-cell">
                                    <div class="date-item">
                                        📅 <?= date('F j, Y', strtotime($appointment['appointment_date'])) ?>
                                    </div>
                                </td>
                                <td class="date-time-cell">
                                     <div class="time-item">
                                        🕐 <?= date('g:i A', strtotime($appointment['appointment_time'])) ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="patient-name">👤 <?= htmlspecialchars($appointment['patient_name']) ?></div>
                                    <div class="contact-info">
                                        <div class="contact-item email-item">
                                            ✉️ <?= htmlspecialchars($appointment['patient_email']) ?>
                                        </div>
                                        <div class="contact-item phone-item">
                                            📞 <?= htmlspecialchars($appointment['patient_phone']) ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="doctor-name">Dr.<?= htmlspecialchars($appointment['doctor_name']) ?></div>
                                </td>
                                
                                <td class="reason-cell">
                                    <div class="reason-text">
                                        <?= htmlspecialchars($appointment['reason']) ?>
                                    </div>
                                </td>
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