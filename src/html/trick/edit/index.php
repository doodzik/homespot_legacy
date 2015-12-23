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
  $sql = "UPDATE TRICK_NAME SET name = :name
            WHERE name = :old_name 
              AND user_id = :user_id";
  $stmt = $db->prepare($sql);
  $stmt->bindParam(':name', $_POST['name'], PDO::PARAM_STR);
  $stmt->bindParam(':old_name', $_POST['old_name'], PDO::PARAM_STR);
  $stmt->bindParam(':user_id', $_SESSION['user_id']);
  $stmt->execute();

  header('Location: /tricks');
  exit();
}

$name = $_GET['name'];

echo html(title('Homespot - Edit Trick'),
          nav() .
          content(
            h1("Edit Trick") .
            p("you wanted to edit: $name") .
            p('no editing of the prefix yet') .
            form('post',
              hidden('old_name', $name).
              text('name').
              submit()) .
            a('delete trick', "/trick/delete/index.php?name=$name")));
?>
