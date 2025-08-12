<?php require APPROOT . '/views/inc/header.php';   
error_reporting(E_ALL);  
ini_set('display_error',1);  
?> 
<?php require APPROOT . '/views/inc/doctorbar.php'; ?>

<div class="profile-container">
    <h2>View Profile</h2>
    
    <!-- Display success/error messages -->
    <?php if(isset($_SESSION['password_success'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['password_success']; unset($_SESSION['password_success']); ?>
        </div>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['password_error'])): ?>
        <div class="alert alert-error">
            <?php echo $_SESSION['password_error']; unset($_SESSION['password_error']); ?>
        </div>
    <?php endif; ?>
    
    <div class="profile-content">
        <div class="profile-sidebar">
            <div class="profile-picture">
                <img src="/<?= htmlspecialchars($doctor['profile_image'])?>" alt="Doctor Image" class="w-full h-full object-cover" />
            </div>
        </div>
        <div class="profile-details">
            <div class="detail-row">
                <label for="name">Name :</label>
                <input type="text" class="readonly-box" value="<?php echo htmlspecialchars($doctor['name']); ?>" readonly>
            </div>
            <div class="detail-row">
                <label for="specialty">Specialty:</label>
                <input type="text" class="readonly-box" value="<?php echo htmlspecialchars($doctor['specialty']); ?>" readonly>
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
                <input type="text" class="readonly-box" value="<?php echo htmlspecialchars($doctor['experience']); ?> Years" readonly>
            </div>
            <div class="detail-row">
                <label for="location">Location :</label>
                <input type="text" class="readonly-box" value="<?php echo htmlspecialchars($doctor['address']); ?>" readonly>
            </div>
        </div>
    </div>
    
    <div class="profile-actions">
        <button id="changePasswordBtn" class="btn change-password-btn">Change Password</button>
        <a href="<?php echo URLROOT; ?>/auth/logout" class="btn logout-btn">Logout</a>
    </div>
    
    <a href="<?php echo URLROOT; ?>/doctor/dash" class="btn back-btn">⬅ Back</a>
</div>

<!-- Change Password Modal -->
<div id="changePasswordModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Change Password</h3>
        <form id="changePasswordForm" method="POST" action="<?php echo URLROOT; ?>/doctor/changePassword">
            <div class="form-group">
                <label for="current_password">Current Password:</label>
                <input type="text" id="current_password" name="current_password" required>
            </div>
            <div class="form-group">
                <label for="new_password">New Password:</label>
                <input type="text" id="new_password" name="new_password" required minlength="8">
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm New Password:</label>
                <input type="text" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Password</button>
                <button type="button" class="btn btn-secondary" id="cancelBtn">Cancel</button>
            </div>
        </form>
    </div>
</div>

<style>
.alert {
    padding: 10px;
    margin: 10px 0;
    border-radius: 4px;
    font-weight: bold;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.change-password-btn {
    background-color: #007bff;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-right: 10px;
}

.change-password-btn:hover {
    background-color: #0056b3;
}

.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 400px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.form-group input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
}

.form-actions {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    margin-top: 20px;
}

.btn-primary {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    cursor: pointer;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    cursor: pointer;
}

.btn-secondary:hover {
    background-color: #545b62;
}
</style>

<script>
// Modal functionality
const modal = document.getElementById('changePasswordModal');
const btn = document.getElementById('changePasswordBtn');
const closeBtn = document.getElementsByClassName('close')[0];
const cancelBtn = document.getElementById('cancelBtn');
const form = document.getElementById('changePasswordForm');

btn.onclick = function() {
    modal.style.display = 'block';
}

closeBtn.onclick = function() {
    modal.style.display = 'none';
    form.reset();
}

cancelBtn.onclick = function() {
    modal.style.display = 'none';
    form.reset();
}

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = 'none';
        form.reset();
    }
}

// Password confirmation validation
form.onsubmit = function(e) {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (newPassword !== confirmPassword) {
        e.preventDefault();
        alert('New password and confirm password do not match!');
        return false;
    }
    
    if (newPassword.length < 8) {
        e.preventDefault();
        alert('Password must be at least 8 characters long!');
        return false;
    }
}
</script>

</body>
</html>