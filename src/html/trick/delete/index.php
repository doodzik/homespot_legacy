<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]) . '/..';
require "$root/init.php";

if(empty($_GET['name']) && empty($_POST['name'])) {
  header('Location: /');
  exit();
}

$trick  = new Trick($db, $user->get_id());

if(isset($_POST['name'])) {
  $tricks = $trick->delete_by_name($user->get_id(), $_POST['name']);
  header('Location: /tricks');
  exit();
}

$name = $_GET['name'];

echo html(title('Homespot - Delete Trick'),
          nav() .
          content(
            h1("Delete Trick - " . $_GET['name']) .
            form('post',
              hidden('name', $_GET['name']) .
              submit('DELETE')
            )));
?>
