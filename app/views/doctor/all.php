<title><?php echo SITENAME; ?></title>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/appointmentview.css">
<?php require APPROOT . '/views/inc/doctorbar.php'; ?>

<div class="app-container">
    <div class="appointment-header">
        <h1 class="appointment-title">All Appointments</h1>
        <div class="filters">
            <!-- <div class="filter-group">
                <label for="selectedDate">Selected Date:</label>
                <input type="date" id="selectedDate" class="date-input" value="<?php echo date('l'); ?>">
            </div> -->
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
    <?php $i = 1; ?>
<?php 
// echo "<pre>";
// var_dump($data['appointments']);
// echo "</pre>";?>
    <div class="appointmentview-table">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Patient Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Time</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($data['appointments'])): ?>
                    <?php foreach ($data['appointments'] as $appointment): ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo htmlspecialchars($appointment['patient_name']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['patient_email']); ?></td>
                            <td ><?php echo htmlspecialchars($appointment['patient_phone']); ?></td>
                            <td><?php echo date("h:i A", strtotime($appointment['start_time'])); ?>
                            <?php echo date("h:i A", strtotime($appointment['end_time'])); ?></td>
                            <td ><?php echo htmlspecialchars($appointment['reason']); ?></td>

                            <td>
                                <span class="status-badge status-<?php echo strtolower($appointment['status_name']); ?>">
                                    <?php echo htmlspecialchars($appointment['status_name']); ?>
                                </span>
                            </td>
                            <td>
                            <form action="<?php echo URLROOT; ?>/appointment/confirm/<?php echo $appointment['appointment_id']; ?>" method="post">
                                <button type="submit" class="confirm-btn"><i class="fas fa-check"></i></button>
                            </form>
                            <form action="<?php echo URLROOT; ?>/appointment/reject/<?php echo $appointment['appointment_id']; ?>" method="post">
                                <button type="submit" class="delete-btn"><i class="fas fa-trash"></i></button>
                            </form>
                           <!-- <?php var_dump($appointment['appointment_id']);?> -->
                        </td>

                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">No appointments found.</td>
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
