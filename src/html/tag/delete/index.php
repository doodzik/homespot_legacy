<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]) . '/..';
require "$root/init.php";
 
$tag   = new Tag($db, $user->get_id());

$error = array();

if(empty($_GET['name']) && empty($_POST['name'])) {
  header('Location: /tags');
  exit();
}

if(isset($_POST['name'])) {
  $tag->delete($_POST['name']);
  header('Location: /tags');
  exit();
}

$name = $_GET['name'];

echo html(title('Homespot - Delete Tag'),
          navigation() .
          content(
            h1("Delete Tag - " . $_GET['name']) .
            form('post',
              hidden('name', $_GET['name']) .
              submit('DELETE')
            )));
?>

