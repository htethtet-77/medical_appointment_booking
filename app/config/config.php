<?php


define('DB_HOST', getenv('DB_HOST'));
define('DB_USER', getenv('DB_USER'));
define('DB_PASS', getenv('DB_PASS'));
define('DB_NAME', getenv('DB_NAME'));

define('APPROOT', dirname(dirname(__FILE__)));

define('URLROOT', getenv('APP_URL'));
define('SITENAME', getenv('SITE_NAME'));

define('ROLE_ADMIN', (int) getenv('ROLE_ADMIN'));
define('ROLE_DOCTOR', (int) getenv('ROLE_DOCTOR'));
define('ROLE_PATIENT', (int) getenv('ROLE_PATIENT'));

define('RECAPTCHA_V2_SITEKEY', getenv('RECAPTCHA_V2_SITEKEY'));
define('RECAPTCHA_V2_SECRET', getenv('RECAPTCHA_V2_SECRET'));

define('RECAPTCHA_V3_SITEKEY', getenv('RECAPTCHA_V3_SITEKEY'));
define('RECAPTCHA_V3_SECRET', getenv('RECAPTCHA_V3_SECRET'));

define('OIDC_PROVIDER_URL', getenv('OIDC_PROVIDER_URL'));
define('OIDC_CLIENT_ID', getenv('OIDC_CLIENT_ID'));
define('OIDC_CLIENT_SECRET', getenv('OIDC_CLIENT_SECRET'));
define('OIDC_REDIRECT_URI', getenv('OIDC_REDIRECT_URI'));