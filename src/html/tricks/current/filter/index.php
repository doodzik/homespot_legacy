<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]) . '/..';
require "$root/init.php";

$tag   = new Tag($db, $user->get_id());

$error = array();

if(isset($_POST['tag_names'])) {
  $uri_query = http_build_query(array('tag_names' => $_POST['tag_names']));
  header("Location: /index.php?$uri_query");
  exit();
}

$rows = $tag->all();
$content = '';
$tag_names = (isset($_GET['tag_names'])) ? $_GET['tag_names'] : array();
$tag_names = array_flip($tag_names);

if(count($rows) > 0) {
    foreach ($rows as $tag) {
      $tag_name = $tag['name'];
      $content .= li(checkbox_array('tag_names', $tag_name, isset($tag_names[$tag_name])) .
                     ' -- ' .
                     a($tag_name, "/tag/edit/index.php?name=$tag_name"));
    }
    $content = form('post', $content .
                            submit('filter'));
} else {
  $content   = 'you have no tags';
}

echo html(title('Homespot - Filter Current'),
          navigation() .
          content(
            h1("Filter Current by Tag") .
            $content));
?>
