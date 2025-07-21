<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/sidebar.php'; ?>


    <div class="container">
        <div class="doctor-list-header">
            <h1>Doctor List</h1>
            <div class="search-bar">
                <span class="material-icons">search</span>
                <input type="text" placeholder="Search by Specialty">
            </div>
        </div>

        <div class="doctor-card">
            <div class="avatar">🖼️</div>
            <div class="info">
                <h3>Dr. Daniel</h3>
                <p>General Physician</p>
                <p>10 years of Exp</p>
                <p>Consultation Fee:120$</p>
                <p>Location :New York ,NY</p>
            </div>
            <div class="actions">
                <button class="edit-btn">Edit</button>
                <button class="delete-btn">Delete</button>
            </div>
        </div>

        <div class="doctor-card">
            <div class="avatar">🖼️</div>
            <div class="info">
                <h3>Dr. Daniel</h3>
                <p>General Physician</p>
                <p>10 years of Exp</p>
                <p>Consultation Fee:120$</p>
                <p>Location :New York ,NY</p>
            </div>
            <div class="actions">
                <button class="edit-btn">Edit</button>
                <button class="delete-btn">Delete</button>
            </div>
        </div>

        <div class="doctor-card">
            <div class="avatar">🖼️</div>
            <div class="info">
                <h3>Dr. Daniel</h3>
                <p>General Physician</p>
                <p>10 years of Exp</p>
                <p>Consultation Fee:120$</p>
                <p>Location :New York ,NY</p>
            </div>
            <div class="actions">
                <button class="edit-btn">Edit</button>
                <button class="delete-btn">Delete</button>
            </div>
        </div>
    </div>

        <a href="<?php echo URLROOT; ?>/admin/adddoctor" class="add-doctor-btn">+</a>

<script>
// Get references to the search input and the doctor cards container
const searchInput = document.querySelector('.search');
const doctorCardsContainer = document.querySelector('.doctor-list-section'); // Or a more specific container if you have one
const doctorCards = doctorCardsContainer ? doctorCardsContainer.querySelectorAll('.doctor-card') : [];

// Add an event listener for input changes
if (searchInput) {
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase(); // Get the typed text and convert to lowercase for case-insensitive search

        doctorCards.forEach(card => {
            const doctorDetails = card.querySelector('.doctor-details');
            if (doctorDetails) {
                const cardText = doctorDetails.textContent.toLowerCase(); // Get all visible text from the doctor's details

                // Check if the card's text contains the search term
                if (cardText.includes(searchTerm)) {
                    card.style.display = 'flex'; // Show the card (or 'block' depending on your card's default display)
                } else {
                    card.style.display = 'none'; // Hide the card
                }
            }
        });
    });
}
</script>