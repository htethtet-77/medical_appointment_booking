
<?php require APPROOT . '/views/inc/doctorbar.php'; ?>

    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/dash.css?v=2">

<div class="dashboard-container">
    <div class="container">
        <div class="stats">
            <div class="stat-item">
                <div class="stat-number"><?= isset($data['todaysAppointments']) ? $data['todaysAppointments'] : 0 ?></div>
                <div class="stat-label">Today's Appointments</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?= $data['totalPatients'] ?? 0 ?></div>
                <div class="stat-label">Total Patients</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?= isset($data['totalAppointments']) ? $data['totalAppointments'] : 0 ?></div>
                <div class="stat-label">Total Appointments</div>
            </div>
        </div>

        <div class="main-content">
            <!-- <div class="content-header">
                <h2 class="section-title">Appointments by Date</h2>
            </div> -->
            
            <div class="content-body">
                <?php if (!empty($data['appointmentsByDate'])): ?>
                    <?php foreach ($data['appointmentsByDate'] as $date => $appointments): ?>
                        <div class="date-section">
                            <div class="date-header <?= ($date === $data['todayDate']) ? 'today' : '' ?>">
                                <div class="date-title">
                                    <?php if ($date === $data['todayDate']): ?>
                                        <span>📅</span>
                                        <span>Today - <?= date('F j, Y', strtotime($date)) ?></span>
                                        <span class="date-badge">Current</span>
                                    <?php else: ?>
                                        <span>📅</span>
                                        <span><?= date('F j, Y', strtotime($date)) ?></span>
                                        <?php
                                        $daysDiff = (strtotime($data['todayDate']) - strtotime($date)) / (60 * 60 * 24);
                                        if ($daysDiff > 0): ?>
                                            <span class="date-badge"><?= floor($daysDiff) ?> day<?= floor($daysDiff) > 1 ? 's' : '' ?> ago</span>
                                        <?php elseif ($daysDiff < 0): ?>
                                            <span class="date-badge"><?= abs(floor($daysDiff)) ?> day<?= abs(floor($daysDiff)) > 1 ? 's' : '' ?> ahead</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                                <div class="appointment-count">
                                    <?= count($appointments) ?> appointment<?= count($appointments) > 1 ? 's' : '' ?>
                                </div>
                            </div>

                            <div class="appointments-table">
                                <table class="table">
                                    
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Date</th>
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
                                                 <td class="📅">
                                    📅 <?= date('F j, Y', strtotime($appointment['appointment_date'])) ?>
                                    </td>
                                                <td class="time-cell">
                                                    
                                                    <?= date('g:i A', strtotime($appointment['appointment_time'])) ?>
                                                </td>
                                                <td>
                                                    <div class="patient-name">
                                                        <?= htmlspecialchars($appointment['patient_name']) ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="contact-info">
                                                        <div class="contact-item email-item">
                                                            <?= htmlspecialchars($appointment['patient_email']) ?>
                                                        </div>
                                                        <div class="contact-item phone-item">
                                                            <?= htmlspecialchars($appointment['patient_phone']) ?>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="reason-cell">
                                                    <div class="reason-text">
                                                        <?= htmlspecialchars($appointment['reason']) ?>
                                                    </div>
                                                </td>
                                                 <td>
                                                <div class="flex justify-between items-center mt-2">
                                                    <?php
                                                        $status = $appointment['status_name'] ?? 'Unknown';
                                                        $badgeClasses = [
                                                            'Pending'   => 'badge badge-pending',
                                                            'Confirmed' => 'badge badge-confirmed',
                                                            'Cancelled'  => 'badge badge-rejected',
                                                            'Completed' => 'badge badge-completed',
                                                            'Unknown'   => 'badge badge-unknown'
                                                        ];
                                                        $colorClass = $badgeClasses[$status] ?? $badgeClasses['Unknown'];
                                                    ?>
                                                    <span class="<?= $colorClass ?>">
                                                        <?= htmlspecialchars($status) ?>
                                                    </span>
                                                </div>
                                            </td>

                                                <td class="action-cell">
    <div class="action-buttons">
        <?php if ($appointment['status_name'] === 'Pending'): ?>
            <a href="#" class="btn-appointment btn btn-confirm" data-id="<?= $appointment['appointment_id']; ?>" data-action="confirm">Confirm</a>
            <a href="#" class="btn-appointment btn btn-cancel" data-id="<?= $appointment['appointment_id']; ?>" data-action="reject">Cancel</a>
        <?php elseif ($appointment['status_name'] === 'Confirmed'): ?>
            <a href="#" class="btn-appointment btn btn-cancel" data-id="<?= $appointment['appointment_id']; ?>" data-action="reject">Cancel</a>
        <?php elseif ($appointment['status_name'] === 'Cancelled'): ?>
            <a href="#" class="btn-appointment btn btn-confirm" data-id="<?= $appointment['appointment_id']; ?>" data-action="confirm">Confirm</a>
        <?php endif; ?>
    </div>
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
                        <p>No appointments found.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
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
<script>
document.querySelectorAll('.btn-appointment').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const appointmentId = this.dataset.id;
        const action = this.dataset.action; // 'confirm' or 'reject'
        const btn = this;

        fetch(`<?= URLROOT ?>/appointment/${action}/${appointmentId}`, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const row = btn.closest('tr');

                // Update status badge
                const statusBadge = row.querySelector('span.badge');
                if (statusBadge) {
                    statusBadge.textContent = data.status;
                    statusBadge.className = 'badge ' +
                        (data.status === 'Confirmed' ? 'badge-confirmed' : 'badge-rejected');
                }

                // Remove ONLY the clicked button
                btn.remove();

                // If both buttons are gone (rare), show the remaining one
                const actionDiv = row.querySelector('.action-buttons');
                if (action === 'confirm') {
                    if (!actionDiv.querySelector('.btn-cancel')) {
                        const cancelBtn = document.createElement('a');
                        cancelBtn.href = '#';
                        cancelBtn.className = 'btn-appointment btn btn-cancel';
                        cancelBtn.dataset.id = appointmentId;
                        cancelBtn.dataset.action = 'reject';
                        cancelBtn.textContent = 'Cancel';
                        actionDiv.appendChild(cancelBtn);

                        // Attach click again
                        cancelBtn.addEventListener('click', arguments.callee);
                    }
                } else if (action === 'reject') {
                    if (!actionDiv.querySelector('.btn-confirm')) {
                        const confirmBtn = document.createElement('a');
                        confirmBtn.href = '#';
                        confirmBtn.className = 'btn-appointment btn btn-confirm';
                        confirmBtn.dataset.id = appointmentId;
                        confirmBtn.dataset.action = 'confirm';
                        confirmBtn.textContent = 'Confirm';
                        actionDiv.appendChild(confirmBtn);

                        // Attach click again
                        confirmBtn.addEventListener('click', arguments.callee);
                    }
                }

            } else {
                alert(data.message || 'Failed to update appointment');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Error occurred. Please try again.');
        });
    });
});

</script>
