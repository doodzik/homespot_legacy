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

function redirect($location = '/') {
    header('Location: ' . $location);
    exit();
}

function redirect_authed($user) {
  if($user->is_authed())
    redirect();
}
?>
