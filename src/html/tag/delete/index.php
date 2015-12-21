<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]) . '/..';
require "$root/init.php";

redirect_not_authed();

$error = array();

if(empty($_GET['name']) && empty($_POST['name'])) {
  header('Location: /tags');
  exit();
}

if(isset($_POST['name'])) {
  $sql = "DELETE FROM TAG WHERE name = :name";
  $stmt = $db->prepare($sql);
  $stmt->bindParam(':name', $_POST['name']);   
  $stmt->execute();
  header('Location: /tags');
  exit();
}

$name = $_GET['name'];

echo html(title('Homespot - Delete Tag'),
          nav() .
          content(
            h1("Delete Tag - " . $_GET['name']) .
            form('post',
              hidden('name', $_GET['name']) .
              submit('DELETE')
            )));
?>

