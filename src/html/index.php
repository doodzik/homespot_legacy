<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require "$root/tricks/current/index.php";

redirect_not_authed();

?>
