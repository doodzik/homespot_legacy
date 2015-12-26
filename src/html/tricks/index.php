<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]) . '/..';
require "$root/init.php";

$trick  = new Trick($db);
$tricks = $trick->names($user->get_id());

if(count($tricks) > 0) {
  $content = '';
  foreach ($tricks as $trick)
    $content .= li($trick->get_link());
} else {
  $content = 'you have no tricks';
}

echo html(title('Homespot - All Tricks'),
          nav() .
          content(
            h1("All Tricks") .
            ul($content)));
?>
