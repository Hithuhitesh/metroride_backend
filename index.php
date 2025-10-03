<?php
require_once __DIR__.'/db.php';

$method = $_SERVER['REQUEST_METHOD'];
$path   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$base = '/myproject/api';
$rel  = '/'.trim(preg_replace('#^'.preg_quote($base,'#').'#','',$path),'/');
if ($rel === '/') $rel = '/health';

switch (true) {
  case $rel === '/health':
    json(['ok'=>true,'ts'=>time()]);

  case ($rel === '/auth/login' && $method === 'POST') || ($rel === '/auth/me' && $method === 'GET'):
    require __DIR__.'/auth.php';
    break;

  case $rel === '/bikes' && in_array($method, ['GET','POST'], true):
    require __DIR__.'/bikes.php';
    break;

  case preg_match('#^/bikes/([\\w-]+)$#', $rel, $m) && $method === 'GET':
    $_GET['id'] = $m[1];
    require __DIR__.'/bikes.php';
    break;

  case preg_match('#^/bikes/([\\w-]+)$#', $rel, $m) && in_array($method, ['PUT','PATCH'], true):
    $_GET['id'] = $m[1];
    require __DIR__.'/bikes.php';
    break;

  case preg_match('#^/bikes/([\\w-]+)$#', $rel, $m) && $method === 'DELETE':
    $_GET['id'] = $m[1];
    require __DIR__.'/bikes.php';
    break;

  case preg_match('#^/bikes/([\\w-]+)/bookings$#', $rel, $m) && $method === 'GET':
    $_GET['bike_id'] = $m[1];
    require __DIR__.'/bookings.php';
    break;

  case $rel === '/bookings' && in_array($method, ['GET','POST'], true):
    require __DIR__.'/bookings.php';
    break;

  case preg_match('#^/bookings/([\\w-]+)/cancel$#', $rel, $m) && in_array($method, ['PUT','PATCH'], true):
    $_GET['id'] = $m[1];
    require __DIR__.'/bookings.php';
    break;

  case $rel === '/admin/seed' && $method === 'POST':
    require __DIR__.'/admin.php';
    break;

  default:
    json(['error'=>'Not Found','path'=>$rel],404);
}
?>