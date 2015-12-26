<?php
function navigation () {
  if(logged_in()) {
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
