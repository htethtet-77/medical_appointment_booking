<?php


$doctor = $_SESSION['current_doctor'] ?? null;

if (!$doctor || !is_array($doctor)) {
    redirect('pages/login');
    exit;
}
?>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/bar.css">

</head>
<body>
<?php $doctor = $_SESSION['current_doctor'];?>
<header class="navbar">
    <a href="<?php echo URLROOT; ?>/doctor/dash" class="logo">Doctor's Dashboard</a>


    <nav class="navbar-nav">
        <!-- <li><a href="<?php echo URLROOT; ?>/doctor/dash">HOME</a></li> -->
        <!-- <li><a href="<?php echo URLROOT; ?>/doctor/all">APPOINTMENTS</a></li> -->
        <li><a href="<?php echo URLROOT; ?>/doctor/profile"><i class="fas fa-user"></i> <span><?php echo " Dr.";echo htmlspecialchars($doctor['name']) ?></span></a></li></a>

        <!-- <li><i class="fas fa-user"></i> Doctor</a></li> -->
    </nav>
</header>

</body>
</html>