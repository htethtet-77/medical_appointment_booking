<?php
$selectedSpecialty = $_POST['specialty'] ?? $data['doctorprofile']['specialty'] ?? '';
$selectedDegree = $_POST['degree'] ?? $data['doctorprofile']['degree'] ?? '';

$degreeOptions = [
    "General Physician" => ["MBBS", "MD", "DO"],
    "Dentist" => ["BDS", "MDS", "DDS"],
    "Pediatrician" => ["MBBS", "MD Pediatrics", "DCH"],
    "Dermatologist" => ["MBBS", "MD Dermatology", "DDVL"]
];
?>

<title><?php echo SITENAME; ?></title>
<?php require APPROOT . '/views/inc/sidebar.php'; ?>

<style>
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        background-color: #f4f7f6;
        overflow-y: auto;
    }

    .container {
        max-width: 1000px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .page-title {
        color: #333;
        font-size: 1.8em;
        margin-bottom: 25px;
    }

    .form-section {
        display: flex;
        gap: 40px;
        flex-wrap: wrap;
    }

    .form-column {
        flex: 1;
        min-width: 300px;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .input-group {
        display: flex;
        flex-direction: column;
    }

    .input-group label {
        margin-bottom: 8px;
        font-weight: bold;
        color: #555;
    }

    .input-group input,
    .input-group select {
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 1em;
        width: 100%;
        box-sizing: border-box;
    }

    .input-group input[type="file"] {
        padding: 10px;
        background-color: #fff;
    }

    .gender-row {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-top: 10px;
    }

    .buttons {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 30px;
    }

    .btn {
        padding: 12px 25px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1em;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }

    .add-btn {
        background-color: #2e8b7f;
        color: white;
    }

    .add-btn:hover {
        background-color: #246e65;
    }

    .error-msg {
        color: #dc3545;
        font-size: 0.9em;
        margin-top: 5px;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .form-section {
            flex-direction: column;
            gap: 20px;
        }

        .buttons {
            justify-content: center;
        }
    }
</style>

<div class="container">
    <h1 class="page-title">Update Doctor</h1>
    <?php require APPROOT . '/views/components/auth_message.php'; ?>

    <form action="<?php echo URLROOT; ?>/admin/updatedoctor"method="POST" enctype="multipart/form-data">
        <div class="form-section">
            <!-- Left Column -->
            <div class="form-column">

                <!-- Upload Photo -->
                <div class="input-group">
                    <label for="profile-photo">Upload Photo</label>
                    <input type="file" name="image" id="profile-photo" accept="image/*">
                    <?php if (!empty($data['users']['profile_image'])): ?>
                        <img src="<?= URLROOT . '/' . $data['users']['profile_image'] ?>" alt="Current Image" style="width: 100px; margin-top: 10px;">
                    <?php endif; ?>
                </div>
                <input type="hidden" name="id" value="<?= $data['users']['id'] ?>">


                <!-- Name -->
                <div class="input-group">
                    <label for="name">Your Name</label>
                    <input type="text" name="name" id="name" value="<?= htmlspecialchars($data['users']['name']) ?>" required>
                </div>

                <!-- Email -->
                <div class="input-group">
                    <label for="doctor-email">Doctor Email</label>
                    <input type="email" name="email" id="doctor-email" value="<?= htmlspecialchars($data['users']['email']) ?>" required>
                </div>

                <!-- Gender -->
                <div class="input-group">
                    <label>Gender:</label>
                    <div class="gender-row">
                        <label><input type="radio" name="gender" value="male" <?= $data['users']['gender'] == 'male' ? 'checked' : '' ?>> Male</label>
                        <label><input type="radio" name="gender" value="female" <?= $data['users']['gender'] == 'female' ? 'checked' : '' ?>> Female</label>
                    </div>
                </div>

                <!-- Phone -->
                <div class="input-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" name="phone" id="phone" value="<?= htmlspecialchars($data['users']['phone']) ?>" required>
                </div>

                <!-- Password -->
                <div class="input-group">
                    <label for="password">Set New Password</label>
                    <input type="text" name="password" id="password" value="<?= htmlspecialchars($data['users']['password']) ?>">
                </div>

                <!-- Experience -->
                <div class="input-group">
                    <label for="experience">Experience</label>
                    <input type="text" name="experience" id="experience" value="<?= htmlspecialchars($data['doctorprofile']['experience']) ?>">
                </div>

                <!-- Bio -->
                <div class="input-group">
                    <label for="bio">About Doctor</label>
                    <input type="text" name="bio" id="bio" value="<?= htmlspecialchars($data['doctorprofile']['bio']) ?>">
                </div>
            </div>

            <!-- Right Column -->
            <div class="form-column">

                <!-- Fee -->
                <div class="input-group">
                    <label for="fees">Fees</label>
                    <input type="text" name="fee" id="fees" value="<?= htmlspecialchars($data['doctorprofile']['fee']) ?>" required>
                </div>

                <!-- Specialty -->
                <div class="input-group">
                    <label for="specialty">Specialty</label>
                    <select name="specialty" id="specialty" required onchange="updateDegrees()">
                        <option value="">-- Select Specialty --</option>
                        <?php foreach ($degreeOptions as $spec => $degrees): ?>
                            <option value="<?= $spec ?>" <?= $selectedSpecialty == $spec ? 'selected' : '' ?>><?= $spec ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Degree -->
                <div class="input-group">
                    <label for="degree">Degree</label>
                    <select name="degree" id="degree" required>
                        <option value="">-- Select Degree --</option>
                        <?php foreach ($degreeOptions[$selectedSpecialty] ?? [] as $deg): ?>
                            <option value="<?= $deg ?>" <?= $selectedDegree == $deg ? 'selected' : '' ?>><?= $deg ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Address -->
                <div class="input-group">
                    <label for="address">Address</label>
                    <input type="text" name="address" id="address" value="<?= htmlspecialchars($data['doctorprofile']['address']) ?>" required>
                </div>

                <!-- Availability -->
                <div class="input-group">
                    <label for="availability">Working Day</label>
                    <select id="availability" name="availability" required>
                        <option value="">-- Select Day --</option>
                        <?php
                        $days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
                        foreach ($days as $day) {
                            $selected = $data['timeslots']['day'] == $day ? 'selected' : '';
                            echo "<option value=\"$day\" $selected>$day</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Start Time -->
                <div class="input-group">
                    <label for="start-time">Start Time</label>
                    <input type="time" id="start-time" name="start_time" value="<?= date('H:i', strtotime($data['timeslots']['start_time'])) ?>" required>
                </div>

                <!-- End Time -->
                <div class="input-group">
                    <label for="end-time">End Time</label>
                    <input type="time" id="end-time" name="end_time" value="<?= date('H:i', strtotime($data['timeslots']['end_time'])) ?>" required>
                </div>

                <!-- Submit -->
                <div class="buttons">
                    <button type="submit" class="btn add-btn">Save</button>
                </div>
            </div>
        </div>
    </form>
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

    document.addEventListener("DOMContentLoaded", updateDegrees);
</script>
