<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]) . '/..';
require "$root/init.php";

redirect_not_authed();

$error = array();

if(isset($_POST['name'])) {
  if(strlen(validate_name($_POST['name'])) > 0)
    $error['name'] = validate_name($_POST['name']);

  if(empty($_POST['tag_ids']) || count($_POST['tag_ids']) == 0)
    $error['tag_ids'] = 'no tags were selected';

  if(count($error) == 0) {
    $prefixes = array();
    foreach ($_POST['stance'] as $stance => $value) {
      if($stance != 'normal' && $stance != 'nolli' && $stance != 'switch' && $stance != 'fakie')
        continue;
      foreach ($_POST['direction'] as $direction => $value2) {
        if($direction != 'none' && $direction != 'fs' && $direction != 'bs')
          continue;
        foreach ($_POST['tag_ids'] as $tag_id) {
          array_push($prefixes, array(
            'stance'    => $stance,
            'direction' => $direction,
            'tag_id'    => $tag_id
          ));
        }
      }
    }

    $name = $_POST['name'];
    if(count($error) == 0) {
      $query_trick_name = 'INSERT INTO TRICK_NAME (name, user_id) VALUES (:name, :user_id)';
      $stmt_trick_name = $db -> prepare($query_trick_name);
      $stmt_trick_name->bindValue(':name', $name);
      $stmt_trick_name->bindValue(':user_id', $_SESSION['user_id']);
      $stmt_trick_name -> execute();
      $trick_name_id = $db->lastInsertId();

      $query  = 'INSERT INTO TRICK (stance, direction, user_id, trick_name_id, reset, tag_id) VALUES ';
      $qPart  = array_fill(0, count($prefixes), "(?, ?, ?, ?, ?, ?)");
      $query .=  implode(",",$qPart);
      $stmt   = $db -> prepare($query);
      $i      = 1;
      foreach($prefixes as $prefix) { //bind the values one by one
          $stmt->bindValue($i++, $prefix['stance']);
          $stmt->bindValue($i++, $prefix['direction']);
          $stmt->bindValue($i++, $_SESSION['user_id']);
          $stmt->bindValue($i++, $trick_name_id);
          $stmt->bindValue($i++, date('Y-m-d', time()));
          $stmt->bindValue($i++, $prefix['tag_id']);
      }
      $stmt -> execute();

      header('Location: /');
      exit();
    }
  }
}

$query = 'SELECT name, tag_id
            FROM TAG
            WHERE user_id = :user_id';
$stmt = $db -> prepare($query);
$stmt -> bindValue(':user_id', $_SESSION['user_id']);
$stmt -> execute();
$rows  = $stmt->fetchAll();

$tags = '';

if(count($rows) > 0) {
    foreach ($rows as $tag) {
      $tag_name = $tag['name'];
      $tags .= li(checkbox_array('tag_ids', $tag['tag_id']) .
                     ' -- ' .
                     a($tag_name, "/tag/edit/index.php?name=$tag_name"));
    }
} else {
  $tags   = 'you have no tags';
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
              input_err($error, 'tag_ids') .
              $tags .
              submit()
            )));
?>
