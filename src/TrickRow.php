<?php
class TrickRow {
  public function __construct($trick) {
    $this->name      = (isset($trick['name'])) ? $trick['name'] : NULL;
    $this->stance    = (isset($trick['stance'])) ? $trick['stance'] : NULL;
    $this->direction = (isset($trick['direction'])) ? $trick['direction'] : NULL;
    $this->tag_name  = (isset($trick['tag_name'])) ? $trick['tag_name'] : NULL;
    $this->id        = (isset($trick['trick_id'])) ? $trick['trick_id'] : NULL;
  }

  public function get_id() {
    return $this->id;
  }

  public function get_full_name() {
    $direction = ($this->direction == 'none') ? '' : $this->direction;
    $stance = ($this->stance == 'normal') ? '' : $this->stance;
    return trim($stance . ' ' . $direction . ' ' . $this->get_link() . ': ' . $this->tag_name);
  }

  public function get_link() {
    return a($this->name, "/trick/edit/index.php?name=" . $this->name);
  }

  public function __toString() {
    return li(checkbox_array('trick_ids', $this->get_id()) .
                  ' --- ' .
                  $this->get_full_name());
  }
}
?>
