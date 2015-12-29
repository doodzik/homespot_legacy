<?php
require(__DIR__ . '/../../init.php');

echo html(title('Homespot - About'),
          navigation($user->is_authed()) .
          content(
            h1('About') .
            p('abaut what') .
            a('check out the source code', 'https://github.com/doodzik/homespot')));
?>
