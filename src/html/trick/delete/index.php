<?php
require(__DIR__ . '/../../../init.php');

if(empty($_GET['name']) && empty($_POST['name']))
  redirect();

$trick  = new Trick($db, $user->get_id());

if(isset($_POST['name'])) {
  $trick->delete_by_name($_POST['name']);
  redirect('/tricks');
}

$name = $_GET['name'];

echo html(title('Homespot - Delete Trick'),
          navigation($user->is_authed()) .
          content(
            h1("Delete Trick - " . $_GET['name']) .
            form('post',
              hidden('name', $_GET['name']) .
              submit('DELETE')
            )));
?>
