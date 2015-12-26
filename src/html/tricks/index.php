<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]) . '/..';
require "$root/init.php";

$trick  = new Trick($db, $user->get_id());
$tricks = $trick->names();

$content = trick_names_ul($tricks);

echo html(title('Homespot - All Tricks'),
          navigation() .
          content(
            h1("All Tricks") .
            $content));
?>
