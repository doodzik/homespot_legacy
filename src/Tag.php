<?php
class Tag {
  public function __construct($db, $user_id) {
    $this->db = $db;
    $this->user_id = $user_id;
  }

  public function all() {
    $user_id = $this->user_id;
    $db = $this->db;
    $query = 'SELECT tag_id, name
                FROM TAG
                WHERE user_id = :user_id
                ORDER BY `name` ASC';
    $stmt = $db -> prepare($query);
    $stmt -> bindValue(':user_id', $_SESSION['user_id']);
    $stmt -> execute();
    $rows  = $stmt->fetchAll();
    return $rows;
  }

  public function all_names() {
    $user_id = $this->user_id;
    $rows = $this->all();
    $_rows = array();
    foreach($rows as $tag)
      array_push($_rows, $tag['name']);
    return $_rows;
  }

  public function delete($name) {
    $user_id = $this->user_id;
    $sql = "DELETE
              FROM TAG
              WHERE name = :name
                AND user_id = :user_id";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
  }

  public function update($name, $old_name) {
    $user_id = $this->user_id;
    $sql = "UPDATE TAG SET name = :name
              WHERE name = :old_name
                AND user_id = :user_id";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':old_name', $old_name, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
  }

  public function create($name) {
    $user_id = $this->user_id;
    $query = 'INSERT INTO TAG (name, user_id) VALUES (:name, :user_id)';
    $stmt = $this -> db -> prepare($query);
    $stmt->bindValue(':name', $name);
    $stmt->bindValue(':user_id', $user_id);
    $stmt -> execute();
  }
}
?>
