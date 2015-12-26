<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]) . '/..';
require "$root/init.php";

$error = array();

if(empty($_GET['name']) && empty($_POST['name'])) {
  header('Location: /');
  exit();
}

$name = (isset($_GET['name'])) ? $_GET['name'] : $_POST['name'];

$trick = new Trick($db, $user->get_id());
$tag   = new Tag($db, $user->get_id());

$tricks = $trick->by_name($name);

$tricks_old     = array_select_prefix($tricks);
$tags_old       = array_select_key($tricks, 'tag_id');
$stances_old    = array_select_key($tricks, 'stance');
$directions_old = array_select_key($tricks, 'direction');

$tags_all  = $tag->all();

$tags_echo = '';
if(count($tags_all) > 0) {
    foreach ($tags_all as $tag) {
      $tags_echo .= li(checkbox_array('tag_ids', $tag['tag_id'], in_array($tag['tag_id'], $tags_old)) .
                     ' --- ' .
                     a($tag['name'], "/tag/edit/index.php?name=" . $tag['name']));
    }
} else {
  header('Location: /tag/create/index.php?no_tags=1');
  exit();
}

if(isset($_POST['name'])) {
  if(strlen(validate_name($_POST['name'])) > 0)
    $error['name'] = validate_name($_POST['name']);
  if(isset($_POST['tag_ids']) && count($_POST['tag_ids']) == 0)
    $error['tag_ids'] = 'no tags were selected';

  if(count($error) == 0) {
    $prefixes = generate_prefixes(array_keys($_POST['stance']), array_keys($_POST['direction']), $_POST['tag_ids']);

    $delete_tricks = compare_prefixes($tricks_old, $prefixes);
    $create_tricks = compare_prefixes($prefixes, $tricks_old);

    $trick->create($trick_name_id, $create_tricks);
    $trick->delete($trick_name_id, $delete_tricks);
    $trick->update_name($_POST['old_name'], $_POST['name']);

    header('Location: /tricks');
    exit();
  }
}

echo html(title('Homespot - Edit Trick'),
          nav() .
          content(
            h1("Edit Trick") .
            input_err($error, 'name') .
            input_err($error, 'tag_ids') .
            form('post',
              lable('stance:') .
              div(lable('normal:') . checkbox('stance[normal]', in_array('normal', $stances_old)) .
                  lable('nolli:')  . checkbox('stance[nolli]', in_array('nolli', $stances_old)) .
                  lable('switch:') . checkbox('stance[switch]', in_array('switch', $stances_old)) .
                  lable('fakie:')  . checkbox('stance[fakie]', in_array('fakie', $stances_old))) .
              lable('direction:') .
              div(lable('none:') . checkbox('direction[none]', in_array('none', $directions_old)) .
                  lable('fs:')   . checkbox('direction[fs]', in_array('fs', $directions_old)) .
                  lable('bs:')   . checkbox('direction[bs]', in_array('bs', $directions_old))) .
              input_err($error, 'name') .
              hidden('old_name', $name).
              text('name').
              br() .
              br() .
              $tags_echo .
              br() .
              submit()) .
              br() .
            a('delete trick', "/trick/delete/index.php?name=$name")));
?>
