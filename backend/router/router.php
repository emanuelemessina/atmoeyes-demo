<?php

session_start();

// HTTP request method check
function get($route, $path_to_include)
{
  if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    route($route, $path_to_include);
  }
}
function post($route, $path_to_include)
{
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    route($route, $path_to_include);
  }
}
function put($route, $path_to_include)
{
  if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    route($route, $path_to_include);
  }
}
function patch($route, $path_to_include)
{
  if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
    route($route, $path_to_include);
  }
}
function delete($route, $path_to_include)
{
  if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    route($route, $path_to_include);
  }
}
function any($route, $path_to_include)
{
  route($route, $path_to_include);
}

/**
 * @return string the overlapping substring between two strings
 */
function str_find_overlap($str1, $str2)
{

  $return = array();

  $sl1 = strlen($str1);
  $sl2 = strlen($str2);

  $max = $sl1 > $sl2 ? $sl2 : $sl1;

  $i = 1;
  while ($i <= $max) {
    $s1 = substr($str1, -$i);
    $s2 = substr($str2, 0, $i);
    if ($s1 == $s2) {
      $return[] = $s1;
    }
    $i++;
  }
  if (!empty($return)) {
    return $return;
  }
  return false;
}

// Route matching
function route($route, $path_to_include)
{

  $ROOT = ".";

  // localize request uri to cwd (backend folder)
  $request_uri = str_replace(str_find_overlap(getcwd(), $_SERVER['REQUEST_URI'])[0], "", $_SERVER['REQUEST_URI']);

  $request_url = filter_var($request_uri, FILTER_SANITIZE_URL);
  $request_url = rtrim($request_url, '/');
  $request_url = strtok($request_url, '?');

  $request_url_parts = explode('/', $request_url);
  array_shift($request_url_parts);

  $route_parts = explode('/', $route);
  array_shift($route_parts);

  // found root route
  if ($route_parts[0] == '' && count($request_url_parts) == 0) {
    include_once("$ROOT/controllers/$path_to_include");
    exit();
  }

  // wrong route (different num of parts)
  if (count($route_parts) != count($request_url_parts)) {
    return;
  }

  $parameters = [];
  for ($__i__ = 0; $__i__ < count($route_parts); $__i__++) {
    $route_part = $route_parts[$__i__];
    if (preg_match("/^[$]/", $route_part)) {
      $route_part = ltrim($route_part, '$');
      //echo "$__i__) preg match -> $route_part\n";
      array_push($parameters, $request_url_parts[$__i__]);
      //echo "$__i__) parameters\n";
      //var_dump($parameters);
      //echo "<br>";
      $$route_part = $request_url_parts[$__i__];
      //echo "$__i__) $$route_part\n";
    } else if ($route_parts[$__i__] != $request_url_parts[$__i__]) { // wrong route (different parts)
      return;
    }
  }

  include_once("$ROOT/controllers/$path_to_include");
  exit(); // found
}

function out($text)
{
  echo htmlspecialchars($text);
}

function set_csrf()
{
  if (!isset($_SESSION["csrf"])) {
    $_SESSION["csrf"] = bin2hex(random_bytes(50));
  }
  echo '<input type="hidden" name="csrf" value="' . $_SESSION["csrf"] . '">';
}
function is_csrf_valid()
{
  if (!isset($_SESSION['csrf']) || !isset($_POST['csrf'])) {
    return false;
  }
  if ($_SESSION['csrf'] != $_POST['csrf']) {
    return false;
  }
  return true;
}


require_once("routes.php"); // 🚃 must be last