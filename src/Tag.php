<?php
class Tag {
  public function __construct($db) {
    $this->db = $db;
  }

  public function all($user_id) {
    $db = $this->db;
    $query = 'SELECT tag_id, name
                FROM TAG
                WHERE user_id = :user_id';
    $stmt = $db -> prepare($query);
    $stmt -> bindValue(':user_id', $_SESSION['user_id']);
    $stmt -> execute();
    $rows  = $stmt->fetchAll();
    return $rows;
  }

  public function all_names($user_id) {
    $rows = $this->all($user_id);
    $_rows = array();
    foreach($rows as $tag)
      array_push($_rows, $tag['name']);
    return $_rows;
  }
}
?>
