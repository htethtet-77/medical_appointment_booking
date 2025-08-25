
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/bar.css?v=2">

</head>
<body>
<?php 
$isLoggedIn = isset($_SESSION['current_user']);
$user = $isLoggedIn ? $_SESSION['current_user'] : "";
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
    <a href="<?php echo defined('URLROOT') ? URLROOT : ''; ?>/admin/dashboard" class="logo">Admin Dashboard</a>
    <button class="navbar-toggle" onclick="toggleNavbar()">
        <i class="fas fa-bars"></i>
    </button>
    <nav class="navbar-nav" id="navbarNav">
        <li>
            <a href="<?php echo defined('URLROOT') ? URLROOT : ''; ?>/admin/dashboard" 
               class="<?php echo isActivePage('/admin/dashboard') ? 'active' : ''; ?>">
               HOME
            </a>
        </li>
        <li>
            <a href="<?php echo defined('URLROOT') ? URLROOT : ''; ?>/admin/patientlist"
               class="<?php echo isActivePage('/admin/patientlist') ? 'active' : ''; ?>">
                PATIENTS
            </a>
        </li>
        <li>
            <a href="<?php echo defined('URLROOT') ? URLROOT : ''; ?>/admin/doctorlist"
               class="<?php echo isActivePage('/admin/doctorlist') ? 'active' : ''; ?>">
               DOCTORS
            </a>
        </li>
        <li>
            <a href="<?php echo defined('URLROOT') ? URLROOT : ''; ?>/admin/appointmentview"
               class="<?php echo isActivePage('/admin/appointmentview') ? 'active' : ''; ?>">
               APPOINTMENTS
            </a>
        </li>
            <?php if($isLoggedIn): ?>
            
            <li>
                    <i class="fas fa-user"></i>
                    <span><?php echo htmlspecialchars($user['name']); ?></span>
            
            </li>
            <li>
                <a href="<?php echo defined('URLROOT') ? URLROOT : ''; ?>/auth/logout">Logout</a>
            </li>
        
        <?php endif; ?>    </nav>
</header>

</body>
</html>