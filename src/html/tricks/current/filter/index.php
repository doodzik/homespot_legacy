<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]) . '/..';
require "$root/init.php";

$tag   = new Tag($db, $user->get_id());

$error = array();

if(isset($_POST['tag_names'])) {
  $uri_query = http_build_query(array('tag_names' => $_POST['tag_names']));
  redirect("/index.php?$uri_query");
}

$tags = $tag->all();
$tag_defaults = (isset($_GET['tag_names'])) ? $_GET['tag_names'] : array();

$content = tag_names_checkbox_ul($tags, $tag_defaults);
if($content)
  $content = form('post', $content . submit('filter'));
else
  $content = 'you have no tags';

echo html(title('Homespot - Filter Current'),
          navigation($user->is_authed()) .
          content(
            h1("Filter Current by Tag") .
            $content));
?>
