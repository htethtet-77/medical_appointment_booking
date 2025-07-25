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

    .gender-row label {
        margin: 0;
        font-weight: normal;
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
    <h1 class="page-title">ADD Doctor</h1>
    <?php require APPROOT . '/views/components/auth_message.php'; ?>

    <form action="<?php echo URLROOT; ?>/admin/adddoctor" method="POST" enctype="multipart/form-data">
        <div class="form-section">
            <!-- Left Column -->
            <div class="form-column">

                <!-- Upload Photo -->
                <div class="input-group">
                    <label for="profile-photo">Upload Photo</label>
                    <input type="file" name="image" id="profile-photo" accept="image/*">
                </div>

                <!-- Name -->
                <div class="input-group">
                    <label for="name">Your Name</label>
                    <input type="text" name="name" id="name" placeholder="Name" required>
                    <?php if (isset($data['name-err'])): ?>
                        <p class="error-msg"><?php echo $data['name-err']; ?></p>
                    <?php endif; ?>
                </div>

                <!-- Email -->
                <div class="input-group">
                    <label for="doctor-email">Doctor Email</label>
                    <input type="email" name="email" id="doctor-email" placeholder="Email" required>
                    <?php if (isset($data['email-err'])): ?>
                        <p class="error-msg"><?php echo $data['email-err']; ?></p>
                    <?php endif; ?>
                </div>

                <!-- Gender (inline) -->
                <div class="input-group">
                    <label>Gender:</label>
                    <div class="gender-row">
                        <label><input type="radio" name="gender" value="male" required> Male</label>
                        <label><input type="radio" name="gender" value="female" required> Female</label>
                    </div>
                </div>

                <!-- Phone -->
                <div class="input-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" name="phone" id="phone" placeholder="Phone Number" required>
                    <?php if (isset($data['phone-err'])): ?>
                        <p class="error-msg"><?php echo $data['phone-err']; ?></p>
                    <?php endif; ?>
                </div>

                <!-- Password -->
                <div class="input-group">
                    <label for="password">Set Password</label>
                    <input type="text" name="password" id="password" placeholder="Password" required>
                    <?php if(isset($data['password-err'])): ?>
                        <p class="error-msg"><?php echo $data['password-err']; ?></p>
                    <?php endif; ?>
                </div>

                <!-- Experience -->
                <div class="input-group">
                    <label for="experience">Experience</label>
                    <input type="text" name="experience" id="experience" placeholder="Years of Experience">
                </div>

                <!-- Bio -->
                <div class="input-group">
                    <label for="about-doctor">About Doctor</label>
                    <input type="text" name="bio" id="about-doctor" placeholder="Write about the doctor">
                </div>
            </div>

            <!-- Right Column -->
            <div class="form-column">

                <div class="input-group">
                    <label for="fees">Fees</label>
                    <input type="text" name="fee" id="fees" placeholder="Doctor fees" required>
                </div>

                <div class="input-group">
                    <label for="specialty">Specialty</label>
                    <select name="specialty" id="specialty" required onchange="updateDegrees()">
                        <option value="">-- Select Specialty --</option>
                        <option value="General Physician">General Physician</option>
                        <option value="Dentist">Dentist</option>
                        <option value="Pediatrician">Pediatrician</option>
                        <option value="Dermatologist">Dermatologist</option>
                    </select>
                </div>


               <div class="input-group">
                    <label for="degree">Degree</label>
                    <select name="degree" id="degree" required>
                        <option value="">-- Select Degree --</option>
                    </select>
                </div>


                <div class="input-group">
                    <label for="address">Address</label>
                    <input type="text" name="address" id="address" placeholder="Address" required>
                </div>

                <div class="input-group">
                    <label for="availability">Availability</label>
                    <select id="availability" name="availability" required>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                            <option value="Sunday">Sunday</option>
                    </select>
                </div>

                <div class="buttons">
                    <button type="submit" class="btn add-btn">Add</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

<script>
    const degreeOptions = {
        "General Physician": ["MBBS", "MD", "DO"],
        "Dentist": ["BDS", "MDS", "DDS"],
        "Pediatrician": ["MBBS", "MD Pediatrics", "DCH"],
        "Dermatologist": ["MBBS", "MD Dermatology", "DDVL"]
    };

    function updateDegrees() {
        const specialty = document.getElementById("specialty").value;
        const degreeSelect = document.getElementById("degree");

        degreeSelect.innerHTML = '<option value="">-- Select Degree --</option>';

        if (degreeOptions[specialty]) {
            degreeOptions[specialty].forEach(function(degree) {
                const option = document.createElement("option");
                option.value = degree;
                option.textContent = degree;
                degreeSelect.appendChild(option);
            });
        }
    }
</script>


</body>
</html>
