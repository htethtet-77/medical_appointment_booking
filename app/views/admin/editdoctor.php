<?php
$selectedSpecialty = $_POST['specialty'] ?? $data['doctorprofile']['specialty'] ?? '';
$selectedDegree = $_POST['degree'] ?? $data['doctorprofile']['degree'] ?? '';

$degreeOptions = [
    "General Physician" => ["MBBS", "MD", "DO"],
    "Dentist" => ["BDS", "MDS", "DDS"],
    "Pediatrician" => ["MBBS", "MD Pediatrics", "DCH"],
    "Dermatologist" => ["MBBS", "MD Dermatology", "DDVL"],
    "Cardiologist" => ["MBBS", "MD Cardiology", "DM Cardiology"],
    "Neurologist" => ["MBBS", "MD Neurology", "DM Neurology"],
    "Orthopedic" => ["MBBS", "MS Orthopedics", "DNB Orthopedics"]
];

// CSRF token for security
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<title><?php echo htmlspecialchars(SITENAME); ?></title>
<?php require APPROOT . '/views/inc/sidebar.php'; ?>

<!-- Alert Messages -->
<?php
function displayAlert($type, $sessionKey) {
    if (isset($_SESSION[$sessionKey])) {
        $icon = $type === 'error' ? 'fa-exclamation-circle' : 'fa-check-circle';
        echo '<div class="alert alert-' . $type . '" role="alert">
                <div class="alert-content">
                    <i class="fa ' . $icon . '" aria-hidden="true"></i>
                    <span>' . htmlspecialchars($_SESSION[$sessionKey]) . '</span>
                    <button onclick="this.parentElement.parentElement.remove();" class="alert-close" aria-label="Close alert">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </button>
                </div>
              </div>';
        unset($_SESSION[$sessionKey]);
    }
}

displayAlert('error', 'error');
displayAlert('success', 'success');
?>

<div class="page-container">
    <div class="form-container">
        <div class="form-header">
            <div class="header-content">
                <div class="header-icon">
                    <i class="fa fa-user-edit" aria-hidden="true"></i>
                </div>
                <div class="header-text">
                    <h1>Update Doctor</h1>
                    <p>Edit doctor information and save changes</p>
                </div>
            </div>
        </div>

        <form action="<?php echo htmlspecialchars(URLROOT); ?>/admin/updatedoctor" method="POST" enctype="multipart/form-data" class="doctor-form" novalidate>
            <!-- CSRF Protection -->
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="hidden" name="id" value="<?= htmlspecialchars($data['users']['id']) ?>">
            
            <div class="form-grid">
                <!-- Personal Information Section -->
                <div class="form-section">
                    <div class="section-header">
                        <i class="fa fa-user" aria-hidden="true"></i>
                        <h3>Personal Information</h3>
                    </div>

                    <!-- Photo Upload -->
                    <div class="photo-upload-section">
                        <div class="photo-preview">
                            <?php if (!empty($data['users']['profile_image'])): ?>
                                <img id="photo-preview" src="<?= htmlspecialchars(URLROOT . '/' . $data['users']['profile_image']) ?>" alt="Current doctor photo">
                                <div class="photo-placeholder" id="photo-placeholder" style="display: none;">
                                    <i class="fa fa-camera" aria-hidden="true"></i>
                                    <span>Change Photo</span>
                                </div>
                            <?php else: ?>
                                <div class="photo-placeholder" id="photo-placeholder">
                                    <i class="fa fa-camera" aria-hidden="true"></i>
                                    <span>Upload Photo</span>
                                </div>
                                <img id="photo-preview" style="display: none;" alt="Doctor photo preview">
                            <?php endif; ?>
                        </div>
                        <div class="upload-controls">
                            <input type="file" name="image" id="profile-photo" accept="image/jpeg,image/png,image/webp" aria-describedby="photo-help" hidden>
                            <button type="button" class="btn btn-outline" onclick="document.getElementById('profile-photo').click()">
                                <i class="fa fa-upload" aria-hidden="true"></i> 
                                <?= !empty($data['users']['profile_image']) ? 'Change Photo' : 'Choose Photo' ?>
                            </button>
                            <small id="photo-help">Recommended: Square image, max 2MB (JPEG, PNG, WebP)</small>
                        </div>
                    </div>

                    <!-- Name -->
                    <div class="input-group">
                        <label for="name">
                            <i class="fa fa-user" aria-hidden="true"></i>
                            Doctor Name *
                        </label>
                        <input type="text" name="name" id="name" value="<?= htmlspecialchars($data['users']['name']) ?>" required placeholder="Enter full name" autocomplete="name" maxlength="100">
                        <?php if (isset($data['name-err'])): ?>
                            <div class="error-message" role="alert">
                                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                                <?= htmlspecialchars($data['name-err']); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Email -->
                    <div class="input-group">
                        <label for="doctor-email">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                            Email Address *
                        </label>
                        <input type="email" name="email" id="doctor-email" value="<?= htmlspecialchars($data['users']['email']) ?>" required placeholder="doctor@example.com" autocomplete="email" maxlength="255">
                        <?php if (isset($data['email-err'])): ?>
                            <div class="error-message" role="alert">
                                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                                <?= htmlspecialchars($data['email-err']); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Gender -->
                    <fieldset class="input-group">
                        <legend>
                            <i class="fa fa-venus-mars" aria-hidden="true"></i>
                            Gender *
                        </legend>
                        <div class="radio-group" role="radiogroup">
                            <label class="radio-option">
                                <input type="radio" name="gender" value="male" <?= $data['users']['gender'] == 'male' ? 'checked' : '' ?> required>
                                <span class="radio-custom"></span>
                                <i class="fa fa-mars" aria-hidden="true"></i> Male
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="gender" value="female" <?= $data['users']['gender'] == 'female' ? 'checked' : '' ?> required>
                                <span class="radio-custom"></span>
                                <i class="fa fa-venus" aria-hidden="true"></i> Female
                            </label>
                       
                        </div>
                    </fieldset>

                    <!-- Phone -->
                    <div class="input-group">
                        <label for="phone">
                            <i class="fa fa-phone" aria-hidden="true"></i>
                            Phone Number *
                        </label>
                        <input type="tel" name="phone" id="phone" value="<?= htmlspecialchars($data['users']['phone']) ?>" required placeholder="+1 (555) 123-4567" autocomplete="tel" pattern="[\+]?[0-9\s\-\(\)]{10,20}">
                        <?php if (isset($data['phone-err'])): ?>
                            <div class="error-message" role="alert">
                                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                                <?= htmlspecialchars($data['phone-err']); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Password -->
                    <div class="input-group">
                        <label for="password">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                            Password
                            <small class="optional-label">(Leave blank to keep current)</small>
                        </label>
                        <div class="password-input">
                            <input type="password" name="password" id="password" placeholder="Enter new password (optional)" autocomplete="new-password" minlength="8">
                            <button type="button" class="password-toggle" onclick="togglePassword()" aria-label="Toggle password visibility">
                                <i class="fa fa-eye" id="password-icon" aria-hidden="true"></i>
                            </button>
                        </div>
                        <small class="input-help">Only fill this if you want to change the password. Minimum 8 characters.</small>
                        <?php if (isset($data['password-err'])): ?>
                            <div class="error-message" role="alert">
                                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                                <?= htmlspecialchars($data['password-err']); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Professional Information Section -->
                <div class="form-section">
                    <div class="section-header">
                        <i class="fa fa-stethoscope" aria-hidden="true"></i>
                        <h3>Professional Information</h3>
                    </div>

                    <!-- Specialty -->
                    <div class="input-group">
                        <label for="specialty">
                            <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                            Specialty *
                        </label>
                        <select name="specialty" id="specialty" required onchange="updateDegrees()" aria-describedby="specialty-help">
                            <option value="">-- Select Specialty --</option>
                            <?php foreach ($degreeOptions as $spec => $degrees): ?>
                                <option value="<?= htmlspecialchars($spec) ?>" <?= $selectedSpecialty == $spec ? 'selected' : '' ?>><?= htmlspecialchars($spec) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small id="specialty-help" class="input-help">Select your medical specialty</small>
                    </div>

                    <!-- Degree -->
                    <div class="input-group">
                        <label for="degree">
                            <i class="fa fa-certificate" aria-hidden="true"></i>
                            Degree *
                        </label>
                        <select name="degree" id="degree" required aria-describedby="degree-help">
                            <option value="">-- Select Degree --</option>
                            <?php if ($selectedSpecialty && isset($degreeOptions[$selectedSpecialty])): ?>
                                <?php foreach ($degreeOptions[$selectedSpecialty] as $deg): ?>
                                    <option value="<?= htmlspecialchars($deg) ?>" <?= $selectedDegree == $deg ? 'selected' : '' ?>><?= htmlspecialchars($deg) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <small id="degree-help" class="input-help">Select your highest medical degree</small>
                    </div>

                    <!-- Experience -->
                    <div class="input-group">
                        <label for="experience">
                            <i class="fa fa-clock-o" aria-hidden="true"></i>
                            Years of Experience
                        </label>
                        <input type="number" name="experience" id="experience" value="<?= htmlspecialchars($data['doctorprofile']['experience']) ?>" placeholder="e.g., 5" min="0" max="60" step="1">
                    </div>

                    <!-- Fees -->
                    <div class="input-group">
                        <label for="fees">
                            <i class="fa fa-dollar" aria-hidden="true"></i>
                            Consultation Fee *
                        </label>
                        <div class="fee-input">
                            <span class="currency">$</span>
                            <input type="number" name="fee" id="fees" value="<?= htmlspecialchars($data['doctorprofile']['fee']) ?>" required placeholder="0.00" min="0" max="9999.99" step="0.01">
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="input-group">
                        <label for="address">
                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                            Clinic Address *
                        </label>
                        <textarea name="address" id="address" required placeholder="Enter clinic address" rows="3" maxlength="500"><?= htmlspecialchars($data['doctorprofile']['address']) ?></textarea>
                    </div>

                    <!-- Working Hours -->
                    <div class="time-group">
                        <div class="input-group">
                            <label for="start-time">
                                <i class="fa fa-clock-o" aria-hidden="true"></i>
                                Start Time *
                            </label>
                            <input type="time" id="start-time" name="start_time" value="<?= date('H:i', strtotime($data['timeslots']['start_time'])) ?>" required min="06:00" max="18:00">
                        </div>

                        <div class="input-group">
                            <label for="end-time">
                                <i class="fa fa-clock-o" aria-hidden="true"></i>
                                End Time *
                            </label>
                            <input type="time" id="end-time" name="end_time" value="<?= date('H:i', strtotime($data['timeslots']['end_time'])) ?>" required min="08:00" max="22:00">
                        </div>
                    </div>

                    <!-- Bio -->
                    <div class="input-group">
                        <label for="bio">
                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                            About Doctor
                        </label>
                        <textarea name="bio" id="bio" placeholder="Brief description about the doctor..." rows="4" maxlength="1000"><?= htmlspecialchars($data['doctorprofile']['bio']) ?></textarea>
                        <small class="input-help">Maximum 1000 characters</small>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="<?php echo htmlspecialchars(URLROOT); ?>/admin/doctorlist" class="btn btn-secondary">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Back to List
                </a>
                <div class="action-buttons">
                    <button type="button" class="btn btn-outline" onclick="resetForm()">
                        <i class="fa fa-undo" aria-hidden="true"></i> Reset Changes
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save" aria-hidden="true"></i> Save Changes
                    </button>
                </div>
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
    --warning-orange: #F59E0B;
    --warning-light: #FEF3C7;
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --border-radius: 0.5rem;
    --transition: all 0.3s ease;
}

/* Base styles */
* {
    box-sizing: border-box;
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
    transition: var(--transition);
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
    flex-shrink: 0;
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
    transition: var(--transition);
}

.photo-upload-section:hover {
    border-color: var(--primary-green);
    background: var(--light-green);
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
    transition: var(--transition);
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
    transition: var(--transition);
}

#photo-preview:hover {
    border-color: var(--primary-green);
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

.input-group label,
fieldset legend {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.875rem;
}

fieldset {
    border: none;
    margin: 0;
    padding: 0;
}

fieldset legend {
    float: left;
    width: 100%;
    margin-bottom: 0.5rem;
}

.input-group label i,
fieldset legend i {
    color: var(--primary-green);
    width: 16px;
    flex-shrink: 0;
}

.optional-label {
    color: var(--text-secondary);
    font-weight: 400;
    font-size: 0.75rem;
    margin-left: auto;
}

.input-help {
    color: var(--text-secondary);
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

.input-group input,
.input-group select,
.input-group textarea {
    padding: 0.875rem 1rem;
    border: 2px solid var(--border-light);
    border-radius: var(--border-radius);
    font-size: 1rem;
    transition: var(--transition);
    background: var(--pure-white);
    resize: vertical;
    font-family: inherit;
}

.input-group input:focus,
.input-group select:focus,
.input-group textarea:focus {
    outline: none;
    border-color: var(--primary-green);
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

.input-group input:invalid,
.input-group select:invalid,
.input-group textarea:invalid {
    border-color: var(--error-red);
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
    transition: var(--transition);
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
    flex-wrap: wrap;
}

.radio-option {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    padding: 0.75rem 1rem;
    border: 2px solid var(--border-light);
    border-radius: var(--border-radius);
    transition: var(--transition);
    flex: 1;
    justify-content: center;
    min-width: 120px;
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
    transition: var(--transition);
    flex-shrink: 0;
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
    border-radius: var(--border-radius);
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
    justify-content: center;
    font-family: inherit;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.btn-primary {
    background: var(--primary-green);
    color: var(--pure-white);
    box-shadow: var(--shadow-md);
}

.btn-primary:hover:not(:disabled) {
    background: var(--dark-green);
    transform: translateY(-1px);
    box-shadow: var(--shadow-lg);
}

.btn-secondary {
    background: var(--pure-white);
    color: var(--text-secondary);
    border: 2px solid var(--border-light);
}

.btn-secondary:hover:not(:disabled) {
    border-color: var(--medium-gray);
    color: var(--text-primary);
}

.btn-outline {
    background: var(--pure-white);
    color: var(--primary-green);
    border: 2px solid var(--primary-green);
}

.btn-outline:hover:not(:disabled) {
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

.action-buttons {
    display: flex;
    gap: 1rem;
}

/* Loading state */
.form-loading {
    position: relative;
    pointer-events: none;
    opacity: 0.7;
}

.form-loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 32px;
    height: 32px;
    border: 3px solid var(--border-light);
    border-top-color: var(--primary-green);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: translate(-50%, -50%) rotate(360deg); }
}

/* Responsive Design */
@media (max-width: 1024px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
}

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
        gap: 1.5rem;
    }

    .radio-group {
        flex-direction: column;
    }

    .radio-option {
        min-width: auto;
    }

    .time-group {
        grid-template-columns: 1fr;
    }

    .form-actions {
        flex-direction: column-reverse;
        gap: 1rem;
    }

    .action-buttons {
        flex-direction: column;
        width: 100%;
    }

    .form-actions .btn {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .photo-placeholder,
    #photo-preview {
        width: 100px;
        height: 100px;
    }

    .header-icon {
        width: 50px;
        height: 50px;
        font-size: 1.25rem;
    }

    .doctor-form {
        padding: 1rem;
    }

    .form-header {
        padding: 1rem;
    }
}

/* Print styles */
@media print {
    .form-header,
    .form-actions,
    .btn {
        display: none;
    }
    
    .form-container {
        box-shadow: none;
        border: 1px solid #000;
    }
    
    .page-container {
        max-width: none;
        margin: 0;
        padding: 0;
    }
}

/* Focus management for accessibility */
.form-container:focus-within .section-header {
    border-bottom-color: var(--primary-green);
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    :root {
        --border-light: #666;
        --text-secondary: #333;
    }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}
</style>

<script>
// Constants and configuration
const degreeOptions = <?php echo json_encode($degreeOptions); ?>;
const MAX_FILE_SIZE = 2 * 1024 * 1024; // 2MB
const ALLOWED_FILE_TYPES = ['image/jpeg', 'image/png', 'image/webp'];

// Form validation patterns
const VALIDATION_PATTERNS = {
    phone: /^[\+]?[0-9\s\-\(\)]{10,20}$/,
    email: /^[^\s@]+@[^\s@]+\.[^\s@]+$/
};

// Initialize form when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    initializeForm();
    setupFormValidation();
    setupFileUpload();
    setupAutoSave();
});

function initializeForm() {
    // Update degrees dropdown on page load
    updateDegrees();
    
    // Setup form submission handler
    const form = document.querySelector('.doctor-form');
    form.addEventListener('submit', handleFormSubmission);
    
    // Setup time validation
    setupTimeValidation();
}

function updateDegrees() {
    const specialty = document.getElementById("specialty").value;
    const degreeSelect = document.getElementById("degree");
    const selectedDegree = "<?php echo addslashes($selectedDegree); ?>";

    // Clear existing options
    degreeSelect.innerHTML = '<option value="">-- Select Degree --</option>';

    if (degreeOptions[specialty]) {
        degreeOptions[specialty].forEach(function(deg) {
            const option = document.createElement("option");
            option.value = deg;
            option.textContent = deg;
            if (deg === selectedDegree) {
                option.selected = true;
            }
            degreeSelect.appendChild(option);
        });
    }
    
    // Trigger validation
    validateField(degreeSelect);
}

function togglePassword() {
    const passwordInput = document.getElementById('password');
    const passwordIcon = document.getElementById('password-icon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        passwordIcon.className = 'fa fa-eye';
        passwordInput.setAttribute('aria-label', 'Password (visible)');
    } else {
        passwordInput.type = 'password';
        passwordIcon.className = 'fa fa-eye-slash';
        passwordInput.setAttribute('aria-label', 'Password (hidden)');
    }
}

function resetForm() {
    // Remove the confirm dialog - directly reset the form
    const form = document.querySelector('.doctor-form');
    form.reset();
    
    // Reset photo preview
    resetPhotoPreview();
    
    // Reset dropdowns
    updateDegrees();
    
    // Clear validation messages
    clearValidationMessages();
    
    // Show success message
    showNotification('Form has been reset to original values', 'info');
}

function resetPhotoPreview() {
    const currentImage = "<?= !empty($data['users']['profile_image']) ? htmlspecialchars(URLROOT . '/' . $data['users']['profile_image']) : '' ?>";
    const preview = document.getElementById('photo-preview');
    const placeholder = document.getElementById('photo-placeholder');
    
    if (currentImage) {
        preview.src = currentImage;
        preview.style.display = 'block';
        placeholder.style.display = 'none';
    } else {
        preview.style.display = 'none';
        placeholder.style.display = 'flex';
    }
}

function setupFileUpload() {
    const fileInput = document.getElementById('profile-photo');
    
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (file) {
            // Validate file
            if (!validateFile(file)) {
                return;
            }
            
            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('photo-preview');
                const placeholder = document.getElementById('photo-placeholder');
                
                preview.src = e.target.result;
                preview.style.display = 'block';
                placeholder.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    });
}

function validateFile(file) {
    // Check file type
    if (!ALLOWED_FILE_TYPES.includes(file.type)) {
        showNotification('Please select a valid image file (JPEG, PNG, or WebP)', 'error');
        return false;
    }
    
    // Check file size
    if (file.size > MAX_FILE_SIZE) {
        showNotification('File size must be less than 2MB', 'error');
        return false;
    }
    
    return true;
}

function setupFormValidation() {
    const form = document.querySelector('.doctor-form');
    const inputs = form.querySelectorAll('input, select, textarea');
    
    inputs.forEach(input => {
        input.addEventListener('blur', () => validateField(input));
        input.addEventListener('input', () => clearFieldError(input));
    });
}

function validateField(field) {
    const value = field.value.trim();
    let isValid = true;
    let errorMessage = '';
    
    // Required field validation
    if (field.hasAttribute('required') && !value) {
        isValid = false;
        errorMessage = 'This field is required';
    }
    
    // Specific field validations
    if (value && field.type === 'email' && !VALIDATION_PATTERNS.email.test(value)) {
        isValid = false;
        errorMessage = 'Please enter a valid email address';
    }
    
    if (value && field.type === 'tel' && !VALIDATION_PATTERNS.phone.test(value)) {
        isValid = false;
        errorMessage = 'Please enter a valid phone number';
    }
    
    if (field.name === 'password' && value && value.length < 8) {
        isValid = false;
        errorMessage = 'Password must be at least 8 characters long';
    }
    
    // Time validation
    if (field.name === 'end_time' && value) {
        const startTime = document.getElementById('start-time').value;
        if (startTime && value <= startTime) {
            isValid = false;
            errorMessage = 'End time must be after start time';
        }
    }
    
    // Show/hide error
    if (isValid) {
        clearFieldError(field);
    } else {
        showFieldError(field, errorMessage);
    }
    
    return isValid;
}

function showFieldError(field, message) {
    clearFieldError(field);
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.setAttribute('role', 'alert');
    errorDiv.innerHTML = `<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ${message}`;
    
    field.parentNode.appendChild(errorDiv);
    field.classList.add('error');
}

function clearFieldError(field) {
    const errorMessage = field.parentNode.querySelector('.error-message');
    if (errorMessage) {
        errorMessage.remove();
    }
    field.classList.remove('error');
}

function clearValidationMessages() {
    const errorMessages = document.querySelectorAll('.error-message');
    errorMessages.forEach(msg => msg.remove());
    
    const errorFields = document.querySelectorAll('.error');
    errorFields.forEach(field => field.classList.remove('error'));
}

function setupTimeValidation() {
    const startTime = document.getElementById('start-time');
    const endTime = document.getElementById('end-time');
    
    startTime.addEventListener('change', () => {
        if (endTime.value && endTime.value <= startTime.value) {
            showFieldError(endTime, 'End time must be after start time');
        } else {
            clearFieldError(endTime);
        }
    });
}

function handleFormSubmission(e) {
    e.preventDefault();
    
    const form = e.target;
    const submitBtn = form.querySelector('button[type="submit"]');
    
    // Validate all fields
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    let isFormValid = true;
    
    inputs.forEach(input => {
        if (!validateField(input)) {
            isFormValid = false;
        }
    });
    
    if (!isFormValid) {
        showNotification('Please correct the errors before submitting', 'error');
        return;
    }
    
    // Show loading state - removed any confirmation dialogs
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Saving...';
    form.classList.add('form-loading');
    
    // Submit form directly
    setTimeout(() => {
        form.submit();
    }, 500);
}

function setupAutoSave() {
    const form = document.querySelector('.doctor-form');
    const inputs = form.querySelectorAll('input, select, textarea');
    let autoSaveTimeout;
    
    inputs.forEach(input => {
        input.addEventListener('input', () => {
            clearTimeout(autoSaveTimeout);
            autoSaveTimeout = setTimeout(() => {
                saveFormData();
            }, 2000);
        });
    });
}

function saveFormData() {
    const form = document.querySelector('.doctor-form');
    const formData = new FormData(form);
    const data = {};
    
    for (let [key, value] of formData.entries()) {
        if (key !== 'image') { // Don't save file data
            data[key] = value;
        }
    }
    
    // Save to sessionStorage for recovery
    try {
        sessionStorage.setItem('doctorFormData', JSON.stringify(data));
    } catch (e) {
        console.warn('Could not save form data:', e);
    }
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type}`;
    notification.innerHTML = `
        <div class="alert-content">
            <i class="fa ${type === 'error' ? 'fa-exclamation-circle' : type === 'success' ? 'fa-check-circle' : 'fa-info-circle'}" aria-hidden="true"></i>
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove();" class="alert-close" aria-label="Close notification">
                <i class="fa fa-times" aria-hidden="true"></i>
            </button>
        </div>
    `;
    
    const container = document.querySelector('.page-container');
    container.insertBefore(notification, container.firstChild);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Auto-hide existing alerts
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        alert.style.opacity = '0';
        alert.style.transform = 'translateY(-10px)';
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 300);
    });
}, 5000);

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + S to save
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        document.querySelector('button[type="submit"]').click();
    }
    
    // Ctrl/Cmd + R to reset
    if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
        e.preventDefault();
        resetForm();
    }
});

// Completely remove the beforeunload event listener
// No more "leave site" warnings or confirmations
</script>