<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]) . '/..';
require "$root/init.php";

redirect_not_authed();

$statement = $db->prepare('SELECT name 
                              FROM `TRICK_NAME`
                              WHERE `user_id` = :user_id
                              ORDER BY `name` ASC');
$statement->bindValue(":user_id", $_SESSION['user_id']);
$count = $statement->execute();
$rows = $statement->fetchAll();
if(count($rows) > 0) {
  $content = '';
  foreach ($rows as $trick) {
    $trick_name = $trick['name'];
    $content .= li(a($trick_name, "/trick/edit/index.php?name=$trick_name"));
  }
} else {
  $content = 'you have no tricks';
}

echo html(title('Homespot - All Tricks'),
          nav() .
          content(
            h1("All Tricks") .
            ul($content)));
?>
