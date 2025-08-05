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
                            <span class="currency">$</span>
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

<style>
:root {
    --primary-green: #085a5e;
    --light-green: #D1FAE5;
    --dark-green: #059669;
    --green-hover: #047857;
    --pure-white: #FFFFFF;
    --light-gray: #F9FAFB;
    --medium-gray: #9CA3AF;
    --dark-gray: #374151;
    --text-primary: #111827;
    --text-secondary: #6B7280;
    --border-light: #E5E7EB;
    --error-red: #DC2626;
    --error-light: #FEF2F2;
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

* {
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif;
    background: var(--light-gray);
    margin: 0;
    padding: 0;
    min-height: 100vh;
}

.page-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 1rem;
}

/* Alert Styles */
.alert {
    margin: 1rem 0;
    border-radius: 0.75rem;
    overflow: hidden;
    box-shadow: var(--shadow-md);
    animation: slideDown 0.3s ease;
}

.alert-content {
    display: flex;
    align-items: center;
    padding: 1rem 1.25rem;
    gap: 0.75rem;
}

.alert-error {
    background: var(--error-light);
    border-left: 4px solid var(--error-red);
    color: var(--error-red);
}

.alert-success {
    background: var(--light-green);
    border-left: 4px solid var(--primary-green);
    color: var(--dark-green);
}

.alert-close {
    margin-left: auto;
    background: none;
    border: none;
    color: inherit;
    cursor: pointer;
    padding: 0.25rem;
    border-radius: 0.25rem;
    transition: background-color 0.2s;
}

.alert-close:hover {
    background: rgba(0, 0, 0, 0.1);
}

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Form Container */
.form-container {
    background: var(--pure-white);
    border-radius: 1rem;
    box-shadow: var(--shadow-lg);
    overflow: hidden;
    border: 1px solid var(--border-light);
}

/* Form Header */
.form-header {
    background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
    color: var(--pure-white);
    padding: 2rem;
}

.header-content {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.header-icon {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.header-text h1 {
    margin: 0;
    font-size: clamp(1.5rem, 4vw, 2rem);
    font-weight: 700;
}

.header-text p {
    margin: 0.5rem 0 0 0;
    opacity: 0.9;
    font-size: 1rem;
}

/* Form */
.doctor-form {
    padding: 2rem;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(min(100%, 400px), 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.form-section {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.section-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--light-green);
}

.section-header i {
    color: var(--primary-green);
    font-size: 1.25rem;
}

.section-header h3 {
    margin: 0;
    color: var(--text-primary);
    font-size: 1.25rem;
    font-weight: 600;
}

/* Photo Upload */
.photo-upload-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: var(--light-gray);
    border-radius: 0.75rem;
    border: 2px dashed var(--border-light);
}

.photo-preview {
    position: relative;
}

.photo-placeholder {
    width: 120px;
    height: 120px;
    background: var(--light-green);
    border-radius: 0.75rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    color: var(--primary-green);
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.photo-placeholder:hover {
    background: #A7F3D0;
    transform: scale(1.02);
}

.photo-placeholder i {
    font-size: 2rem;
}

#photo-preview {
    width: 120px;
    height: 120px;
    border-radius: 0.75rem;
    object-fit: cover;
    border: 3px solid var(--light-green);
}

.upload-controls {
    text-align: center;
}

.upload-controls small {
    display: block;
    color: var(--text-secondary);
    margin-top: 0.5rem;
    font-size: 0.875rem;
}

/* Input Groups */
.input-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.input-group label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.875rem;
}

.input-group label i {
    color: var(--primary-green);
    width: 16px;
}

.input-group input,
.input-group select,
.input-group textarea {
    padding: 0.875rem 1rem;
    border: 2px solid var(--border-light);
    border-radius: 0.5rem;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: var(--pure-white);
    resize: vertical;
}

.input-group input:focus,
.input-group select:focus,
.input-group textarea:focus {
    outline: none;
    border-color: var(--primary-green);
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

.input-group input::placeholder,
.input-group textarea::placeholder {
    color: var(--medium-gray);
}

/* Password Input */
.password-input {
    position: relative;
}

.password-toggle {
    position: absolute;
    right: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--medium-gray);
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 0.25rem;
    transition: color 0.3s ease;
}

.password-toggle:hover {
    color: var(--primary-green);
}

/* Fee Input */
.fee-input {
    position: relative;
    display: flex;
    align-items: center;
}

.currency {
    position: absolute;
    left: 1rem;
    color: var(--medium-gray);
    font-weight: 600;
    z-index: 1;
}

.fee-input input {
    padding-left: 2rem;
}

/* Radio Groups */
.radio-group {
    display: flex;
    gap: 1rem;
    margin-top: 0.5rem;
}

.radio-option {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    padding: 0.75rem 1rem;
    border: 2px solid var(--border-light);
    border-radius: 0.5rem;
    transition: all 0.3s ease;
    flex: 1;
    justify-content: center;
}

.radio-option:hover {
    border-color: var(--primary-green);
    background: var(--light-green);
}

.radio-option input[type="radio"] {
    display: none;
}

.radio-custom {
    width: 18px;
    height: 18px;
    border: 2px solid var(--border-light);
    border-radius: 50%;
    position: relative;
    transition: all 0.3s ease;
}

.radio-option input[type="radio"]:checked + .radio-custom {
    border-color: var(--primary-green);
    background: var(--primary-green);
}

.radio-option input[type="radio"]:checked + .radio-custom::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 8px;
    height: 8px;
    background: var(--pure-white);
    border-radius: 50%;
}

.radio-option input[type="radio"]:checked ~ * {
    color: var(--primary-green);
}

/* Time Group */
.time-group {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

/* Error Messages */
.error-message {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--error-red);
    font-size: 0.875rem;
    font-weight: 500;
    padding: 0.5rem;
    background: var(--error-light);
    border-radius: 0.375rem;
    border-left: 3px solid var(--error-red);
}

/* Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.875rem 1.5rem;
    border: none;
    border-radius: 0.5rem;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    justify-content: center;
}

.btn-primary {
    background: var(--primary-green);
    color: var(--pure-white);
    box-shadow: var(--shadow-md);
}

.btn-primary:hover {
    background: var(--dark-green);
    transform: translateY(-1px);
    box-shadow: var(--shadow-lg);
}

.btn-secondary {
    background: var(--pure-white);
    color: var(--text-secondary);
    border: 2px solid var(--border-light);
}

.btn-secondary:hover {
    border-color: var(--medium-gray);
    color: var(--text-primary);
}

.btn-outline {
    background: var(--pure-white);
    color: var(--primary-green);
    border: 2px solid var(--primary-green);
}

.btn-outline:hover {
    background: var(--primary-green);
    color: var(--pure-white);
}

/* Form Actions */
.form-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 2rem;
    border-top: 2px solid var(--light-gray);
    gap: 1rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .page-container {
        padding: 0.75rem;
    }

    .form-header {
        padding: 1.5rem;
    }

    .header-content {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }

    .doctor-form {
        padding: 1.5rem;
    }

    .form-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .radio-group {
        flex-direction: column;
    }

    .time-group {
        grid-template-columns: 1fr;
    }

    .form-actions {
        flex-direction: column-reverse;
        gap: 1rem;
    }

    .form-actions .btn {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .photo-placeholder {
        width: 100px;
        height: 100px;
    }

    #photo-preview {
        width: 100px;
        height: 100px;
    }
}
</style>

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
        passwordIcon.className = 'fa fa-eye-slash';
    } else {
        passwordInput.type = 'password';
        passwordIcon.className = 'fa fa-eye';
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