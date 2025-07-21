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
                    <button class="appointment-history-button">
                        Appointment History
                    </button>
                </div>
                <div class="profile-right">
                    <div class="profile-info-group">
                        <label for="name">Name :</label>
                        <input type="text" id="name" value="John" readonly>
                    </div>
                    <div class="profile-info-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" value="John@gmail.com" readonly>
                    </div>
                    <div class="profile-info-group">
                        <label for="dob">Date of birth:</label>
                        <input type="text" id="dob" value="09 123 456 78" readonly>
                    </div>
                    <div class="profile-info-group">
                        <label for="gender">Gender:</label>
                        <input type="text" id="gender" value="Male" readonly>
                    </div>
                    <div class="profile-info-group">
                        <label for="phone">Ph No:</label>
                        <input type="tel" id="phone" value="09 123 456 78" readonly>
                    </div>
                </div>
            </div>
            <div class="profile-actions">
                <button class="action-button logout-button">Logout</button>
            </div>
        </div>
    </div>

   <?php require APPROOT . '/views/inc/footer.php'; ?>