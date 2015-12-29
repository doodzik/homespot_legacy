<?php
require(__DIR__ . '/../../../init.php');

$error = array();

$tag   = new Tag($db, $user->get_id());

if(isset($_GET['no_tags']))
  $error['no_tags'] = 'you need tags to use this service';

if(isset($_POST['name'])) {
  if(count($error) == 0) {
    $tag->create($_POST['name']);
    redirect('/tags');
  }
}

echo html(title('Homespot - Create Tag'),
          navigation($user->is_authed()) .
          content(
            h1("Create Tag") .
            input_err($error, 'no_tags') .
            form('post',
              input_err($error, 'name') .
              input('text', 'name') .
              submit()
            )));
?>

