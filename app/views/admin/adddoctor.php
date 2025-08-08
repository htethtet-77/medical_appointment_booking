<?php
$selectedSpecialty = $_POST['specialty'] ?? '';
$selectedDegree = $_POST['degree'] ?? '';

$degreeOptions = [
    "General Physician" => ["MBBS", "MD", "DO"],
    "Dentist" => ["BDS", "MDS", "DDS"],
    "Pediatrician" => ["MBBS", "MD Pediatrics", "DCH"],
    "Dermatologist" => ["MBBS", "MD Dermatology", "DDVL"]
];
?>

<title><?php echo SITENAME; ?></title>
<?php require APPROOT . '/views/inc/sidebar.php'; ?>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/adddoctor.css">

<?php
if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-error">
            <div class="alert-content">
                <i class="fa fa-exclamation-circle"></i>
                <span>'.htmlspecialchars($_SESSION['error']).'</span>
                <button onclick="this.parentElement.parentElement.remove();" class="alert-close">
                    <i class="fa fa-times"></i>
                </button>
            </div>
          </div>';
    unset($_SESSION['error']);
}

if (isset($_SESSION['success'])) {
    echo '<div class="alert alert-success">
            <div class="alert-content">
                <i class="fa fa-check-circle"></i>
                <span>'.htmlspecialchars($_SESSION['success']).'</span>
                <button onclick="this.parentElement.parentElement.remove();" class="alert-close">
                    <i class="fa fa-times"></i>
                </button>
            </div>
          </div>';
    unset($_SESSION['success']);
}
?>

<div class="page-container">
    <div class="form-container">
        <div class="form-header">
            <div class="header-content">
                <div class="header-icon">
                    <i class="fa fa-user-plus"></i>
                </div>
                <div class="header-text">
                    <h1>Add New Doctor</h1>
                    <p>Fill in the information below to add a new doctor to the system</p>
                </div>
            </div>
        </div>

        <form action="<?php echo URLROOT; ?>/admin/adddoctor" method="POST" enctype="multipart/form-data" class="doctor-form">
            <div class="form-grid">
                <!-- Personal Information Section -->
                <div class="form-section">
                    <div class="section-header">
                        <i class="fa fa-user"></i>
                        <h3>Personal Information</h3>
                    </div>

                    <!-- Photo Upload -->
                    <div class="photo-upload-section">
                        <div class="photo-preview">
                            <div class="photo-placeholder" id="photo-placeholder">
                                <i class="fa fa-camera"></i>
                                <span>Upload Photo</span>
                            </div>
                            <img id="photo-preview" style="display: none;" alt="Doctor Photo">
                        </div>
                        <div class="upload-controls">
                            <input type="file" name="image" id="profile-photo" accept="image/*" hidden>
                            <button type="button" class="btn btn-outline" onclick="document.getElementById('profile-photo').click()">
                                <i class="fa fa-upload"></i> Choose Photo
                            </button>
                            <small>Recommended: Square image, max 2MB</small>
                        </div>
                    </div>

                    <!-- Name -->
                    <div class="input-group">
                        <label for="name">
                            <i class="fa fa-user"></i>
                            Doctor Name
                        </label>
                        <input type="text" name="name" id="name" value="<?= $_POST['name'] ?? '' ?>" required placeholder="Enter full name">
                        <?php if (isset($data['name-err'])): ?>
                            <div class="error-message">
                                <i class="fa fa-exclamation-triangle"></i>
                                <?= $data['name-err']; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Email -->
                    <div class="input-group">
                        <label for="doctor-email">
                            <i class="fa fa-envelope"></i>
                            Email Address
                        </label>
                        <input type="email" name="email" id="doctor-email" value="<?= $_POST['email'] ?? '' ?>" required placeholder="doctor@example.com">
                        <?php if (isset($data['email-err'])): ?>
                            <div class="error-message">
                                <i class="fa fa-exclamation-triangle"></i>
                                <?= $data['email-err']; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Gender -->
                    <div class="input-group">
                        <label>
                            <i class="fa fa-venus-mars"></i>
                            Gender
                        </label>
                        <div class="radio-group">
                            <label class="radio-option">
                                <input type="radio" name="gender" value="male" <?= ($_POST['gender'] ?? '') == 'male' ? 'checked' : '' ?>>
                                <span class="radio-custom"></span>
                                <i class="fa fa-mars"></i> Male
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="gender" value="female" <?= ($_POST['gender'] ?? '') == 'female' ? 'checked' : '' ?>>
                                <span class="radio-custom"></span>
                                <i class="fa fa-venus"></i> Female
                            </label>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="input-group">
                        <label for="phone">
                            <i class="fa fa-phone"></i>
                            Phone Number
                        </label>
                        <input type="text" name="phone" id="phone" value="<?= $_POST['phone'] ?? '' ?>" required placeholder="+1 (555) 123-4567">
                        <?php if (isset($data['phone-err'])): ?>
                            <div class="error-message">
                                <i class="fa fa-exclamation-triangle"></i>
                                <?= $data['phone-err']; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Password -->
                    <div class="input-group">
                        <label for="password">
                            <i class="fa fa-lock"></i>
                            Password
                        </label>
                        <div class="password-input">
                            <input type="password" name="password" id="password" required placeholder="Enter secure password">
                            <button type="button" class="password-toggle" onclick="togglePassword()">
                                <i class="fa fa-eye" id="password-icon"></i>
                            </button>
                        </div>
                        <?php if (isset($data['password-err'])): ?>
                            <div class="error-message">
                                <i class="fa fa-exclamation-triangle"></i>
                                <?= $data['password-err']; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Professional Information Section -->
                <div class="form-section">
                    <div class="section-header">
                        <i class="fa fa-stethoscope"></i>
                        <h3>Professional Information</h3>
                    </div>

                    <!-- Specialty -->
                    <div class="input-group">
                        <label for="specialty">
                            <i class="fa fa-graduation-cap"></i>
                            Specialty
                        </label>
                        <select name="specialty" id="specialty" required onchange="updateDegrees()">
                            <option value="">-- Select Specialty --</option>
                            <?php foreach ($degreeOptions as $spec => $degrees): ?>
                                <option value="<?= $spec ?>" <?= $selectedSpecialty == $spec ? 'selected' : '' ?>><?= $spec ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Degree -->
                    <div class="input-group">
                        <label for="degree">
                            <i class="fa fa-certificate"></i>
                            Degree
                        </label>
                        <select name="degree" id="degree" required>
                            <option value="">-- Select Degree --</option>
                            <?php if ($selectedSpecialty && isset($degreeOptions[$selectedSpecialty])): ?>
                                <?php foreach ($degreeOptions[$selectedSpecialty] as $deg): ?>
                                    <option value="<?= $deg ?>" <?= $selectedDegree == $deg ? 'selected' : '' ?>><?= $deg ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Experience -->
                    <div class="input-group">
                        <label for="experience">
                            <i class="fa fa-clock-o"></i>
                            Years of Experience
                        </label>
                        <input type="number" name="experience" id="experience" value="<?= $_POST['experience'] ?? '' ?>" placeholder="e.g., 5" min="0" max="50">
                    </div>

                    <!-- Fees -->
                    <div class="input-group">
                        <label for="fees">
                            <i class="fa fa-dollar"></i>
                            Consultation Fee
                        </label>
                        <div class="fee-input">
                            <span class="currency"></span>
                            <input type="number" name="fee" id="fees" required placeholder="0.00" min="0" step="0.01">
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="input-group">
                        <label for="address">
                            <i class="fa fa-map-marker"></i>
                            Clinic Address
                        </label>
                        <textarea name="address" id="address" required placeholder="Enter clinic address" rows="3"></textarea>
                    </div>

                    <!-- Working Hours -->
                    <div class="time-group">
                        <div class="input-group">
                            <label for="start-time">
                                <i class="fa fa-clock-o"></i>
                                Start Time
                            </label>
                            <input type="time" id="start-time" name="start_time" required>
                        </div>

                        <div class="input-group">
                            <label for="end-time">
                                <i class="fa fa-clock-o"></i>
                                End Time
                            </label>
                            <input type="time" id="end-time" name="end_time" required>
                        </div>
                    </div>

                    <!-- Bio -->
                    <div class="input-group">
                        <label for="about-doctor">
                            <i class="fa fa-info-circle"></i>
                            About Doctor
                        </label>
                        <textarea name="bio" id="about-doctor" value="<?= $_POST['bio'] ?? '' ?>" placeholder="Brief description about the doctor..." rows="4"></textarea>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="<?php echo URLROOT; ?>/admin/doctorlist" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Add Doctor
                </button>
            </div>
        </form>
    </div>
</div>



<script>
const degreeOptions = <?php echo json_encode($degreeOptions); ?>;

function updateDegrees() {
    const specialty = document.getElementById("specialty").value;
    const degreeSelect = document.getElementById("degree");
    const selectedDegree = "<?php echo $selectedDegree; ?>";

    degreeSelect.innerHTML = '<option value="">-- Select Degree --</option>';

    if (degreeOptions[specialty]) {
        degreeOptions[specialty].forEach(function (deg) {
            const option = document.createElement("option");
            option.value = deg;
            option.textContent = deg;
            if (deg === selectedDegree) {
                option.selected = true;
            }
            degreeSelect.appendChild(option);
        });
    }
}

function togglePassword() {
    const passwordInput = document.getElementById('password');
    const passwordIcon = document.getElementById('password-icon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        passwordIcon.className = 'fa fa-eye';
    } else {
        passwordInput.type = 'password';
        passwordIcon.className = 'fa fa-eye-slash';
    }
}

// Photo preview functionality
document.getElementById('profile-photo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('photo-preview');
    const placeholder = document.getElementById('photo-placeholder');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            placeholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
});

// Form validation enhancement - NO ALERT BOXES
document.querySelector('.doctor-form').addEventListener('submit', function(e) {
    const startTime = document.getElementById('start-time').value;
    const endTime = document.getElementById('end-time').value;
    
    if (startTime && endTime && startTime >= endTime) {
        e.preventDefault();
        // Show error message in the form instead of alert
        showFormError('End time must be after start time.');
        return false;
    }
});

// Function to show error messages in the form
function showFormError(message) {
    // Create error notification
    const notification = document.createElement('div');
    notification.className = 'alert alert-error';
    notification.innerHTML = `
        <div class="alert-content">
            <i class="fa fa-exclamation-circle"></i>
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove();" class="alert-close">
                <i class="fa fa-times"></i>
            </button>
        </div>
    `;
    
    // Insert at the top of the form
    const container = document.querySelector('.page-container');
    container.insertBefore(notification, container.firstChild);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

document.addEventListener("DOMContentLoaded", updateDegrees);
</script>