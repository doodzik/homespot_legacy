<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]) . '/..';
require "$root/init.php";

$tag   = new Tag($db);
$trick = new Trick($db);

$error = array();

if(isset($_POST['name'])) {
  if(strlen(validate_name($_POST['name'])) > 0)
    $error['name'] = validate_name($_POST['name']);

  if(empty($_POST['tag_ids']) || count($_POST['tag_ids']) == 0)
    $error['tag_ids'] = 'no tags were selected';

  if(count($error) == 0) {
    $prefixes = generate_prefixes(array_keys($_POST['stance']), array_keys($_POST['direction']), $_POST['tag_ids']);

    $name = $_POST['name'];
    if(count($error) == 0) {
      $trick_name_id = $trick->create_trick_name($user->get_id(), $name);
      $trick->create($user->get_id(), $trick_name_id, $prefixes);
      header('Location: /');
      exit();
    }
  }
}

$rows  = $tag->all($user->get_id());
$tags = '';

if(count($rows) > 0) {
    foreach ($rows as $tag) {
      $tag_name = $tag['name'];
      $tags .= li(checkbox_array('tag_ids', $tag['tag_id']) .
                     ' -- ' .
                     a($tag_name, "/tag/edit/index.php?name=$tag_name"));
    }
} else {
  $tags   = 'you have no tags';
}


echo html(title('Homespot - Create Trick'),
          nav() .
          content(
            h1("Create Trick") .
            form('post',
              lable('stance:') .
              div(lable('normal:') . checkbox('stance[normal]') .
                  lable('nolli:')  . checkbox('stance[nolli]') .
                  lable('switch:') . checkbox('stance[switch]') .
                  lable('fakie:')  . checkbox('stance[fakie]')) .
              lable('direction:') .
              div(lable('none:') . checkbox('direction[none]') .
                  lable('fs:')   . checkbox('direction[fs]', false) .
                  lable('bs:')   . checkbox('direction[bs]', false)) .
              input_err($error, 'name') .
              input('text', 'name') .
              div(lable('tags:')) .
              input_err($error, 'tag_ids') .
              $tags .
              submit()
            )));
?>
