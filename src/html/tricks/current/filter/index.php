<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]) . '/..';
require "$root/init.php";

redirect_not_authed();

$error = array();

if(isset($_POST['tag_names'])) {
  $uri_query = http_build_query(array('tag_names' => $_POST['tag_names']));
  header("Location: /index.php?$uri_query");
  exit();
}

$query = 'SELECT name, tag_id
            FROM TAG
            WHERE user_id = :user_id';
$stmt = $db -> prepare($query);
$stmt -> bindValue(':user_id', $_SESSION['user_id']);
$stmt -> execute();
$rows  = $stmt->fetchAll();

$content = '';

if(count($rows) > 0) {
    foreach ($rows as $tag) {
      $tag_name = $tag['name'];
      $content .= li(checkbox_array('tag_names', $tag_name) .
                     ' -- ' .
                     a($tag_name, "/tag/edit/index.php?name=$tag_name"));
    }
    $content = form('post', $content .
                            submit('filter'));
} else {
  $content   = 'you have no tags';
}

echo html(title('Homespot - Filter Current'),
          nav() .
          content(
            h1("Filter Current by Tag") .
            $content));
?>
