<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]) . '/..';
require "$root/init.php";

redirect_not_authed(); 

$error = array();

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
  if(strlen($name_err) > 0)
    $error['name'] = $name_err;
  if(count($error) == 0) {
    $query = 'INSERT INTO TRICK (prefix, name, reset, user_id) VALUES ';
    $insertData = array();
    $qPart = array_fill(0, count($prefixes), "(?, ?, ?, ?)");
    $query .=  implode(",",$qPart);
    $stmt = $db -> prepare($query);
    $i = 1;
    foreach($prefixes as $prefix) { //bind the values one by one
        $stmt->bindValue($i++, $prefix);
        $stmt->bindValue($i++, $name);
        $stmt->bindValue($i++, date('Y-m-d H:i:s', time()));
        $stmt->bindValue($i++, $_SESSION['user_id']);
    }
    $stmt -> execute();

    header('Location: /');
    exit();
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
