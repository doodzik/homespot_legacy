<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]) . '/..';
require "$root/init.php";

echo html(title('Homespot'),
          nav() .
          content(
            h1("Current Tricks") .
            'welcome'
            ));
?>

