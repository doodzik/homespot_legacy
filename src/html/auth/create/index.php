<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]) . '/..';
require "$root/init.php";

redirect_authed($user);

$error = array();

if(isset($_POST['email'])) {
  $email = $_POST['email'];
  $email_err = validate_email($email);
  if(strlen($email_err) > 0)
    $error['email'] = $email_err;
  if(count($error) == 0) {
    $uuid = $user->set_token(gen_uuid(), $email);
    if(!is_production())
      die("<a href='http://localhost:8080/auth/confirm.php?token=$uuid'>login</a>");
    send_login_mail($email, $uuid);
    redirect('/auth/confirm.php');
  }
}

echo html(title('Homespot - Sign In/Up'),
          navigation($user->is_authed()) .
          content(
            h1("Sign In/Up") .
            p('This website uses cookies to check if you are authenticated or not.') .
            p('By signing in or up you permit us to do so.') .
            form('post',
              input_err($error, 'email') .
              input('email') .
              submit())));
?>
