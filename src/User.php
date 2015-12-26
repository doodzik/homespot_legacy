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
    if($this->is_authed()) {
      return $this->id;
    } else {
      header('Location: /auth/create');
      exit();
    }
  }

  public function auth($token) {
    $row = $this->by_token($token);
    if(isset($row)) {
      $_SESSION['user_id'] = $row['user_id'];
      return true;
    }
    return false;
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

  public function by_email($email) {
    $db = $this->db;
    $statement = $db->prepare('SELECT user_id FROM USER WHERE email = :email LIMIT 1');
    $statement->bindValue(":email", $email);
    $statement->execute();
    $row = $statement->fetchAll();
    return $row;
  }
}

?>
