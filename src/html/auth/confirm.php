<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]) . '/..';
require "$root/init.php";

if(isset($_GET['token'])) {
  $user->auth($_GET['token']);
  header('Location: /index.php');
  exit();
}

echo html(title('Homespot - Sign In/Up Confirmation'),
          navigation() .
          content(
            h1("Sign In/Up Confirmation") .
            p("Check your email account and login by clicking the link that was sent to you")));
?>
