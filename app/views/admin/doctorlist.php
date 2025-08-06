<title><?php echo SITENAME; ?></title>
<?php require APPROOT . '/views/inc/sidebar.php'; ?>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/doctorlist.css">


<?php
if (isset($_SESSION['error'])) {
    echo '<div class="alert error">'.htmlspecialchars($_SESSION['error']).'</div>';
    unset($_SESSION['error']);
}

if (isset($_SESSION['success'])) {
    echo '<div class="alert success">'.htmlspecialchars($_SESSION['success']).'</div>';
    unset($_SESSION['success']);
}
?>

<div class="container">
    <div class="header">
        <h1><i class="fa fa-user-md"></i> Doctor Management</h1>
        <div class="search-container">
            <div class="search-bar">
                <i class="fa fa-search"></i>
                <input type="text" id="search-input" placeholder="Search doctors...">
            </div>
            <a href="<?php echo URLROOT; ?>/admin/adddoctor" class="add-btn">
                <i class="fa fa-plus"></i> Add Doctor
            </a>
        </div>
    </div>

    <?php if (!empty($data['doctors'])) : ?>
        <div class="doctors-grid">
            <?php foreach ($data['doctors'] as $doctor) : ?>
                <div class="doctor-card">
                    <div class="doctor-photo">
                        <?php if (!empty($doctor['profile_image'])) : ?>
                            <img src="/<?= htmlspecialchars($doctor['profile_image'])?>" alt="Dr. <?= htmlspecialchars($doctor['name'] ?? 'Unknown') ?>">
                        <?php else : ?>
                            <div class="photo-placeholder">
                                <i class="fa fa-user-md"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="doctor-info">
                        <h3>Dr. <?php echo htmlspecialchars($doctor['name'] ?? 'Unknown'); ?></h3>
                        
                        <div class="info-grid">
                            <?php if (!empty($doctor['specialty'])) : ?>
                                <div class="info-item">
                                    <span class="label">Specialty</span>
                                    <span class="value"><?php echo htmlspecialchars($doctor['specialty']); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($doctor['experience'])) : ?>
                                <div class="info-item">
                                    <span class="label">Experience</span>
                                    <span class="value"><?php echo htmlspecialchars($doctor['experience']); ?> years</span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($doctor['fee'])) : ?>
                                <div class="info-item">
                                    <span class="label">Fee</span>
                                    <span class="value"><?php echo htmlspecialchars($doctor['fee']); ?>kyat</span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($doctor['phone'])) : ?>
                                <div class="info-item">
                                    <span class="label">Phone</span>
                                    <span class="value"><?php echo htmlspecialchars($doctor['phone']); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php if (!empty($doctor['address'])) : ?>
                            <div class="location">
                                <i class="fa fa-map-marker"></i>
                                <?php echo htmlspecialchars($doctor['address']); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="doctor-actions">
                        <?php $user_id = $doctor['user_id'] ?? null; ?>
                        
                        <form method="POST" action="<?= URLROOT ?>/admin/editdoctor/<?= $user_id ?>">
                            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($doctor['user_id']); ?>">
                            <button type="submit" class="btn btn-edit" title="Edit Doctor">
                                <i class="fa fa-edit"></i>
                            </button>
                        </form>
                        
                        <form method="POST" action="<?php echo URLROOT; ?>/admin/deletedoctor" 
                              onsubmit="return confirm('Are you sure you want to delete this doctor?')">
                            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($doctor['user_id']); ?>">
                            <button type="submit" class="btn btn-delete" title="Delete Doctor">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fa fa-user-md"></i>
            </div>
            <h3>No doctors found</h3>
            <p>Start by adding your first doctor to the system.</p>
            <a href="<?php echo URLROOT; ?>/admin/adddoctor" class="btn btn-primary">
                <i class="fa fa-plus"></i> Add First Doctor
            </a>
        </div>
    <?php endif; ?>

    <div class="no-results" id="no-results" style="display: none;">
        <div class="empty-icon">
            <i class="fa fa-search"></i>
        </div>
        <h3>No results found</h3>
        <p>Try adjusting your search terms.</p>
    </div>
</div>



<script>
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById('search-input');
    const doctorCards = document.querySelectorAll('.doctor-card');
    const noResults = document.getElementById('no-results');

    // Debounce search for better performance
    let searchTimeout;
    
    searchInput.addEventListener('input', function () {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const searchTerm = this.value.toLowerCase().trim();
            let visibleCount = 0;

            doctorCards.forEach(card => {
                const info = card.querySelector('.doctor-info');
                const text = info.textContent.toLowerCase();

                if (text.includes(searchTerm) || searchTerm === '') {
                    card.style.display = 'flex';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Show/hide no results message
            if (visibleCount === 0 && searchTerm !== '') {
                noResults.style.display = 'block';
            } else {
                noResults.style.display = 'none';
            }
        }, 200);
    });

    // Auto-hide alerts with smooth animation
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                alert.style.display = 'none';
            }, 500);
        });
    }, 5000);

    // Add loading states for forms
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const button = this.querySelector('button[type="submit"]');
            if (button) {
                button.style.opacity = '0.6';
                button.style.pointerEvents = 'none';
            }
        });
    });
});
</script>