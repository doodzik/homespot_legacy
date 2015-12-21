<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]) . '/..';
require "$root/init.php";

redirect_not_authed();

$error = array();

$name = $_GET['name'];

echo html(title('Homespot - Edit Trick'),
          nav() .
          content(
            h1("Edit Trick") .
            p("you wanted to edit: $name") .
            p('no editing yet') . 
            a('delete trick', "/trick/delete/index.php?name=$name")));
?>
