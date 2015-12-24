<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]) . '/..';
require "$root/init.php";

redirect_not_authed();

$error = array();

if(empty($_GET['name']) && empty($_POST['name'])) {
  header('Location: /');
  exit();
}

$name = (isset($_GET['name'])) ? $_GET['name'] : $_POST['name'];


$statement = $db->prepare("SELECT stance, direction, tag_id, tn.trick_name_id
                             FROM TRICK as t
                             LEFT JOIN TRICK_NAME as tn ON tn.trick_name_id = t.trick_name_id
                             WHERE t.user_id = :user_id
                               AND tn.name = :name");
$statement->bindValue(':user_id', $_SESSION['user_id']);
$statement->bindValue(':name', $name);

$count    = $statement->execute();
$tricks   = $statement->fetchAll();

$tricks_old = array();
$tags_old = array();
$stances_old = array();
$directions_old = array();
$trick_name_id = $tricks[0]['trick_name_id'];
foreach($tricks as $trick) {
  array_push($tricks_old, array(
    'stance'    => $trick['stance'],
    'direction' => $trick['direction'],
    'tag_id'    => $trick['tag_id']
  ));
  array_push($tags_old, $trick['tag_id']);
  array_push($stances_old, $trick['stance']);
  array_push($directions_old, $trick['direction']);
}
$tags_old = array_unique($tags_old);
$stances_old = array_unique($stances_old);
$directions_old = array_unique($directions_old);

$query = 'SELECT tag_id, name
            FROM TAG
            WHERE user_id = :user_id';
$stmt = $db -> prepare($query);
$stmt -> bindValue(':user_id', $_SESSION['user_id']);
$stmt -> execute();
$tags_all  = $stmt->fetchAll();

$tags_echo = '';
if(count($tags_all) > 0) {
    foreach ($tags_all as $tag) {
      $tag_name = $tag['name'];
      $tags_echo .= li(checkbox_array('tag_ids', $tag['tag_id'], in_array($tag['tag_id'], $tags_old)) .
                     ' --- ' .
                     a($tag_name, "/tag/edit/index.php?name=$tag_name"));
    }
} else {
  header('Location: /tag/create/index.php?no_tags=1');
  exit();
}

if(isset($_POST['name'])) {
  if(strlen(validate_name($_POST['name'])) > 0)
    $error['name'] = validate_name($_POST['name']);
  if(isset($_POST['tag_ids']) && count($_POST['tag_ids']) == 0)
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

    function compare_prefixes($as, $bs) {
      $rest = array();
      foreach ($as as $key => $a) {
        $i = 0;
        $break = false;
        foreach ($bs as $key => $b) {
          if ($a['stance'] == $b['stance'] && $a['direction'] == $b['direction'] && $a['tag_id'] == $b['tag_id'])
            $break = true;
          $i++;
          if(count($bs) == $i && !$break)
            array_push($rest, array('stance' => $a['stance'], 'direction' => $a['direction'], 'tag_id' => $a['tag_id']));
        }
      }
      return $rest;
    }

    $delete_tricks = compare_prefixes($tricks_old, $prefixes);
    $create_tricks = compare_prefixes($prefixes, $tricks_old);

    $query  = 'INSERT INTO TRICK (stance, direction, user_id, trick_name_id, reset, tag_id) VALUES ';
    $qPart  = array_fill(0, count($create_tricks), "(?, ?, ?, ?, ?, ?)");
    $query .=  implode(",",$qPart);
    $stmt   = $db -> prepare($query);
    $i      = 1;
    foreach($create_tricks as $create_trick) { //bind the values one by one
        $stmt->bindValue($i++, $create_trick['stance']);
        $stmt->bindValue($i++, $create_trick['direction']);
        $stmt->bindValue($i++, $_SESSION['user_id']);
        $stmt->bindValue($i++, $trick_name_id);
        $stmt->bindValue($i++, date('Y-m-d', time()));
        $stmt->bindValue($i++, $create_trick['tag_id']);
    }
    $stmt -> execute();

    $stmt   = $db -> prepare('DELETE
                                FROM TRICK
                                WHERE user_id = :user_id
                                  AND trick_name_id = :trick_name_id
                                  AND stance = :stance
                                  AND direction = :direction
                                  AND tag_id = :tag_id');
    foreach($delete_tricks as $delete_trick) {
        $stmt->bindValue(':stance', $delete_trick['stance']);
        $stmt->bindValue(':direction', $delete_trick['direction']);
        $stmt->bindValue(':user_id', $_SESSION['user_id']);
        $stmt->bindValue(':trick_name_id', $trick_name_id);
        $stmt->bindValue(':tag_id', $delete_trick['tag_id']);
        $stmt -> execute();
    }

    $statement = $db->prepare("SELECT stance, direction, tag_id
                                 FROM TRICK as t
                                 LEFT JOIN TRICK_NAME as tn ON tn.trick_name_id = t.trick_name_id
                                 WHERE t.user_id = :user_id
                                   AND tn.name = :name");
    $statement->bindValue(':user_id', $_SESSION['user_id']);
    $statement->bindValue(':name', $_POST['name']);

    $count = $statement->execute();
    $rows  = $statement->fetchAll();

    $prefixes_old = array();
    foreach ($rows as $row) {
      array_push($prefixes_old, array(
        'stance'    => $row['stance'],
        'direction' => $row['direction'],
        'tag_id'    => $row['tag_id']
      ));
    }


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
}

echo html(title('Homespot - Edit Trick'),
          nav() .
          content(
            h1("Edit Trick") .
            input_err($error, 'name') .
            input_err($error, 'tag_ids') .
            form('post',
              lable('stance:') .
              div(lable('normal:') . checkbox('stance[normal]', in_array('normal', $stances_old)) .
                  lable('nolli:')  . checkbox('stance[nolli]', in_array('nolli', $stances_old)) .
                  lable('switch:') . checkbox('stance[switch]', in_array('switch', $stances_old)) .
                  lable('fakie:')  . checkbox('stance[fakie]', in_array('fakie', $stances_old))) .
              lable('direction:') .
              div(lable('none:') . checkbox('direction[none]', in_array('none', $directions_old)) .
                  lable('fs:')   . checkbox('direction[fs]', in_array('fs', $directions_old)) .
                  lable('bs:')   . checkbox('direction[bs]', in_array('bs', $directions_old))) .
              input_err($error, 'name') .
              hidden('old_name', $name).
              text('name').
              br() .
              br() .
              $tags_echo .
              br() .
              submit()) .
              br() .
            a('delete trick', "/trick/delete/index.php?name=$name")));
?>
