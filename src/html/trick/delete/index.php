<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]) . '/..';
require "$root/init.php";

redirect_not_authed();

$error = array();

if(empty($_GET['name']) && empty($_POST['name'])) {
  header('Location: /');
  exit();
}

if(isset($_POST['name'])) {
  $sql = "SELECT trick_name_id
            FROM TRICK_NAME
            WHERE name = :name
              AND user_id = :user_id
            LIMIT 1";
  $stmt = $db->prepare($sql);
  $stmt->bindParam(':name', $_POST['name']);
  $stmt->bindParam(':user_id', $_SESSION['user_id']);
  $stmt->execute();
  $row = $stmt->fetch();

  if(isset($row)) {
    $sql = "DELETE
              FROM TRICK_NAME
              WHERE trick_name_id = :trick_name_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':trick_name_id', $row['trick_name_id']);
    $stmt->execute();

    $sql = "DELETE
              FROM TRICK
              WHERE trick_name_id = :trick_name_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':trick_name_id', $row['trick_name_id']);
    $stmt->execute();
  }
  header('Location: /tricks');
  exit();
}

$name = $_GET['name'];

echo html(title('Homespot - Delete Trick'),
          nav() .
          content(
            h1("Delete Trick - " . $_GET['name']) .
            form('post',
              hidden('name', $_GET['name']) .
              submit('DELETE')
            )));
?>
