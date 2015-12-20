<?php
  require 'validators.php';
  require 'util.php';

  function html($head, $body) {
    return "<!DOCTYPE html>
            <html>
              <head>
                $head
              </head>
              <body>
                $body
              </body>
            </html>";
  }

  function title ($name) {
    return "<title>$name</title>";
  }

  function content ($content) {
    return "<div id=\"content\">$content</div>";
  }

  function p ($content) {
    return "<p>$content</p>";
  }

  function h1 ($content) {
    return "<h1>$content</h1>";
  }

  function form ($request, $content) {
    $backtrace = debug_backtrace();
    $path = str_replace($_SERVER['DOCUMENT_ROOT'], "", $backtrace[0]['file']);
    return "<form action=\"$path\" method=\"$request\">$content</form>";
  }

  function input_err ($error, $name) {
    if(isset($error[$name])) {
      $err_val = $error[$name];
      return "<lable class=\"error\">$err_val</lable>";
    }
    return '';
  }

  function input ($type, $name = '', $placeholder = '') {
    if(strlen($name) == 0)
      $name = $type;
    if(strlen($placeholder) == 0)
      $placeholder = $name;

    $value = get_request($name);
    $input = "<input type=\"$type\" name=\"$name\" placeholder=\"$placeholder\" value=\"$value\"/>";
    return $input;
  }

  function submit ($value = 'Submit') {
    return "<input type=\"submit\" value=\"$value\">";
  }

  function nav () {
    return "<nav>
            <a href=\"#\">hello world</a>
          </nav>";
  }
?>
