<?php

define('ROOT', dirname(__DIR__));

// Application Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'your_database');
define('DB_USER', 'root');
define('DB_PASS', '');
define('BASE_URL', 'http://localhost:8000/');
define('ENVIRONMENT', 'development'); // production/development

// Error reporting
if (ENVIRONMENT === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}