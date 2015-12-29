<?php
ini_set('session.cookie_httponly', true);
session_start();
require('env.php');

function is_production() {
  return getenv("PRODUCTION") !== false;
}

if(!is_production()) {
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
}

require('db.php');
require('auth.php');
require('User.php');
require('Trick.php');
require('Tag.php');
require('trick_html.php');
require('tag_html.php');
$user = new User($db, $_SESSION);
require('html_fn/html_fn.php');
?>
