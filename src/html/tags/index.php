<?php
require(__DIR__ . '/../../init.php');

$tag     = new Tag($db, $user->get_id());

$rows    = $tag->all();
$content = tag_names_ul($rows);

if(!$content)
  redirect('/tag/create/index.php?no_tags=1');

echo html(title('Homespot - All Tags'),
          navigation($user->is_authed()) .
          content(
            h1("All Tags") .
            p('tag count: ' . count($rows)) .
            $content));
?>

