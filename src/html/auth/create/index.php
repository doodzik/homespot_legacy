<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]) . '/..';
require "$root/init.php";

$error = array();

if(isset($_POST['email'])) {
  $email = $_POST['email'];
  $email_err = validate_email($email);
  if(strlen($email_err) > 0)
    $error['email'] = $email_err;
  if(count($error) == 0) {
    $uuid = gen_uuid();
    update_token($db, $uuid, $email);
    if(is_production())
      send_login_mail($email, $uuid);
    else
      echo("http://localhost:8080/auth/confirm.php?token=$uuid");
    include "$root/html/auth/confirm.php";
    exit();
  }
}

echo html(title('hello world'),
          nav() .
          content(
            h1("Sign In/Up") .
            form('post',
              input_err($error, 'email') .
              input('email') .
              submit()
            )));
?>
