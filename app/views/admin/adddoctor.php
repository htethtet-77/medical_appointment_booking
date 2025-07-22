<?php require APPROOT . '/views/inc/sidebar.php'; ?>


<style>
                /* General Body and Container Styles */
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f4f7f6; /* Light gray background */
                display: flex;
                flex-direction: column;
                min-height: 100vh; /* Ensure body takes full viewport height */
                overflow: hidden; /* Prevent body scroll, content will need to fit */
            }

            .container {
                max-width: 1200px;
                max-height: 760px;
                margin: 20px auto;
                padding: 20px;
                background-color: #fff;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                flex-grow: 1; /* Allow container to grow and fill available space */
                display: flex;
                flex-direction: column;
                overflow: hidden; /* Keep content within container without internal scroll by default */
            }


            /* Page Title */
            .page-title {
                color: #333;
                font-size: 1.8em;
                margin-bottom: 25px;
                margin-top: 10px;
            }

            /* Form Section */
            .form-section {
                display: flex;
                gap: 40px; /* Space between columns */
                flex-wrap: wrap; /* Allow columns to wrap on smaller screens */
                padding-bottom: 20px; /* Space at the bottom */
                flex-grow: 1; /* Allow form section to grow */
                overflow-y: auto; /* Allow scroll within this section if content exceeds height */
                padding-right: 15px; /* Account for scrollbar width if present */
            }

            .form-column {
                flex: 1; /* Each column takes equal space */
                min-width: 300px; /* Minimum width for columns before wrapping */
                display: flex;
                flex-direction: column;
                gap: 15px; /* Space between input groups */
            }

            .profile-column {
                max-width: 350px; /* Adjust as needed for the left column */
            }

            .details-column {
                max-width: 500px; /* Adjust as needed for the right column */
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
                border-radius: 8px; /* Slightly rounded corners */
            }

            /* Input Group Styles */
            .input-group {
                display: flex;
                flex-direction: column;
                margin-bottom: 5px; /* Smaller margin for tighter layout */
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
                box-sizing: border-box; /* Include padding in width */
                width: 100%; /* Take full width of parent */
            }

            /* Buttons */
            .buttons {
                display: flex;
                justify-content: flex-end; /* Align buttons to the right */
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

            .edit-btn {
                background-color: #6c757d; /* Gray for Edit */
                color: white;
            }

            .edit-btn:hover {
                background-color: #5a6268;
            }

            .add-btn {
                background-color: #2e8b7f; /* Teal for Add */
                color: white;
            }

            .add-btn:hover {
                background-color: #246e65;
            }

            /* Responsive Design (Mobile View) */
            @media (max-width: 768px) {
                .header {
                    flex-direction: column;
                    align-items: flex-start;
                    padding: 10px 15px;
                }

                .navbar ul {
                    flex-direction: column;
                    width: 100%;
                    margin-top: 10px;
                }

                .navbar li {
                    margin: 5px 0;
                    text-align: center;
                    width: 100%;
                }

                .form-section {
                    flex-direction: column; /* Stack columns vertically on mobile */
                    gap: 20px;
                    padding-left: 0; /* Reset padding for mobile */
                    padding-right: 0; /* Reset padding for mobile */
                    overflow-y: auto; /* Allow scroll if content is too long */
                }

                .form-column {
                    min-width: auto; /* Remove min-width constraint */
                    max-width: 100%; /* Take full width */
                    padding: 0 15px; /* Add horizontal padding for content */
                    box-sizing: border-box;
                }

                .profile-pic-upload {
                    margin: 0 auto 20px auto; /* Center profile pic on mobile */
                }

                .buttons {
                    justify-content: center; /* Center buttons on mobile */
                    margin-top: 20px;
                }

                /* Adjust padding of container for smaller screens */
                .container {
                    padding: 15px;
                    margin: 10px auto;
                }
            }

            /* Adjustments for "no scroll" - This is tricky and might involve compromises */
            /* The main approach to 'no scroll' is to make sure content always fits.
            For forms, this means fields might need to be smaller, or fonts, or
            the layout needs to be more compact. */

            /* Example of making fonts smaller on very small screens if necessary */
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

        <div class="form-section">
            <div class="form-column profile-column">
                <div class="profile-pic-upload">
                    <i class="fas fa-image"></i>
                </div>
                <div class="input-group">
                    <label for="name">Your Name</label>
                    <input type="text" id="name" placeholder="Name">
                </div>
                <div class="input-group">
                    <label for="doctor-email">Doctor Email</label>
                    <input type="email" id="doctor-email" placeholder="Email">
                </div>
                <div class="input-group">
                    <label for="password">Set Password</label>
                    <input type="password" id="password" placeholder="Password">
                </div>
                <div class="input-group">
                    <label for="experience">Experience</label>
                    <select id="experience">
                        <option>1 Year</option>
                        <option>2 Years</option>
                        <option>3+ Years</option>
                    </select>
                </div>
                <div class="input-group">
                    <label for="about-doctor">About Doctor</label>
                    <input type="text" id="about-doctor" placeholder="Write about doctor">
                </div>
            </div>

            <div class="form-column details-column">
                <div class="input-group">
                    <label for="fees">Fees</label>
                    <input type="text" id="fees" placeholder="Doctor fees">
                </div>
                <div class="input-group">
                    <label for="speciality">Speciality</label>
                    <input type="text" id="speciality" placeholder="General Physician">
                </div>
                <div class="input-group">
                    <label for="degree">Degree</label>
                    <input type="text" id="degree" placeholder="Degree">
                </div>
                <div class="input-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" placeholder="Address">
                </div>
                <div class="input-group">
                    <label for="services">Services</label>
                    <input type="text" id="services" placeholder="Services">
                </div>
                <div class="input-group">
                    <label for="availability">Availability</label>
                    <select id="availability">
                        <option>Availability</option>
                        <option>Monday - Friday</option>
                        <option>Weekends</option>
                    </select>
                </div>
                <div class="buttons">
                    <!-- <button class="btn edit-btn">Edit</button> -->
                    <button class="btn add-btn">Add</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>