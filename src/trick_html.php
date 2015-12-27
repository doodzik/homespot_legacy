<?php
function navigation ($is_authed) {
  if($is_authed) {
    $str = a('Home', '/') .
           a('Create Trick', '/trick/create') .
           a('All Tricks', '/tricks') .
           a('Create Tag', '/tag/create') .
           a('All Tags', '/tags') .
           a('Logout', '/auth/delete');
  } else {
    $str = a('Sign In/Up', '/auth/create');
  }
  return nav($str . a('About', '/about'));
}

function trick_link($trick_name) {
  return a($trick_name, "/trick/edit/index.php?name=" . $trick_name);
}

function trick_full_name($trick_name, $direction, $stance, $tag) {
  $direction = ($direction == 'none') ? '' : $direction;
  $stance = ($stance == 'normal') ? '' : $stance;
  return trim($stance . ' ' . $direction . ' ' . trick_link($trick_name) . ': ' . $tag);
}


function trick_names_checkbox_ul($tricks) {
  if(count($tricks) > 0) {
    $content = '';
    foreach ($tricks as $trick)
      $content .= li(checkbox_array('trick_ids', $trick['trick_id']) .
                  ' --- ' .
                  trick_full_name($trick['name'], $trick['direction'], $trick['stance'], $trick['tag_name']));
    $content = ul($content);
  } else {
  }
  return $content;
}

function trick_names_ul($tricks) {
  if(count($tricks) > 0) {
    $content = '';
    foreach ($tricks as $trick)
      $content .= li(trick_link($trick['name']));
    $content = ul($content);
  } else {
    $content = 'you have no tricks';
  }
  return $content;
}
?>
