<title><?php echo SITENAME; ?></title>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/patientlist.css?v=2">
<?php require APPROOT . '/views/inc/sidebar.php'; ?>

<!-- Main Content -->
<main class="main-container">
    <div class="content-card">
        <div class="card-header">
            <h1 class="card-title">Patient Management</h1>
            <div class="search-container">
                <div class="search-icon">🔍</div>
                <input type="text" id="patientSearch" class="search-input" placeholder="Search patients...">
            </div>
        </div>
<?php $i=1;?>;
        <!-- Desktop Table View -->
        <div class="table-container">
            <table class="patient-table">
                <thead>
                    <tr>
                        <th>Patient ID</th>
                        <th>Patient Name</th>
                        <th>Gender</th>
                        <th>Email</th>
                        <th>Phone No</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="patientTableBody">
                    <?php if(!empty($data['user'])): ?>
                        <?php foreach($data['user'] as $patient): ?>
                            <tr>
                                <td><strong>#<?php echo $i++; ?></strong></td>
                                <td><?php echo htmlspecialchars($patient['name']); ?></td>
                                <td><?php echo htmlspecialchars($patient['gender']); ?></td>
                                <td><?php echo htmlspecialchars($patient['email']); ?></td>

                                <td><?php echo htmlspecialchars($patient['phone']); ?></td>
                                <td>
                                    <a href="<?php echo URLROOT ?>/pages/userprofile/<?php echo $patient['id']; ?>" class="view-btn">View</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align:center;">No patients found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="mobile-cards" id="mobileCards">
            <?php if(!empty($data['user'])): ?>
                <?php foreach($data['user'] as $patient): ?>
                    <div class="patient-card">
                        <div class="patient-card-header">
                            <span class="patient-id">Patient #<?php echo htmlspecialchars($patient->id); ?></span>
                            <a href="<?php echo URLROOT ?>/pages/userprofile/<?php echo $patient->id; ?>" class="view-btn">View</a>
                        </div>
                        <div class="patient-info">
                            <div class="info-item">
                                <span class="info-label">Full Name</span>
                                <span class="info-value"><?php echo htmlspecialchars($patient->fullname); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Gender</span>
                                <span class="info-value"><?php echo htmlspecialchars($patient->gender); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Phone</span>
                                <span class="info-value"><?php echo htmlspecialchars($patient->phone); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align:center;">No patients available.</p>
            <?php endif; ?>
        </div>
    </div>
</main>

<script>
// Patient search filter (works for both desktop and mobile)
document.getElementById('patientSearch').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let tableRows = document.querySelectorAll('#patientTableBody tr');
    let cardItems = document.querySelectorAll('#mobileCards .patient-card');

    // Filter table rows
    tableRows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
    });

    // Filter mobile cards
    cardItems.forEach(card => {
        let text = card.textContent.toLowerCase();
        card.style.display = text.includes(filter) ? "" : "none";
    });
});
</script>
