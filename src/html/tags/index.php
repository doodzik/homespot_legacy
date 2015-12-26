<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]) . '/..';
require "$root/init.php";

$tag   = new Tag($db);

$rows = $tag->all($user->get_id());
$content = '';
if(count($rows) > 0) {
  foreach ($rows as $_tag)
    $content .= li(a($_tag['name'], "/tag/edit/index.php?name=" . $_tag['name']));
} else {
  $content = 'you have no tags';
}

echo html(title('Homespot - All Tags'),
          nav() .
          content(
            h1("All Tags") .
            ul($content)));
?>

