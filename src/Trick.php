<?php
include 'TrickRow.php';
include 'trick_fn.php';

class Trick {
  public function __construct($db) {
    $this->db = $db;
  }

  public function current($user_id, $tag_names = array()) {
    $in  = str_repeat('?,', count($tag_names) - 1) . '?';
    $db  = $this->db;
    $statement = $db->prepare("SELECT tn.name, t.stance, t.direction, t.trick_id, TAG.tag_id, TAG.name as tag_name
                                 FROM TRICK as t

                                 LEFT JOIN TRICK_NAME as tn ON tn.trick_name_id = t.trick_name_id
                                 LEFT JOIN TAG ON t.tag_id = TAG.tag_id
                                 WHERE t.user_id = ?
                                   AND `reset` <= ?
                                   AND TAG.name IN ($in)
                                 ORDER BY `name` ASC");
    $statement->bindValue(1, $user_id);
    $statement->bindValue(2, date('Y-m-d', time()));

    foreach ($tag_names as $k => $tag_name)
      $statement->bindValue(($k+3), $tag_name);

    $count = $statement->execute();
    $rows  = $statement->fetchAll();
    return $this->gen_trick_rows($rows);
  }

  private function gen_trick_rows($rows) {
    $trick_rows = array();
    foreach ($rows as $row)
      array_push($trick_rows, new TrickRow($row));
    return $trick_rows;
  }

  public function defer($user_id, $trick_ids) {
    $db = $this->db;
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
      $stmt->bindValue(':user_id', $user_id);
      $stmt->execute();
      $row = $stmt->fetch();

      if(empty($row))
        continue;

      $query->bindValue(':trick_id', $trick_id);
      $query->bindValue(':user_id', $user_id);
      $query->bindValue(':reset', date('Y-m-d', strtotime('+' . $row['interval'] . ' days')));
      $query->bindValue(':interval', $row['interval'] * 2);
      $query->execute();
    }
  }

  public function reset($user_id, $trick_ids) {
    $db = $this->db;
    $query = $db->prepare('UPDATE TRICK
                            SET reset = :reset, interval = :interval
                            WHERE trick_id = :trick_id
                              AND user_id  = :user_id');
    foreach ($trick_ids as $trick_id) {
        $stmt->bindValue(':trick_id', $trick_id);
        $stmt->bindValue(':user_id', $user_id);
        $stmt->bindValue(':reset', date('Y-m-d', strtotime('+1 days')));
        $stmt->bindValue(':interval', 1);
        $query->execute();
    }
  }

  public function by_name($user_id, $name) {
    $statement = $this->db->prepare("SELECT stance, direction, tag_id, tn.trick_name_id
                                 FROM TRICK as t
                                 LEFT JOIN TRICK_NAME as tn ON tn.trick_name_id = t.trick_name_id
                                 WHERE t.user_id = :user_id
                                   AND tn.name = :name");
    $statement->bindValue(':user_id', $user_id);
    $statement->bindValue(':name', $name);

    $count    = $statement->execute();
    $tricks   = $statement->fetchAll();
    return $tricks;
  }

  public function delete_by_name($user_id, $name) {
    $db = $this->db;
    $sql = "SELECT trick_name_id
              FROM TRICK_NAME
              WHERE name = :name
                AND user_id = :user_id
              LIMIT 1";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':user_id', $user_id);
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
  }

  public function names($user_id) {
    $statement = $this->db->prepare('SELECT name
                                  FROM `TRICK_NAME`
                                  WHERE `user_id` = :user_id
                                  ORDER BY `name` ASC');
    $statement->bindValue(":user_id", $user_id);
    $count = $statement->execute();
    $rows = $statement->fetchAll();
    return $this->gen_trick_rows($rows);
  }

  public function create_trick_name($user_id, $name) {
      $db = $this->db;
      $query_trick_name = 'INSERT INTO TRICK_NAME (name, user_id) VALUES (:name, :user_id)';
      $stmt_trick_name = $db -> prepare($query_trick_name);
      $stmt_trick_name->bindValue(':name', $name);
      $stmt_trick_name->bindValue(':user_id', $_SESSION['user_id']);
      $stmt_trick_name -> execute();
      return $db->lastInsertId();
  }

  public function create($user_id, $trick_name_id, $create_tricks) {
    $query  = 'INSERT INTO TRICK (stance, direction, user_id, trick_name_id, reset, tag_id) VALUES ';
    $qPart  = array_fill(0, count($create_tricks), "(?, ?, ?, ?, ?, ?)");
    $query .=  implode(",",$qPart);
    $stmt   = $this -> db -> prepare($query);
    $i      = 1;
    foreach($create_tricks as $create_trick) { //bind the values one by one
        $stmt->bindValue($i++, $create_trick['stance']);
        $stmt->bindValue($i++, $create_trick['direction']);
        $stmt->bindValue($i++, $user_id);
        $stmt->bindValue($i++, $trick_name_id);
        $stmt->bindValue($i++, date('Y-m-d', time()));
        $stmt->bindValue($i++, $create_trick['tag_id']);
    }
    $stmt -> execute();
  }

  public function delete ($user_id, $trick_name_id, $delete_tricks) {
    $stmt   = $this -> db -> prepare('DELETE
                                FROM TRICK
                                WHERE user_id = :user_id
                                  AND trick_name_id = :trick_name_id
                                  AND stance = :stance
                                  AND direction = :direction
                                  AND tag_id = :tag_id');
    foreach($delete_tricks as $delete_trick) {
        $stmt->bindValue(':stance', $delete_trick['stance']);
        $stmt->bindValue(':direction', $delete_trick['direction']);
        $stmt->bindValue(':user_id', $user_id);
        $stmt->bindValue(':trick_name_id', $trick_name_id);
        $stmt->bindValue(':tag_id', $delete_trick['tag_id']);
        $stmt -> execute();
    }
  }

  public function update_name($user_id, $old_name, $name) {
    $sql = "UPDATE TRICK_NAME SET name = :name
              WHERE name = :old_name
                AND user_id = :user_id";
    $stmt = $this -> db -> prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':old_name', $old_name, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
  }
}
?>

