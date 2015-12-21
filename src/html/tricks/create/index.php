<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]) . '/..';
require "$root/init.php";

$error = array();

function validate_name($name) {
  if(strlen($name >= 150))
    return 'name too long';
  return '';
}

if(isset($_POST['name'])) {
  $prefixes = array();
  foreach ($_POST['stance'] as $key => $value) {
    if($key != 'normal' && $key != 'nolli' && $key != 'switch' && $key != 'fakie')
      continue;
    $value = ($key == 'normal') ? '' : $key . ' ';
    if(isset($_POST['direction']['none']))
      array_push($prefixes, trim($value));
    if(isset($_POST['direction']['fs']))
      array_push($prefixes, $value . 'fs');
    if(isset($_POST['direction']['bs']))
      array_push($prefixes, $value . 'bs');
  }

  $name = $_POST['name'];
  $name_err = validate_name($name);
  if(strlen($name_err) > 0) {
    $error['name'] = $name_err;
    if(count($error) == 0) {
      $sql = 'INSERT INTO TRICKS (prefix, name, reset, user_id) VALUES ';
      $insertQuery = array();
      $insertData = array();
      foreach ($prefixes as $prefix) {
          $insertQuery[] = '(?, ?, ?, ?)';
          $insertData[] = $prefix;
          $insertData[] = $name;
          $insertData[] = date('Y-m-d H:i:s', time());
          $insertData[] = $_SESSION['user_id'];
      }

      if (!empty($insertQuery)) {
          $sql .= implode(', ', $insertQuery);
          $stmt = $db->prepare($sql);
          $stmt->execute($insertData);
      }

      header('Location: /index.php');
      exit();
    }
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
              submit()
            )));
?>
