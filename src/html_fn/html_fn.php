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

  function div ($content) {
    return "<div>$content</div>";
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

  function lable ($value, $class = '') {
      if(strlen($class) > 0)
        $class = "class=\"$class\"";
      return "<lable $class >$value</lable>";
  }

  function input_err ($error, $name) {
    if(isset($error[$name])) {
      $err_val = $error[$name];
      return lable($err_val, 'error');
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

  function checkbox ($name, $checked = true) {
    $checked = ($checked) ? 'checked' : '';
    return "<input type=\"checkbox\" name=\"$name\" $checked/>";
  }

  function text ($name ='', $placeholder = '') {
    return input('text', $name, $placeholder);
  }

  function submit ($value = 'Submit') {
    return "<input type=\"submit\" value=\"$value\">";
  }

  function nav () {
    if(logged_in()) {
      $str = "
              <a href=\"/\">Home</a>
              <a href=\"/tricks/create\">Create Trick</a>
              <a href=\"/\">All Tricks</a>
              <a href=\"/\">Create Tag</a>
              <a href=\"/\">All Tags</a>
              <a href=\"auth/delete/index.php\">Logout</a>
      ";
    } else {
      $str = "<a href=\"/\">Sign In/Up</a>";
    }
    return "<nav>
              $str
              <a href=\"/about\">About</a>
            </nav>";
  }
?>
