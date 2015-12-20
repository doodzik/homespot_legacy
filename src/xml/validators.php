<?php 
  function validate_email($email) {
    $value = '';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
      $value = 'email is invalid';
    return $value;
  }
?>
