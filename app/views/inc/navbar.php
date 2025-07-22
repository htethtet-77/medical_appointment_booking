
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/bar.css">

</head>
<body>
<?php $name = $_SESSION['name'];?>
<header class="navbar">
    <a href="#" class="logo">Mediplus</a>
    <nav class="navbar-nav">
        <li><a href="<?php echo URLROOT; ?>/pages/home">HOME</a></li>
        <li><a href="<?php echo URLROOT; ?>/pages/doctors">DOCTORS</a></li>
        <li><a href="<?php echo URLROOT; ?>/pages/appointment">APPOINTMENT</a></li>
        <li><a href="<?php echo URLROOT; ?>/pages/contactus">CONTACT</a></li>
        <li><i class="fas fa-user"></i> <span><?php echo htmlspecialchars($name) ?></span></a></li>
        <!-- <li><a href="<?php echo URLROOT; ?>/auth/logout"><i class="fas fa-lock"></i> LOGOUT</a></li>
        <i class="fas fa-user-circle user-icon"></i>
        <span><?php echo $name; ?></span> -->

    </nav>
</header>

</body>
</html>