 <?php require APPROOT . '/views/inc/header.php'; 
 error_reporting(E_ALL);
 ini_set('display_error',1);
 ?>
<?php require APPROOT . '/views/inc/doctorbar.php'; ?>

    <div class="profile-container">
        <h2>View Profile</h2>
        <div class="profile-content">
            <div class="profile-sidebar">
                <div class="profile-picture">
                    <i class="fas fa-image"></i>
                </div>
            </div>
            <div class="profile-details">
                <div class="detail-row">
                    <label for="name">Name :</label>
                    <input type="text" class="readonly-box" value="<?php echo htmlspecialchars($doctor['name']); ?>" readonly>
                </div>
                <div class="detail-row">
                    <label for="specialty">Specialty:</label>
                    <input type="text" class="readonly-box" value="<?php echo htmlspecialchars(string: $doctor['specialty']); ?>" readonly>
                </div>
                <div class="detail-row">
                    <label for="email">Email:</label>
                    <input type="text" class="readonly-box" value="<?php echo htmlspecialchars($doctor['email']); ?>" readonly>
                </div>
                <div class="detail-row">
                    <label for="phno">Ph No :</label>
                    <input type="text" class="readonly-box" value="<?php echo htmlspecialchars($doctor['phone']); ?>" readonly>
                </div>
                <div class="detail-row">
                    <label for="exp">Exp :</label>
                    <input type="text" class="readonly-box" value="<?php echo htmlspecialchars($doctor['experience']); ?>" readonly>
                </div>
                <div class="detail-row">
                    <label for="location">Location :</label>
                    <input type="text" class="readonly-box" value="<?php echo htmlspecialchars($doctor['address']); ?>" readonly>
                </div>
            </div>
        </div>
        <div class="profile-actions">
             <a href="<?php echo URLROOT; ?>/auth/logout" class="btn logout-btn">Logout</a>

        </div>
        <div class="warning-message">
            <i class="fas fa-exclamation-triangle"></i>
            <span>Warning: Deleting your profile will permanently remove your account, cancel all future appointments, and erase your data from the system. This action cannot be undone.</span>
        </div>
    </div>
    
</body>
</html>