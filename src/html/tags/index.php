<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]) . '/..';
require "$root/init.php";

redirect_not_authed();

$statement = $db->prepare('SELECT *
                              FROM `TAG`
                              WHERE `user_id` = :user_id
                              ORDER BY `name` ASC');
$statement->bindValue(":user_id", $_SESSION['user_id']);
$count = $statement->execute();
$rows = $statement->fetchAll();
if(count($rows) > 0) {
  $content = '';
  foreach ($rows as $tag) {
    $tag_name = $tag['name'];
    $content .= li(a($tag_name, "/tag/edit/index.php?name=$tag_name"));
  }
} else {
  $content = 'you have no tags';
}

echo html(title('Homespot - All Tags'),
          nav() .
          content(
            h1("All Tags") .
            ul($content)));
?>

