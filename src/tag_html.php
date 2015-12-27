<?php
function tag_link($tag_name) {
  return a($tag_name, "/tag/edit/index.php?name=$tag_name");
}

function tag_checkbox($tag_name, $tag_defaults) {
  return checkbox_array('tag_names', $tag_name, in_array($tag_name, $tag_defaults)) . 
          ' --- ' .
          tag_link($tag_name);
}

function tag_names_ul($tags) {
  if(count($tags) > 0) {
    $content = '';
    foreach ($tags as $tag)
      $content .= li(tag_link($tag_name));
    $content = ul($content);
    return $content;
  } else {
    return false;
  }
}

function tag_names_checkbox_ul($tags, $tag_defaults) {
  if(count($tags) > 0) {
    $content = '';
    foreach ($tags as $tag)
      $content .= li(tag_checkbox($tag['name'], $tag_defaults));
    $content = ul($content);
    return $content;
  } else {
    return false;
  }
}

function tag_ids_checkbox_ul($tags, $tag_defaults = array()) {
  if(count($tags) > 0) {
    $content = '';
    foreach ($tags as $tag) {
      $default = (count($tag_defaults) > 0) ? in_array($tag['tag_id'], $tag_defaults) : false;
      $content .= li(checkbox_array('tag_ids', $tag['tag_id'], $default) . ' --- ' . tag_link($tag['name']));
    }
    $content = ul($content);
    return $content;
  } else {
    return false;
  }
}

?>

