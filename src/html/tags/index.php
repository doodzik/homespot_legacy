<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]) . '/..';
require "$root/init.php";

$tag     = new Tag($db, $user->get_id());

$rows    = $tag->all();
$content = tag_names_ul($rows);

if(!$content)
  redirect('/tag/create/index.php?no_tags=1');

echo html(title('Homespot - All Tags'),
          navigation($user->is_authed()) .
          content(
            h1("All Tags") .
            ul($content)));
?>

