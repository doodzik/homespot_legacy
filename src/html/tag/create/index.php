<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]) . '/..';
require "$root/init.php";

$error = array();

$tag   = new Tag($db, $user->get_id());

if(isset($_GET['no_tags']))
  $error['no_tags'] = 'you need tags to use this service';

if(isset($_POST['name'])) {
  if(count($error) == 0) {
    $tag->create($_POST['name']);
    header('Location: /tags');
    exit();
  }
}

echo html(title('Homespot - Create Tag'),
          nav() .
          content(
            h1("Create Tag") .
            input_err($error, 'no_tags') .
            form('post',
              input_err($error, 'name') .
              input('text', 'name') .
              submit()
            )));
?>

