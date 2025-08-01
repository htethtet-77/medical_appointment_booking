<title><?php echo SITENAME; ?></title>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/appointmentview.css?v=2">
<?php require APPROOT . '/views/inc/sidebar.php'; ?>
<?php
// Initialize counters for each appointment status
$pendingCount = 0;
$confirmedCount = 0;
$cancelledCount = 0;
$i = 1;

// Loop through appointments to count each status
if (!empty($data['appointments'])) {
    foreach ($data['appointments'] as $appointment) {
        if (strtolower($appointment['status_name']) == 'pending') {
            $pendingCount++;
        } elseif (strtolower($appointment['status_name']) == 'confirmed') {
            $confirmedCount++;
        } elseif (strtolower($appointment['status_name']) == 'cancelled') {
            $cancelledCount++;
        }
    }
}
?>
<div class="app-container">
    <!-- The appointment-header now uses flexbox to arrange its children -->
    <div class="appointment-header flex flex-col md:flex-row md:justify-between md:items-center">
        <!-- Container for title and filters, now placed alongside the cards -->
        <div class="flex flex-col md:flex-row md:items-center mb-4 md:mb-0">
            <h1 class="appointment-title mr-4">All Appointments</h1>
            <div class="filters">
                <div class="filter-group">
                    <label for="statusFilter">Status:</label>
                    <select id="statusFilter" class="status-select">
                        <option value="all">All Appointments</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="pending">Pending </option>
                        <option value="cancelled">Cancelled </option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- The status count cards are now inside the .appointment-header div -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Confirmed Appointments Card -->
            <div class="bg-green-500 text-white rounded-xl shadow-lg p-4 flex items-center justify-between transition-transform transform hover:scale-105">
                <div>
                    <h3 class="text-lg font-medium">Confirmed</h3>
                    <p class="text-3xl font-bold mt-1"><?php echo $confirmedCount; ?></p>
                </div>
                <i class="fas fa-check-circle text-3xl opacity-50"></i>
            </div>
            <!-- Pending Appointments Card -->
            <div class="bg-yellow-500 text-white rounded-xl shadow-lg p-4 flex items-center justify-between transition-transform transform hover:scale-105">
                <div>
                    <h3 class="text-lg font-medium">Pending</h3>
                    <p class="text-3xl font-bold mt-1"><?php echo $pendingCount; ?></p>
                </div>
                <i class="fas fa-clock text-3xl opacity-50"></i>
            </div>
            <!-- Canceled Appointments Card -->
            <div class="bg-red-500 text-white rounded-xl shadow-lg p-4 flex items-center justify-between transition-transform transform hover:scale-105">
                <div>
                    <h3 class="text-lg font-medium">Canceled</h3>
                    <p class="text-3xl font-bold mt-1"><?php echo $cancelledCount; ?></p>
                </div>
                <i class="fas fa-times-circle text-3xl opacity-50"></i>
            </div>
        </div>
    </div>
    
    <div class="appointmentview-table mt-8">
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
                            <td><?php echo $i++; ?></td>
                            <td><?php echo htmlspecialchars($appointment['patient_name']); ?></td>
                            <td>Dr.<?php echo htmlspecialchars($appointment['doctor_name']); ?></td>
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
