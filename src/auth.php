<?php
function logged_in() {
  return isset($_SESSION['user_id']);
}

if(logged_in()){
    $stmt  =  $db->prepare("SELECT user_id, token, token_time
                              FROM USER
                              WHERE user_id=?
                              LIMIT 1");
    $stmt->bind_param("s", $_SESSION['user_id']);
    $stmt->execute();
    $row = $stmt->fetch();
    if(isset($row))
      $user = $row;
    else
      session_destroy();
}
?>
