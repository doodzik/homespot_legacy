<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]) . '/..';
require "$root/init.php";

if(isset($_GET['token'])) {
  $token = $_GET['token'];
  $stmt  =  $db->prepare("SELECT user_id
                            FROM USER
                            WHERE token=?
                            LIMIT 1");
  $stmt->bind_param("s", $token);
  $stmt->execute();
  $row = $stmt->fetch();
  if(isset($row))
    $_SESSION['user_id'] = $row['user_id'];
  header('Location: /index.php');
  exit();
}

echo html(title('Homespot - Sign In/Up Confirmation'),
          nav() .
          content(
            h1("Sign In/Up Confirmation") .
            p("Check your email account and login by clicking the link that was sent to you")
            ));
?>
