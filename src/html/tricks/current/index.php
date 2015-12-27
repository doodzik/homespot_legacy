<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]) . '/..';
require "$root/init.php";

$trick = new Trick($db, $user->get_id());
$tag   = new Tag($db, $user->get_id());

$err = '';

if(isset($_POST['submit']) && $_POST['submit'] == 'good') {
  if(isset($_POST['trick_ids'])) {
    $trick->defer($_POST['trick_ids']);
    redirect();
  } else {
    $err = 'select at least one track';
  }
}

if(isset($_POST['submit']) && $_POST['submit'] == 'bad') {
  if(isset($_POST['track_ids'])) {
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

$tricks  = gen_trick_rows($trick->current($tag_names));

$content = '';
if(count($tricks) == 0) {
  $content = 'you have no tricks';
} else {
  foreach ($tricks as $trick)
    $content .= $trick;
}

echo html(title('Homespot'),
          navigation($user->is_authed()) .
          content(
            h1("Current Tricks") .
            $err .
            a('change filters', '/tricks/current/filter/index.php?' . $tags_query) .
            br() .
            p("current filters: $filters") .
            form('post',
              submit('good') .
              submit('bad') .
              ul($content))));
?>
