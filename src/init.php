<?php
ini_set('session.cookie_httponly', true);
session_start();

if(!getenv("PRODUCTION"))
  require('env.php');

require('db.php');
require('auth.php');
?>
