<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]) . '/..';
require "$root/init.php";

redirect_authed();

$error = array();

if(isset($_POST['email'])) {
  $email = $_POST['email'];
  $email_err = validate_email($email);
  if(strlen($email_err) > 0)
    $error['email'] = $email_err;
  if(count($error) == 0) {
    $uuid = gen_uuid();
    $count = update_token($db, $uuid, $email);
    if($count == 0)
      create_user($db, $uuid, $email);
    if(!is_production())
      die("http://localhost:8080/auth/confirm.php?token=$uuid");
    send_login_mail($email, $uuid);
    header('Location: ../confirm.php');
    exit();
  }
}

echo html(title('Homespot - Sign In/Up'),
          nav() .
          content(
            h1("Sign In/Up") .
            form('post',
              input_err($error, 'email') .
              input('email') .
              submit()
            )));
?>
