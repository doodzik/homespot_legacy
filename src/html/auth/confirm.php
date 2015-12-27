<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]) . '/..';
require "$root/init.php";

redirect_authed($user);

if(isset($_GET['token'])) {
  $user->authenticate($_GET['token']);
  redirect();
}

echo html(title('Homespot - Sign In/Up Confirmation'),
          navigation($user->is_authed()) .
          content(
            h1("Sign In/Up Confirmation") .
            p("Check your email account and login by clicking the link that was sent to you")));
?>
