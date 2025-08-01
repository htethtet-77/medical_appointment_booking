
<title><?php echo SITENAME; ?></title>
<?php require APPROOT . '/views/inc/sidebar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/doctorlist.css?v=2">
<?php
if (isset($_SESSION['error'])) {
    echo '<div>'.htmlspecialchars($_SESSION['error']).'</div>';
    unset($_SESSION['error']);
}

if (isset($_SESSION['success'])) {
    echo '<div>'.htmlspecialchars($_SESSION['success']).'</div>';
    unset($_SESSION['success']);
}
?>

<div class="container">
    <div class="doctor-list-header">
        <h1>Doctor List</h1>
        <div class="search-bar">
            <button><span class="material-icons"><i class="fa fa-search" aria-hidden="true"></i></span></button> 
            <input type="text" placeholder="Search by Specialty">
        </div>
    </div>

    <?php if (!empty($data['doctors'])) : ?>
    <?php foreach ($data['doctors'] as $doctor) : ?>
        <div class="doctor-card">
            <div class="avatar">
                <img src="/<?= htmlspecialchars($doctor['profile_image'])?>">
             </div>
            <div class="info">
                <h3>Dr. <?php echo htmlspecialchars($doctor['name'] ?? 'UN'); ?></h3>
                <p>Specialization: <?php echo htmlspecialchars($doctor['specialty'] ?? ''); ?></p>
                <p><?php echo htmlspecialchars($doctor['experience'] ?? ''); ?> years of Exp</p>
                <p>Consultation Fee: <?php echo htmlspecialchars($doctor['fee'] ?? ''); ?>$</p>
                <p>Location: <?php echo htmlspecialchars($doctor['address'] ?? ''); ?></p>
            </div>
            <div class="actions">
               <?php $user_id = $doctor['user_id'] ?? null;?>

                <form method="POST" action="<?= URLROOT ?>/admin/editdoctor/<?= $user_id ?>" >

                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($doctor['user_id']); ?>">

                    <button type="submit" class="edit-btn">Edit</button>

                    </form>
                <form method="POST" action="<?php echo URLROOT; ?>/admin/deletedoctor" >
                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($doctor['user_id']); ?>">

                    <button type="submit" class="delete-btn">Delete</button>

                    </form>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>No doctors found.</p>
<?php endif; ?>


<a href="<?php echo URLROOT; ?>/admin/adddoctor" class="add-doctor-btn">+</a>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.querySelector('input[placeholder="Search by Specialty"]');
    const doctorCards = document.querySelectorAll('.doctor-card');

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase();

            doctorCards.forEach(card => {
                const info = card.querySelector('.info');
                const text = info.textContent.toLowerCase();

                if (text.includes(searchTerm)) {
                    card.style.display = 'flex'; // Show match
                } else {
                    card.style.display = 'none'; // Hide non-match
                }
            });
        });
    }
});
</script>
