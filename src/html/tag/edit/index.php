<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]) . '/..';
require "$root/init.php";

$tag   = new Tag($db, $user->get_id());

$error = array();

if(empty($_GET['name']) && empty($_POST['name']))
  redirect();

if(isset($_POST['name'])) {
  $tag->update($_POST['name'], $_POST['old_name']);
  redirect('/tags');

$name = $_GET['name'];

echo html(title('Homespot - Edit tags'),
          navigation($user->is_authed()) .
          content(
            h1("Edit Tags") .
            form('post',
              hidden('old_name', $name).
              text('name').
              submit()) .
            a('delete tag', "/tag/delete/index.php?name=$name")));
?>

