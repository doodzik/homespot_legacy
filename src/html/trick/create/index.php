<?php
require(__DIR__ . '/../../../init.php');

$tag   = new Tag($db, $user->get_id());
$trick = new Trick($db, $user->get_id());

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
      $trick_name_id = $trick->create_trick_name($name);
      $trick->create($trick_name_id, $prefixes);
      redirect();
    }
  }
}

$rows  = $tag->all();
$tags  = tag_ids_checkbox_ul($rows);

if(!$tags)
  redirect('/tag/create/index.php?no_tags=1');

echo html(title('Homespot - Create Trick'),
          navigation($user->is_authed()) .
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
