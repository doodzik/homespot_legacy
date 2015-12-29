<?php
require(__DIR__ . '/../../init.php');

$trick  = new Trick($db, $user->get_id());
$tricks = $trick->names();

$content = trick_names_ul($tricks);

echo html(title('Homespot - All Tricks'),
          navigation($user->is_authed()) .
          content(
            h1("All Tricks") .
            $content));
?>
