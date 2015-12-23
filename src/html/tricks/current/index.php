<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]) . '/..';
require "$root/init.php";

redirect_not_authed();

function trick_name($stance, $direction, $name) {
  if($direction == 'none')
    $direction = '';
  if($stance == 'normal')
    $stance = '';
  return trim($stance . ' ' . $direction . ' ' . $name);
}


$err = '';

if(isset($_POST['submit']) && $_POST['submit'] == 'good') {
  if(isset($_POST['trick_ids'])) {
    $query = $db->prepare('UPDATE TRICK
                            SET reset = :reset, interval = :interval
                            WHERE trick_id = :trick_id
                              AND user_id  = :user_id');
    foreach ($trick_ids as $trick_id) {
      $stmt = $db->prepare('SELECT interval
                              FROM TRICK
                              WHERE user_id  = :user_id
                                AND trick_id = :trick_id
                              LIMIT 1');
      $stmt->bindValue(':trick_id', $trick_id);
      $stmt->bindValue(':user_id', $_SESSION['user_id']);
      $stmt->execute();
      $row = $stmt->fetch();

      if(empty($row))
        continue;

      $query->bindValue(':trick_id', $trick_id);
      $query->bindValue(':user_id', $_SESSION['user_id']);
      $query->bindValue(':reset', date('Y-m-d', strtotime('+' . $row['interval'] . 'days')));
      $query->bindValue(':interval', $row['interval'] * 2);
      $query->execute();
    }
    header('Location: /');
    exit();
  } else {
    $err = 'select at least one track';
  }
}

if(isset($_POST['submit']) && $_POST['submit'] == 'bad') {
  if(isset($_POST['track_ids'])) {
    $query = $db->prepare('UPDATE TRICK
                            SET reset = :reset, interval = :interval
                            WHERE trick_id = :trick_id
                              AND user_id  = :user_id');
    foreach ($trick_ids as $trick_id) {
        $stmt->bindValue(':trick_id', $trick_id);
        $stmt->bindValue(':user_id', $_SESSION['user_id']);
        $stmt->bindValue(':reset', date('Y-m-d', time()));
        $stmt->bindValue(':interval', 1);
        $query->execute();
    }
    header('Location: /');
    exit();
  } else {
    $err = 'select at least one track';
  }
}

if(isset($_GET['tag_names'])) {
  $filters  = join(', ', $_GET['tag_names']);
} else {
  $filters  = 'all';
}

$statement = $db->prepare('SELECT tn.name, t.stance, t.direction, t.trick_id
                             FROM TRICK as t
                             LEFT JOIN TRICK_NAME as tn ON tn.trick_name_id = t.trick_name_id
                             WHERE t.user_id = :user_id
                               AND `reset` <= :now
                             ORDER BY `name` ASC');
$statement->bindValue(":user_id", $_SESSION['user_id']);
$statement->bindValue(":now", date('Y-m-d', time()));

$count = $statement->execute();
$rows  = $statement->fetchAll();

if(count($rows) > 0) {
  $content = '';
  foreach ($rows as $trick) {
    $trick_name = trick_name($trick['stance'], $trick['direction'], $trick['name']);
    $content .= li(checkbox_array('trick_ids', $trick['trick_id']) .
                  ' -- ' .
                  $trick_name);
  }
} else {
  $content = 'you have no tricks';
}

echo html(title('Homespot'),
          nav() .
          content(
            h1("Current Tricks") .
            $err .
            a('change filters', '/tricks/current/filter') .
            br() .
            p("current filters: $filters") .
            form('post',
              submit('good') .
              submit('bad') .
              ul($content))));
?>
