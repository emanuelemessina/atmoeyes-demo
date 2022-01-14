<?php

header('Content-Type: application/json; charset=utf-8');

// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
    // you want to allow, and if so:
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
}

// Place routes here ⬇️

get('/data/aqi', 'aqi-data.php');

post('/data/aqi/send', 'send-aqi-data.php');


any('/test', 'test.php'); // testing route

// 🛑 last route to match is always 404
http_response_code(404);
die(json_encode(["msg" => "404 - Not Found"]));
