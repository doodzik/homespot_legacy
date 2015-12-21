<?php 
  function validate_email($email) {
    $value = '';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
      $value = 'email is invalid';
    return $value;
  }

  function validate_name($name) {
    if(strlen($name >= 150))
      return 'name too long';
    return '';
  }
?>
