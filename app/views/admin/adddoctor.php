
<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/sidebar.php'; ?>

    <div class="main-content-wrapper">
        <div class="formcontainer">
            <h1>ADD Doctor</h1>
            <div class="form-layout">
                <div class="form-section left-section">
                    <div class="image-placeholder">
                        <span class="material-icons">image</span>
                    </div>
                    <label for="name">Your Name</label>
                    <input type="text" id="name" name="name" placeholder="Name">

                    <label for="email">Doctor Email</label>
                    <input type="email" id="email" name="email" placeholder="Email">

                    <label for="password">Set Password</label>
                    <input type="password" id="password" name="password" placeholder="Password">

                    <label for="experience">Experience</label>
                    <select id="experience" name="experience">
                        <option value="">1 Year</option>
                        <option value="2-5">2-5 Years</option>
                        <option value="5-10">5-10 Years</option>
                        <option value="10+">10+ Years</option>
                    </select>

                    <label for="about">About Doctor</label>
                    <input type="text" id="about" name="about" placeholder="Write about doctor">

                    <label for="fees">Fees</label>
                    <input type="text" id="fees" name="fees" placeholder="Doctor fees">
                </div>

                <div class="form-section right-section">
                    <label for="speciality">Speciality</label>
                    <input type="text" id="speciality" name="speciality" placeholder="General Physician">

                    <label for="degree">Degree</label>
                    <input type="text" id="degree" name="degree" placeholder="Degree">

                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" placeholder="Address">

                    <label for="services">Services</label>
                    <input type="text" id="services" name="services" placeholder="Services">

                    <label for="overall-availability-dropdown">Overall Availability</label>
                    <select id="overall-availability-dropdown" name="overall-availability">
                        <option value="">Select Availability</option>
                        <option value="available">Available</option>
                        <option value="unavailable">Unavailable</option>
                    </select>

                    <div id="availabilityDetailsPanel" class="availability-card hidden">
                        <div class="day-toggles">
                            <div class="day-toggle-item">
                                <span>Monday</span>
                                <label class="switch">
                                    <input type="checkbox" checked>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div class="day-toggle-item">
                                <span>Tuesday</span>
                                <label class="switch">
                                    <input type="checkbox" checked>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div class="day-toggle-item">
                                <span>Wednesday</span>
                                <label class="switch">
                                    <input type="checkbox" checked>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div class="day-toggle-item">
                                <span>Thursday</span>
                                <label class="switch">
                                    <input type="checkbox" checked>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div class="day-toggle-item">
                                <span>Friday</span>
                                <label class="switch">
                                    <input type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div class="day-toggle-item">
                                <span>Saturday</span>
                                <label class="switch">
                                    <input type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div class="day-toggle-item">
                                <span>Sunday</span>
                                <label class="switch">
                                    <input type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                        <div class="time-slot-config">
                            <label for="time-range">Time Range</label>
                            <div class="time-range-inputs">
                                <input type="text" id="time-range-start" value="9:00AM">
                                <span class="material-icons">chevron_right</span>
                                <input type="text" id="time-range-end" value="1:00PM">
                            </div>

                            <label for="slot-duration">Slot During</label>
                            <select id="slot-duration" name="slot-duration">
                                <option value="30">30 min</option>
                                <option value="15">15 min</option>
                                <option value="60">60 min</option>
                            </select>
                        </div>
                        <button type="button" class="save-availability-btn">Save</button>
                    </div>
                    </div>
            </div>
            <div class="form-actions">
                <button type="button" class="edit-btn">Edit</button>
                <button type="submit" class="add-btn">Add</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const overallAvailabilityDropdown = document.getElementById('overall-availability-dropdown');
            const availabilityDetailsPanel = document.getElementById('availabilityDetailsPanel');

            // Function to toggle the visibility of the availability details panel
            function toggleDetailsPanel() {
                // Show the panel if "available" is selected
                if (overallAvailabilityDropdown.value === 'available') {
                    availabilityDetailsPanel.classList.remove('hidden');
                } else {
                    // Hide the panel for "Select Availability" or "Unavailable"
                    availabilityDetailsPanel.classList.add('hidden');
                }
            }

            // Set initial state when the page loads
            toggleDetailsPanel();

            // Add event listener for when the dropdown value changes
            overallAvailabilityDropdown.addEventListener('change', toggleDetailsPanel);
        });
    </script>
</body>
</html>