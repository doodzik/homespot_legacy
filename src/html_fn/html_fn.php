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

  function ul ($content) {
    return "<ul>$content</ul>";
  }

  function li ($content) {
    return "<li>$content</li>";
  }

  function h1 ($content) {
    return "<h1>$content</h1>";
  }

  function a ($content, $link = '#') {
    return "<a href=\"$link\">$content</a>";
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

  // TODO rename nav
  function _nav ($content) {
    return "<nav>$content</nav>";
  }

  function nav () {
    if(logged_in()) {
      $str = a('Home', '/') .
             a('Create Trick', '/trick/create') .
             a('All Tricks', '/tricks') .
             a('Create Tag', '/tag/create') .
             a('All Tags', '/tags') .
             a('Logout', 'auth/delete');
    } else {
      $str = a('Sign In/Up', '/auth/create');
    }
    return _nav($str . a('About', '/about'));
  }
?>
