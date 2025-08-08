<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/navbar.php'; ?>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
<div class="user-main-content">
    <div class="container profile-container">
        <h2 class="profile-title">View Profile</h2>

        <!-- Flash Messages -->
        <?php if(isset($_SESSION['success_message'])): ?>
            <div class="success-message">
                <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>
        <?php if(isset($_SESSION['error_message'])): ?>
            <div class="error-message">
                <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>

        <div class="profile-card">
            <div class="profile-left">
                <!-- Profile Image -->
                <div class="profile-image-container">
                    <div class="profile-image-placeholder" onclick="document.getElementById('profileImageInput').click()">
                        <div id="imageWrapper">
    <?php if ($user['profile_image'] && $user['profile_image'] !== 'default_profile.jpg'): ?>
        <img src="<?php echo URLROOT; ?>/uploads/<?php echo htmlspecialchars($user['profile_image']); ?>" alt="Profile Image">
        <div class="image-overlay">
            <i class="fas fa-camera"></i>
            <span>Change Photo</span>
        </div>
    <?php else: ?>
        <i class="fas fa-user" id="placeholderIcon"></i>
        <div class="image-overlay">
            <i class="fas fa-camera"></i>
            <span>Upload Photo</span>
        </div>
    <?php endif; ?>
</div>

                    </div>

                    <?php if ($user['profile_image'] && $user['profile_image'] !== 'default_profile.jpg'): ?>
                        <button class="remove-image-btn" onclick="removeProfileImage()" title="Remove Photo">
                            <i class="fas fa-times"></i>
                        </button>
                    <?php endif; ?>
                </div>

                <!-- File input for uploading -->
                <input type="file" id="profileImageInput" accept="image/*" class="hidden" onchange="uploadProfileImage()">

                <div class="loading" id="uploadLoading" style="display: none;">
                    <i class="fas fa-spinner fa-spin"></i> Uploading...
                </div>
            </div>

            <div class="profile-right">
                <div class="profile-info-group">
                    <label for="name">Name :</label>
                    <input type="text" class="readonly-box" value="<?php echo htmlspecialchars($user['name']); ?>" readonly>
                </div>
                <div class="profile-info-group">
                    <label for="email">Email:</label>
                    <input type="text" class="readonly-box" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                </div>
                <div class="profile-info-group">
                    <label for="gender">Gender:</label>
                    <input type="text" class="readonly-box" value="<?php echo htmlspecialchars($user['gender']); ?>" readonly>
                </div>
                <div class="profile-info-group">
                    <label for="phone">Ph No:</label>
                    <input type="text" class="readonly-box" value="<?php echo htmlspecialchars($user['phone']); ?>" readonly>
                </div>
            </div>
        </div>

        <div class="profile-actions">
            <a href="<?php echo URLROOT; ?>/auth/logout" class="action-button logout-button">Logout</a>
        </div>
    </div>
</div>

<script>
function uploadProfileImage() {
    const input = document.getElementById('profileImageInput');
    const file = input.files[0];

    if (!file) return;

    if (!file.type.startsWith('image/')) {
        alert('Please select a valid image file.');
        input.value = '';
        return;
    }

    if (file.size > 5 * 1024 * 1024) {
        alert('File size must be less than 5MB.');
        input.value = '';
        return;
    }

    document.getElementById('uploadLoading').style.display = 'block';

    const formData = new FormData();
    formData.append('profile_image', file);

    fetch(`<?php echo URLROOT; ?>/patient/uploadProfileImage`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('uploadLoading').style.display = 'none';

        if (data.success) {
            const imageWrapper = document.getElementById('imageWrapper');
            const fullImageUrl = `<?php echo URLROOT; ?>/uploads/${data.imageUrl}?t=${new Date().getTime()}`;

            imageWrapper.innerHTML = `
                <img src="${fullImageUrl}" alt="Profile Image" class="profile-image" id="profileImage">
                <div class="image-overlay">
                    <i class="fas fa-camera"></i>
                    <span>Change Photo</span>
                </div>
            `;

            if (!document.querySelector('.remove-image-btn')) {
                const removeBtn = document.createElement('button');
                removeBtn.className = 'remove-image-btn';
                removeBtn.title = 'Remove Photo';
                removeBtn.onclick = removeProfileImage;
                removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                document.querySelector('.profile-image-container').appendChild(removeBtn);
            }

            showMessage('success', 'Profile image updated successfully!');
        } else {
            showMessage('error', data.message || 'Upload failed.');
        }
    })
    .catch(error => {
        document.getElementById('uploadLoading').style.display = 'none';
        console.error('Error uploading image:', error);
        showMessage('error', 'Error uploading image.');
    });
}


    function removeProfileImage() {

        fetch('<?php echo URLROOT; ?>/patient/removeProfileImage', {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const placeholder = document.getElementById('imageWrapper');
                placeholder.innerHTML = `
                    <i class="fas fa-user" id="placeholderIcon"></i>
                    <div class="image-overlay">
                        <i class="fas fa-camera"></i>
                        <span>Upload Photo</span>
                    </div>
                `;

                const removeBtn = document.querySelector('.remove-image-btn');
                if (removeBtn) removeBtn.remove();

                document.getElementById('profileImageInput').value = '';

                showMessage('success', 'Profile image removed successfully!');
            } else {
                showMessage('error', data.message || 'Failed to remove image.');
            }
        })
        .catch(error => {
            console.error(error);
            showMessage('error', 'An error occurred while removing the image.');
        });
    }

    function showMessage(type, message) {
        const existing = document.querySelectorAll('.success-message, .error-message');
        existing.forEach(e => e.remove());

        const messageDiv = document.createElement('div');
        messageDiv.className = type === 'success' ? 'success-message' : 'error-message';
        messageDiv.textContent = message;

        const profileCard = document.querySelector('.profile-card');
        profileCard.parentNode.insertBefore(messageDiv, profileCard);

        setTimeout(() => messageDiv.remove(), 5000);
    }
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
