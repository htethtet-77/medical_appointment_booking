<?php

// DB Params
define('DB_HOST', 'db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'mvcoop');

// Define App Root
define ('APPROOT', dirname(dirname(__FILE__)));

// Define URL Root
define('URLROOT', 'http://localhost:8000');

// Define SITENAME
define('SITENAME', 'Medical Appointment Booking');

define("ROLE_ADMIN",1);
define("ROLE_DOCTOR",2);
define("ROLE_PATIENT",3);