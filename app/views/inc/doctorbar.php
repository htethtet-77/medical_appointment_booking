
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/bar.css">

</head>
<body>
<?php $name = $_SESSION['current_user'];?>
<header class="navbar">
    <a href="#" class="logo">Dashboard</a>

    <button class="navbar-toggle" onclick="document.querySelector('.navbar-nav').classList.toggle('show')">
        &#9776; </button>

    <nav class="navbar-nav">
        <li><a href="<?php echo URLROOT; ?>/doctor/newappointment">NEW APPOINTMENT</a></li>
        <li><a href="<?php echo URLROOT; ?>/doctor/all">HISTORY</a></li>
        <li><a href="<?php echo URLROOT; ?>/doctor/profile"><i class="fas fa-user"></i> <span><?php echo " Dr.";echo htmlspecialchars($name['name']) ?></span></a></li></a>

        <!-- <li><i class="fas fa-user"></i> Doctor</a></li> -->
    </nav>
</header>

</body>
</html>