<?php
ini_set('session.cookie_httponly', true);
session_start();

function is_production() {
  return !!getenv("PRODUCTION");
}

if(!is_production()) {
  require('env.php');
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
}

require('db.php');
require('auth.php');
require('xml/nav.php');
?>
