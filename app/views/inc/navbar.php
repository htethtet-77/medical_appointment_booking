<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/bar.css?v=2">

    
</head>
<body>

<?php 
$isLoggedIn = isset($_SESSION['current_patient']);
$user = $isLoggedIn ? $_SESSION['current_patient'] : null;

// Function to detect active page
function isActivePage($pagePath) {
    $currentURL = $_SERVER['REQUEST_URI'];
    $urlRoot = defined('URLROOT') ? URLROOT : '';
    
    // Remove URLROOT from comparison
    $cleanURL = str_replace($urlRoot, '', $currentURL);
    $cleanURL = strtok($cleanURL, '?'); // Remove query parameters
    $cleanURL = rtrim($cleanURL, '/');
    
    return strpos($cleanURL, $pagePath) !== false || 
           ($pagePath === '/pages/home' && ($cleanURL === '' || $cleanURL === '/'));
}
?>

<header class="navbar">
    <a href="<?php echo defined('URLROOT') ? URLROOT : ''; ?>/pages/home" class="logo">Mediplus</a>
    <button class="navbar-toggle" onclick="toggleNavbar()">
        <i class="fas fa-bars"></i>
    </button>
    <nav class="navbar-nav" id="navbarNav">
        <li>
            <a href="<?php echo defined('URLROOT') ? URLROOT : ''; ?>/pages/home" 
               class="<?php echo isActivePage('/pages/home') ? 'active' : ''; ?>">
               HOME
            </a>
        </li>
        <li>
            <a href="<?php echo defined('URLROOT') ? URLROOT : ''; ?>/patient/doctors"
               class="<?php echo isActivePage('/patient/doctors') ? 'active' : ''; ?>">
               DOCTORS
            </a>
        </li>
        <li>
            <a href="<?php echo defined('URLROOT') ? URLROOT : ''; ?>/appointment/appointmentlist"
               class="<?php echo isActivePage('/appointment/appointmentlist') ? 'active' : ''; ?>">
               APPOINTMENT
            </a>
        </li>
        <li>
            <a href="<?php echo defined('URLROOT') ? URLROOT : ''; ?>/pages/contactus"
               class="<?php echo isActivePage('/pages/contactus') ? 'active' : ''; ?>">
               CONTACT
            </a>
        </li>
        
        <?php if($isLoggedIn): ?>
            <li>
                <a href="<?php echo defined('URLROOT') ? URLROOT : ''; ?>/pages/userprofile"
                   class="<?php echo isActivePage('/pages/userprofile') ? 'active' : ''; ?>">
                    <i class="fas fa-user"></i>
                    <span><?php echo htmlspecialchars($user['name']); ?></span>
                </a>
            </li>
            <li>
                <a href="<?php echo defined('URLROOT') ? URLROOT : ''; ?>/auth/logout">Logout</a>
            </li>
        <?php else: ?>
            <li>
                <a href="<?php echo defined('URLROOT') ? URLROOT : ''; ?>/pages/login"
                   class="<?php echo isActivePage('/pages/login') ? 'active' : ''; ?>">
                    <i class="fas fa-user"></i>
                    <span>Guest</span>
                </a>
            </li>
        <?php endif; ?>
    </nav>
</header>

<script>
function toggleNavbar() {
    const navbar = document.getElementById('navbarNav');
    navbar.classList.toggle('show');
}
</script>

</body>
</html>