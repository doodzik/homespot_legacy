<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]) . '/..';
require "$root/init.php";

$tag   = new Tag($db);

$error = array();

if(empty($_GET['name']) && empty($_POST['name'])) {
  header('Location: /');
  exit();
}

if(isset($_POST['name'])) {
  $tag->update($user->get_id(), $_POST['name'], $_POST['old_name']);
  header('Location: /tags');
  exit();
}

$name = $_GET['name'];

echo html(title('Homespot - Edit tags'),
          nav() .
          content(
            h1("Edit Tags") .
            form('post',
              hidden('old_name', $name).
              text('name').
              submit()) .
            a('delete tag', "/tag/delete/index.php?name=$name")));
?>

