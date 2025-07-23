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
                    <input type="text" class="search-input" placeholder="Search patients...">
                </div>
            </div>

            <!-- Desktop Table View -->
            <div class="table-container">
                <table class="patient-table">
                    <thead>
                        <tr>
                            <th>Patient ID</th>
                            <th>Patient Name</th>
                            <th>Gender</th>
                            <th>Phone No</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="patientTableBody">
                        <tr>
                            <td><strong>#001</strong></td>
                            <td>John Doe</td>
                            <td>Male</td>
                            <td>+1 (555) 123-4567</td>
                            <td><a href="<?php echo URLROOT?>/pages/userprofile" class="view-btn">View</button></td>
                        </tr>
                        <tr>
                            <td><strong>#002</strong></td>
                            <td>Smith Wilson</td>
                            <td>Male</td>
                            <td>+1 (555) 987-6543</td>
                            <td><button class="view-btn">View</button></td>
                        </tr>
                        <tr>
                            <td><strong>#003</strong></td>
                            <td>John Anderson</td>
                            <td>Male</td>
                            <td>+1 (555) 456-7890</td>
                            <td><button class="view-btn">View</button></td>
                        </tr>
                        <tr>
                            <td><strong>#004</strong></td>
                            <td>Sarah Johnson</td>
                            <td>Female</td>
                            <td>+1 (555) 321-9876</td>
                            <td><button class="view-btn">View</button></td>
                        </tr>
                        <tr>
                            <td><strong>#005</strong></td>
                            <td>Michael Brown</td>
                            <td>Male</td>
                            <td>+1 (555) 654-3210</td>
                            <td><button class="view-btn">View</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="mobile-cards" id="mobileCards">
                <div class="patient-card">
                    <div class="patient-card-header">
                        <span class="patient-id">Patient #001</span>
                        <a href="<?php echo URLROOT;?>/pages/userprofile" class="view-btn">View</a>
                    </div>
                    <div class="patient-info">
                        <div class="info-item">
                            <span class="info-label">Full Name</span>
                            <span class="info-value">John Doe</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Gender</span>
                            <span class="info-value">Male</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Phone</span>
                            <span class="info-value">+1 (555) 123-4567</span>
                        </div>
                    </div>
                </div>

                <div class="patient-card">
                    <div class="patient-card-header">
                        <span class="patient-id">Patient #002</span>
                        <button class="view-btn">View</button>
                    </div>
                    <div class="patient-info">
                        <div class="info-item">
                            <span class="info-label">Full Name</span>
                            <span class="info-value">Smith Wilson</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Gender</span>
                            <span class="info-value">Male</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Phone</span>
                            <span class="info-value">+1 (555) 987-6543</span>
                        </div>
                    </div>
                </div>

                <div class="patient-card">
                    <div class="patient-card-header">
                        <span class="patient-id">Patient #003</span>
                        <button class="view-btn">View</button>
                    </div>
                    <div class="patient-info">
                        <div class="info-item">
                            <span class="info-label">Full Name</span>
                            <span class="info-value">John Anderson</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Gender</span>
                            <span class="info-value">Male</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Phone</span>
                            <span class="info-value">+1 (555) 456-7890</span>
                        </div>
                    </div>
                </div>

                <div class="patient-card">
                    <div class="patient-card-header">
                        <span class="patient-id">Patient #004</span>
                        <button class="view-btn">View</button>
                    </div>
                    <div class="patient-info">
                        <div class="info-item">
                            <span class="info-label">Full Name</span>
                            <span class="info-value">Sarah Johnson</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Gender</span>
                            <span class="info-value">Female</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Phone</span>
                            <span class="info-value">+1 (555) 321-9876</span>
                        </div>
                    </div>
                </div>

                <div class="patient-card">
                    <div class="patient-card-header">
                        <span class="patient-id">Patient #005</span>
                        <button class="view-btn">View</button>
                    </div>
                    <div class="patient-info">
                        <div class="info-item">
                            <span class="info-label">Full Name</span>
                            <span class="info-value">Michael Brown</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Gender</span>
                            <span class="info-value">Male</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Phone</span>
                            <span class="info-value">+1 (555) 654-3210</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

</body>
</html>