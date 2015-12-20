<?php
  function get_request($name) {
    $value = '';
    if(isset($_POST[$name]))
      $value = $_POST[$name];
    if(isset($_GET[$name]))
      $value = $_GET[$name];
    return $value;
  }
?>
