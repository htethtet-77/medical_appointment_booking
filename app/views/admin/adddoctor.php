<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}?>
<title><?php echo SITENAME; ?></title>
<?php require APPROOT . '/views/inc/sidebar.php'; ?>
<link rel="stylesheet" type="text/css" href="<?php echo URLROOT; ?>/vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">


<style>
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        background-color: #f4f7f6;
        overflow-y: auto; /* ✅ allow page scroll */
    }

    .container {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
    }

    .page-title {
        color: #333;
        font-size: 1.8em;
        margin-bottom: 25px;
        margin-top: 10px;
    }

    .form-section {
        display: flex;
        gap: 40px;
        flex-wrap: wrap;
        padding-bottom: 20px;
    }

    .form-column {
        flex: 1;
        min-width: 300px;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .profile-column {
        max-width: 350px;
    }

    .details-column {
        max-width: 500px;
    }

    .profile-pic-upload {
        width: 150px;
        height: 150px;
        background-color: #f0f0f0;
        border: 1px solid #ccc;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 3em;
        color: #aaa;
        margin-bottom: 20px;
        border-radius: 8px;
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

    /* Responsive Design */
    @media (max-width: 768px) {
        .form-section {
            flex-direction: column;
            gap: 20px;
            padding: 0;
        }

        .form-column {
            max-width: 100%;
            padding: 0 15px;
        }

        .buttons {
            justify-content: center;
        }

        .container {
            padding: 15px;
            margin: 10px auto;
        }
    }

    @media (max-width: 480px) {
        body {
            font-size: 0.9em;
        }

        .page-title {
            font-size: 1.5em;
        }

        .input-group input,
        .input-group select,
        .btn {
            padding: 10px;
            font-size: 0.9em;
        }
    }
</style>

<div class="container">
    <h1 class="page-title">ADD Doctor</h1>
<?php require APPROOT . '/views/components/auth_message.php'; ?>

    <form action="<?php echo URLROOT; ?>/admin/adddoctor" method="POST">
        <div class="form-section">
            <!-- Left Column -->
            <div class="form-column profile-column">
                <div class="profile-pic-upload">
                    <i class="fas fa-image"></i>
                </div>

                <div class="input-group">
                    <label for="name">Your Name</label>
                    <input type="text" name="name" id="name" placeholder="Name" required>
                    <p class="text-danger ml-4">
                        <?php
                        if(isset($data['name-err']))
                        echo $data['name-err'];
                        ?>
                    </p>

                </div>

                <div class="input-group">
                    <label for="doctor-email">Doctor Email</label>
                    <input type="email" name="email" id="doctor-email" placeholder="Email" required>
                    <p class="text-danger ml-4">
                        <?php
                        if(isset($data['email-err']))
                        echo $data['email-err'];
                        ?>
                    </p>
                </div>

                <div class="input-group">
                    <label for="services">Phone Number</label>
                    <input type="text" name="phone" id="services" placeholder="Phone Number" required>
                </div>

                <div class="input-group">
                    <label for="password">Set Password</label>
                    <input type="text" name="password" id="password" placeholder="Password" required>
                    <?php
                        if(isset($data['password-err']))
                        echo $data['password-err'];
                        ?>
                    </p>

                </div>

                <div class="input-group">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>

                <div class="input-group">
                    <label for="experience">Experience</label>
                    <input type="text" name="experience" id="experience" placeholder="Experience Year">

                </div>

                <div class="input-group">
                    <label for="about-doctor">About Doctor</label>
                    <input type="text" name="bio" id="about-doctor" placeholder="Write about doctor ">
                </div>
            </div>

            <!-- Right Column -->
            <div class="form-column details-column">
                <div class="input-group">
                    <label for="fees">Fees</label>
                    <input type="text" name="fee" id="fees" placeholder="Doctor fees" required>
                </div>

                <div class="input-group">
                    <label for="specialty">Specialty</label>
                    <input type="text" name="specialty" id="specialty" placeholder="Doctor's Specialty" required>
                </div>

                <div class="input-group">
                    <label for="degree">Degree</label>
                    <input type="text" name="degree" id="degree" placeholder="Degree" required>
                </div>

                <div class="input-group">
                    <label for="address">Address</label>
                    <input type="text" name="address" id="address" placeholder="Address" required>
                </div>

                <div class="input-group">
                    <label for="availability">Availability</label>
                    <select id="availability" name="availability" required>
                        <option value="">Select Availability</option>
                        <option value="Monday - Friday">Monday - Friday</option>
                        <option value="Weekends">Weekends</option>
                    </select>
                </div>

                <div class="buttons">
                    <button type="submit" class="btn add-btn">Add</button>
                </div>
            </div>
        </div>
    </form>
</div>
</body>
</html>
