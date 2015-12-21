<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]) . '/..';
require "$root/init.php";

redirect_not_authed();

$error = array();

if(empty($_GET['name']) && empty($_POST['name'])) {
  header('Location: /');
  exit();
}

$name = $_GET['name'];

if(isset($_POST['name'])) {
  $sql = "DELETE FROM TRICK WHERE name = :name";
  $stmt = $db->prepare($sql);
  $stmt->bindParam(':name', $_POST['name']);   
  $stmt->execute();
  header('Location: /tricks');
  exit();
}

function hidden($field, $value) {
  return "<input type=\"hidden\" name=\"$field\" value=\"$value\">";
}

echo html(title('Homespot - Delete Trick'),
          nav() .
          content(
            h1("Delete Trick - " . $_GET['name']) .
            form('post',
              hidden('name', $_GET['name']) .
              submit('DELETE')
            )));
?>
