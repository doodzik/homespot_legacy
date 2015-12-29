<?php
class User {
  private $id = NULL;

  public function __construct($db, $session) {
    $this->db = $db;
    if(isset($session['user_id'])) {
      $user = $this->by_id($session['user_id']);
      if(isset($user))
        $this->id = $user['user_id'];
      else
        session_destroy();
    }
  }

  public function is_authed() {
    return isset($this->id);
  }

  public function get_id() {
    if($this->is_authed())
      return $this->id;
    else
      redirect('/auth/create');
  }

  public function authenticate($token) {
    $row = $this->by_token($token);
    if(isset($row))
      $_SESSION['user_id'] = $row['user_id'];
    return isset($_SESSION['user_id']);
  }

  public function by_email($email) {
    $db = $this->db;
    $statement = $db->prepare('SELECT user_id FROM USER WHERE email = :email LIMIT 1');
    $statement->bindValue(":email", $email);
    $statement->execute();
    $row = $statement->fetchAll();
    return $row;
  }

  public function create($uuid, $email) {
    $statement = $this->db->prepare('INSERT INTO USER (token, token_time, email)
                                VALUES (:token, :token_time, :email)');
    $statement->bindValue(":token", $uuid);
    $statement->bindValue(":token_time", date('Y-m-d H:i:s', time()));
    $statement->bindValue(":email", $email);
    $count   = $statement->execute();

    $user_id = $this->db->lastInsertId();
    
    $tag = new Tag($this->db, $user_id);
    $tag->create_default();
    $tag_id = $tag->by_name('flat');
    $trick  = new Trick($this->db, $user_id);
    $trick->create_default($tag_id);
  }

  public function update_token($uuid, $email) {
    $statement = $this->db->prepare('UPDATE `USER`
                                SET `token` = :token,
                                    `token_time` = :token_time
                                WHERE `email` = :email');
    $statement->bindValue(":token", $uuid);
    $statement->bindValue(":token_time", date('Y-m-d H:i:s', time()));
    $statement->bindValue(":email", $email);
    $count = $statement->execute();
  }

  public function set_token($uuid, $email) {
    $row = $this->by_email($email);
    if(count($row) == 0)
      $this->create($uuid, $email);
    else
      $this->update_token($uuid, $email);
    return $uuid;
  }

  private function by_token($token) {
    $db    = $this->db;
    $stmt  = $db->prepare("SELECT user_id
                              FROM USER
                              WHERE token=:token
                                AND token_time < :token_time
                              LIMIT 1");
    $stmt->bindValue(":token", $token);
    $stmt->bindValue(':token_time', date('Y-m-d H:i:s', strtotime('+1 hour')));
    $stmt->execute();
    $row = $stmt->fetch();
    return $row;
  }

  private function by_id($id) {
    $db    = $this->db;
    $stmt  = $db->prepare("SELECT user_id
                              FROM USER
                              WHERE user_id=:user_id
                              LIMIT 1");
    $stmt->bindValue(':user_id', $id);
    $stmt->execute();
    $row = $stmt->fetch();
    return $row;
  }
}
?>
