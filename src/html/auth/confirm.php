<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]) . '/..';
require "$root/init.php";

if(isset($_GET['token'])) {
  $token = $_GET['token'];
  $user->auth($token);
  header('Location: /index.php');
  exit();
}

echo html(title('Homespot - Sign In/Up Confirmation'),
          nav() .
          content(
            h1("Sign In/Up Confirmation") .
            p("Check your email account and login by clicking the link that was sent to you")));
?>
