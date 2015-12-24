<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]) . '/..';
require "$root/init.php";

redirect_not_authed();

$error = array();

if(isset($_POST['no_tags'])) {
  $error['no_tags'] = 'you need tags to use this service';

if(isset($_POST['name'])) {
  $prefixes = array();
  if(count($error) == 0) {
    $query = 'INSERT INTO TAG (name, user_id) VALUES (:name, :user_id)';
    $stmt = $db -> prepare($query);
    $stmt->bindValue(':name', $_POST['name']);
    $stmt->bindValue(':user_id', $_SESSION['user_id']);
    $stmt -> execute();

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

