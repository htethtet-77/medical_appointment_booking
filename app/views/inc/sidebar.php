
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/bar.css">

</head>
<body>
<?php 
$isLoggedIn = isset($_SESSION['current_user']);
$user = $isLoggedIn ? $_SESSION['current_user'] : null;
?>
<header class="navbar">
    <a href="#" class="logo">Dashboard</a>

    <nav class="navbar-nav">
        <li><a href="<?php echo URLROOT; ?>/admin/dashboard">HOME</a></li>
        <li><a href="<?php echo URLROOT; ?>/admin/patientlist">PATIENTS</a></li>
         <li><a href="<?php echo URLROOT; ?>/admin/doctorlist">DOCTORS</a></li>
        <li><a href="<?php echo URLROOT; ?>/admin/appointmentview">APPOINTMENTS</a></li>
            <?php if($isLoggedIn): ?>
            <li>
                    <i class="fas fa-user"></i>
                    <span><?php echo htmlspecialchars($user['name']); ?></span>
            
            </li>
            <li>
                <a href="<?php echo URLROOT; ?>/auth/logout">Logout</a>
            </li>
        
        <?php endif; ?>    </nav>
</header>

</body>
</html>