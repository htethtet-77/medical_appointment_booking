<?php

// DB Params
define('DB_HOST', 'db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'medical');

// Define App Root
define ('APPROOT', dirname(dirname(__FILE__)));

// Define URL Root
define('URLROOT', 'http://localhost:8000');

// Define SITENAME
define('SITENAME', 'Medical Appointment Booking');

define("ROLE_ADMIN",1);
define("ROLE_DOCTOR",2);
define("ROLE_PATIENT",3);

// config.php
define('RECAPTCHA_V2_SITEKEY', '6Ldt3aorAAAAAFE1cumOeq5KAxWJgLSLBIpLaWa- ');
define('RECAPTCHA_V2_SECRET', '6Ldt3aorAAAAACscGy-p0XEbegh8wVFpHY6Lrd56 ');

define('RECAPTCHA_V3_SITEKEY', '6LeNNasrAAAAAIQSNkn9mA5fYBg8jD2vhSaaAu29');
define('RECAPTCHA_V3_SECRET', '6LeNNasrAAAAABsO2R5PjSSuPDeLxgyzdvOUhO7v');

