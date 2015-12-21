<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]) . '/..';
require "$root/init.php";

echo html(title('Homespot - Delete Trick'),
          nav() .
          content(
            h1('About') .
            p('abaut what') .
            a('check out the source code', 'https://github.com/doodzik/homespot')));
?>
