<?php
function compare_prefixes($as, $bs) {
  $rest = array();
  foreach ($as as $key => $a) {
    $i = 0;
    $break = false;
    foreach ($bs as $key => $b) {
      if ($a['stance'] == $b['stance'] && $a['direction'] == $b['direction'] && $a['tag_id'] == $b['tag_id'])
        $break = true;
      $i++;
      if(count($bs) == $i && !$break)
        array_push($rest, array('stance' => $a['stance'], 'direction' => $a['direction'], 'tag_id' => $a['tag_id']));
    }
  }
  return $rest;
}

function generate_prefixes($stances, $directions, $tags) {
  $prefixes = array();
  foreach ($stances as $stance) {
    if($stance != 'normal' && $stance != 'nolli' && $stance != 'switch' && $stance != 'fakie')
      continue;
    foreach ($directions as $direction) {
      if($direction != 'none' && $direction != 'fs' && $direction != 'bs')
        continue;
      foreach ($tag_ids as $tag_id) {
        array_push($prefixes, array(
          'stance'    => $stance,
          'direction' => $direction,
          'tag_id'    => $tag_id
        ));
      }
    }
  }
  return $prefixes;
}

function array_select_prefix($tricks) {
  $tricks_old = array();
  foreach($tricks as $trick) {
    array_push($tricks_old, array(
      'stance'    => $trick['stance'],
      'direction' => $trick['direction'],
      'tag_id'    => $trick['tag_id']
    ));
  }
  return $tricks_old;
}

function array_select_key($array, $key) {
  $new_array = array();
  foreach($array as $a)
    array_push($new_array, $a[$key]);
  return array_unique($new_array);
}
?>
