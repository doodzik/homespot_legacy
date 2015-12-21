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
  $sql = "UPDATE TAG SET name = :name
            WHERE name = :old_name";
  $stmt = $db->prepare($sql);                                  
  $stmt->bindParam(':name', $_POST['name'], PDO::PARAM_STR);       
  $stmt->bindParam(':old_name', $_POST['old_name'], PDO::PARAM_STR);    
  $stmt->execute(); 

  header('Location: /tags');
  exit();
}

$name = $_GET['name'];

echo html(title('Homespot - Edit tags'),
          nav() .
          content(
            h1("Edit Tags") .
            form('post',
              hidden('old_name', $name).
              text('name').
              submit()) .
            a('delete tag', "/tag/delete/index.php?name=$name")));
?>

