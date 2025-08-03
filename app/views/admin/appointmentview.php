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
                <label for="selectedDate">Selected Date:</label>
                <input type="date" id="selectedDate" class="date-input" value="<?php echo date('Y-m-d'); ?>">
            </div> 
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
        
        
  
    </div>
    
    <div class="appointmentview-table mt-8">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Patient Name</th>
                    <th>Doctor</th>
                    <th>Fees</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php if(!empty($data['appointments'])): ?>
                <?php foreach($data['appointments'] as $appointment): ?>
                    <tr data-date="<?= date('Y-m-d', strtotime($appointment['appointment_date'])) ?>">
                        <td><?php echo $i++; ?></td>
                        <td>📅 <?= date('F j, Y', strtotime($appointment['appointment_date'])) ?></td>
                        <td><?php echo htmlspecialchars(date('h:i A', strtotime($appointment['appointment_time']))); ?></td>
                        <td><?php echo htmlspecialchars($appointment['patient_name']); ?></td>
                        <td>Dr.<?php echo htmlspecialchars($appointment['doctor_name']); ?></td>
                        <td class="fees"><?php echo htmlspecialchars($appointment['fee']); ?></td>
                        <td>
                            <span class="status-badge status-<?php echo strtolower($appointment['status_name']); ?>">
                                <?php echo htmlspecialchars($appointment['status_name']); ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align:center;">No appointments found.</td>
                </tr>
            <?php endif; ?>
        </tbody>

        </table>
    </div>
</div>

<script>
const statusFilter = document.getElementById("statusFilter");
const dateFilter = document.getElementById("selectedDate");
const tableRows = document.querySelectorAll("tbody tr");

// Apply both filters
function applyFilters() {
    const selectedStatus = statusFilter.value.toLowerCase();
    const selectedDate = dateFilter.value; // format: YYYY-MM-DD

    tableRows.forEach(row => {
        const statusBadge = row.querySelector(".status-badge");
        const rowStatus = statusBadge ? statusBadge.textContent.trim().toLowerCase() : "";
        const rowDate = row.getAttribute("data-date"); // already YYYY-MM-DD

        const statusMatch = (selectedStatus === "all" || rowStatus === selectedStatus);
        const dateMatch = (!selectedDate || rowDate === selectedDate);

        row.style.display = (statusMatch && dateMatch) ? "" : "none";
    });
}

// Run filters when status or date changes
statusFilter.addEventListener("change", applyFilters);
dateFilter.addEventListener("change", applyFilters);
</script>

