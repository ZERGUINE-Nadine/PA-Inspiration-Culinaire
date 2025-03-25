<?php 
function log_php_errors($path) {
    ini_set('error_reporting', E_ALL);
    ini_set('log_errors', 'On');
    ini_set('display_errors', 'Off');
    ini_set('error_log', $path);
}