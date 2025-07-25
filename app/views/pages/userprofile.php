 <?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/navbar.php'; ?>

<body>
    <div class="user-main-content">
        <div class="container profile-container">
            <h2 class="profile-title">View Profile</h2>
            <div class="profile-card">
                <div class="profile-left">
                    <div class="profile-image-placeholder">
                        <i class="fas fa-image"></i>
                    </div>
                    <a href="<?php echo URLROOT;?>/pages/userappointment" class="appointment-history-button">
                        Appointment History</a>
                </div>
                <div class="profile-right">
                    <div class="profile-info-group">
                        <label for="name">Name :</label>
                        <input type="text" class="readonly-box" value="<?php echo htmlspecialchars($user['name']); ?>" readonly>
    
                       
                    </div>
                    <div class="profile-info-group">
                        <label for="email">Email:</label>
                        <input type="text" class="readonly-box" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>

                    </div>
                    <!-- <div class="profile-info-group">
                        <label for="dob">Date of birth:</label>
                        <span><?php echo htmlspecialchars($user['email']); ?></span>
                    </div> -->
                    <div class="profile-info-group">
                        <label for="gender">Gender:</label>
                        <input type="text" class="readonly-box" value="<?php echo htmlspecialchars($user['gender']); ?>" readonly>
                    </div>
                    <div class="profile-info-group">
                        <label for="phone">Ph No:</label>
                        <input type="text" class="readonly-box" value="<?php echo htmlspecialchars($user['phone']); ?>" readonly>
                    </div>
                </div>
            </div>
            <div class="profile-actions">
                <a href="<?php echo URLROOT; ?>/auth/logout" class="action-button logout-button">Logout</a>
            </div>
        </div>
    </div>

   <?php require APPROOT . '/views/inc/footer.php'; ?>