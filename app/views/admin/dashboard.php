 <?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/sidebar.php'; ?>
    <div class="dashboard-container">

        <!-- Stats Cards -->
        <div class="stats-container">
            <div class="stat-card doctors">
                <div class="stat-icon"></div>
                <div class="stat-number">8</div>
                <div class="stat-label">Total Doctors</div>
            </div>
            <div class="stat-card patients">
                <div class="stat-icon"></div>
                <div class="stat-number">6</div>
                <div class="stat-label">Total Patients</div>
            </div>
            <div class="stat-card history">
                <div class="stat-icon"></div>
                <div class="stat-number">6</div>
                <div class="stat-label">History Records</div>
            </div>
        </div>

        <!-- Appointment Request Section -->
        <section class="appointment-section">
            <h2 class="section-title">Appointment Requests</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Patient Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>1</strong></td>
                            <td>John Doe</td>
                            <td>Dr.Daniel@clinic.com</td>
                            <td><span class="status-badge">$120</span></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="#" class="btn btn-approve">Approve</a>
                                    <a href="#" class="btn btn-cancel">Cancel</a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>2</strong></td>
                            <td>Jane Smith</td>
                            <td>Dr.Daniel@clinic.com</td>
                            <td><span class="status-badge">$120</span></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="#" class="btn btn-approve">Approve</a>
                                    <a href="#" class="btn btn-cancel">Cancel</a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>3</strong></td>
                            <td>Michael Johnson</td>
                            <td>Dr.Daniel@clinic.com</td>
                            <td><span class="status-badge">$120</span></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="#" class="btn btn-approve">Approve</a>
                                    <a href="#" class="btn btn-cancel">Cancel</a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</body>
</html>