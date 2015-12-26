<?php
function gen_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}

function send_login_mail ($to, $token) {
  $message = "In order to login into Homespot you need to click on this link: http://homespot.dudzik.co/auth/confirm.php?token=$token
 you have to login within an hour!";
  $subject = 'Loging in';
  $headers = 'From: noreply@dudzik.co' . "\r\n" .
    'Reply-To: webmaster@dudzik.co' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

  mail($to, $subject, $message, $headers);
}

function create_user($db, $uuid, $email) {
  $statement = $db->prepare('INSERT INTO USER (token, token_time, email)
                              VALUES (:token, :token_time, :email)');
  $statement->bindValue(":token", $uuid);
  $statement->bindValue(":token_time", date('Y-m-d H:i:s', time()));
  $statement->bindValue(":email", $email);
  $count   = $statement->execute();

  $user_id = $db->lastInsertId();

  $statement = $db->prepare("INSERT INTO TAG (user_id, name)
                              VALUES (:user_id, 'curb'),
                                     (:user_id, 'flat'),
                                     (:user_id, 'manual table'),
                                     (:user_id, 'bank'),
                                     (:user_id, 'rail')");
  $statement->bindValue(":user_id", $user_id);
  $statement->execute();

  return $count;
}

function update_token($db, $uuid, $email) {
  $statement = $db->prepare('UPDATE `USER`
                              SET `token` = :token,
                                  `token_time` = :token_time
                              WHERE `email` = :email');
  $statement->bindValue(":token", $uuid);
  $statement->bindValue(":token_time", date('Y-m-d H:i:s', time()));
  $statement->bindValue(":email", $email);
  $count = $statement->execute();

  return $count;
}

function logged_in() {
  return isset($_SESSION['user_id']);
}

function redirect_authed() {
  if(logged_in()) {
    header('Location: /');
    exit();
  }
}

function redirect_not_authed() {
  if(!logged_in()) {
    header('Location: /auth/create/index.php');
    exit();
  }
}
?>
