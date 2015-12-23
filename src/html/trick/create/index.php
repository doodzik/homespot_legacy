<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]) . '/..';
require "$root/init.php";

redirect_not_authed();

$error = array();

if(isset($_POST['name'])) {
  $prefixes = array();
  foreach ($_POST['stance'] as $stance => $value) {
    if($stance != 'normal' && $stance != 'nolli' && $stance != 'switch' && $stance != 'fakie')
      continue;
    foreach ($_POST['direction'] as $direction => $value2) {
      if($direction != 'none' && $direction != 'fs' && $direction != 'bs')
        continue;
      array_push($prefixes, array(
        'stance'    => $stance,
        'direction' => $direction
      ));
    }
  }

  $name = $_POST['name'];
  $name_err = validate_name($name);
  if(strlen($name_err) > 0)
    $error['name'] = $name_err;
  if(count($error) == 0) {
    $query_trick_name = 'INSERT INTO TRICK_NAME (name, user_id) VALUES (:name, :user_id)';
    $stmt_trick_name = $db -> prepare($query_trick_name);
    $stmt_trick_name->bindValue(':name', $name);
    $stmt_trick_name->bindValue(':user_id', $_SESSION['user_id']);
    $stmt_trick_name -> execute();
    $trick_name_id = $db->lastInsertId();

    $query  = 'INSERT INTO TRICK (stance, direction, user_id, trick_name_id, reset) VALUES ';
    $qPart  = array_fill(0, count($prefixes), "(?, ?, ?, ?, ?)");
    $query .=  implode(",",$qPart);
    $stmt   = $db -> prepare($query);
    $i      = 1;
    foreach($prefixes as $prefix) { //bind the values one by one
        $stmt->bindValue($i++, $prefix['stance']);
        $stmt->bindValue($i++, $prefix['direction']);
        $stmt->bindValue($i++, $_SESSION['user_id']);
        $stmt->bindValue($i++, $trick_name_id);
        $stmt->bindValue($i++, date('Y-m-d', time()));
    }
    $stmt -> execute();

    header('Location: /');
    exit();
  }
}

echo html(title('Homespot - Create Trick'),
          nav() .
          content(
            h1("Create Trick") .
            form('post',
              lable('stance:') .
              div(lable('normal:') . checkbox('stance[normal]') .
                  lable('nolli:')  . checkbox('stance[nolli]') .
                  lable('switch:') . checkbox('stance[switch]') .
                  lable('fakie:')  . checkbox('stance[fakie]')) .
              lable('direction:') .
              div(lable('none:') . checkbox('direction[none]') .
                  lable('fs:')   . checkbox('direction[fs]', false) .
                  lable('bs:')   . checkbox('direction[bs]', false)) .
              input_err($error, 'name') .
              input('text', 'name') .
              div(lable('tags:')) .
              submit()
            )));
?>
