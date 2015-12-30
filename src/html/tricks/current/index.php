<?php
require(__DIR__ . '/../../../init.php');

$trick = new Trick($db, $user->get_id());
$tag   = new Tag($db, $user->get_id());

$err = '';

if(isset($_POST['submit']) && $_POST['submit'] == 'defer') {
  if(isset($_POST['trick_ids'])) {
    $trick->defer($_POST['trick_ids']);
    redirect();
  } else {
    $err = 'select at least one track';
  }
}

if(isset($_POST['submit']) && $_POST['submit'] == 'reset') {
  if(isset($_POST['trick_ids'])) {
    $trick->reset($_POST['trick_ids']);
    redirect();
  } else {
    $err = 'select at least one track';
  }
}

if(empty($_GET['tag_names'])) {
  $rows = $tag->all_names();
  if(count($rows) > 0) {
    $uri_query = http_build_query(array('tag_names' => $rows));
    redirect("/index.php?$uri_query");
  } else {
    redirect('/tag/create/index.php?no_tags=1');
  }
}

$tag_names = $_GET['tag_names'];
$filters   = join(', ', $tag_names);
$tags_query = http_build_query(array('tag_names' => $tag_names));

$tricks  = $trick->current($tag_names);

$content = trick_names_checkbox_ul($tricks);
if($content) {
  $content = form('post',
                  submit('defer') .
                  submit('reset') .
                  $content);
} else {
  $content = p('you have no more tricks to do');
}

echo html(title('Homespot'),
          navigation($user->is_authed()) .
          content(
            h1("Current Tricks") .
            $err .
            a('change filters', '/tricks/current/filter/index.php?' . $tags_query) .
            br() .
            p("current filters: $filters") .
            $content));
?>
