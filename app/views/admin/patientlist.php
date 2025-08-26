<title><?php echo SITENAME; ?></title>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/appointmentview.css">
<?php require APPROOT . '/views/inc/sidebar.php'; ?>

<div class="app-container">
    <!-- Header with filters -->
    <div class="appointment-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
        <h1 class="appointment-title" style="margin: 0;">Patient Management</h1>
        
        <div class="filters" style="display: flex; gap: 1rem; align-items: center;">
            <div class="filter-group" style="display: flex; align-items: center; gap: 0.5rem;">
                <label for="patientSearch">Search:</label>
                <input type="text" id="patientSearch" class="date-input" placeholder="Search patients...">
            </div>
            <div class="filter-group" style="display: flex; align-items: center; gap: 0.5rem;">
                <label for="statusFilter">Status:</label>
                <select id="statusFilter" class="status-select">
                    <option value="all">All Patients</option>
                    <option value="active">Active</option>
                    <option value="not active">Not Active</option>
                </select>
            </div>
        </div>
    </div>

    <?php $i = 1; ?>
    <div class="appointmentview-table">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Patient Name</th>
                    <th>Gender</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="patientTableBody">
                <?php if (!empty($data['user'])): ?>
                    <?php foreach ($data['user'] as $patient): ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo htmlspecialchars($patient['name']); ?></td>
                            <td><?php echo htmlspecialchars($patient['gender']); ?></td>
                            <td><?php echo htmlspecialchars($patient['email']); ?></td>
                            <td><?php echo htmlspecialchars($patient['phone']); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $patient['is_login'] == 1 ? 'confirmed' : 'cancelled'; ?>">
                                    <?php echo $patient['is_login'] == 1 ? 'Active' : 'Not Active'; ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="empty-state">
                            <div class="empty-state-icon">👤</div>
                            No patients found.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- JS for search + filter -->
<script>
const searchInput = document.getElementById("patientSearch");
const statusFilter = document.getElementById("statusFilter");
const tableRows = document.querySelectorAll("#patientTableBody tr");

// Search Filter
searchInput.addEventListener("keyup", function() {
    const filter = this.value.toLowerCase();
    tableRows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
    });
});

// Status Filter
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
