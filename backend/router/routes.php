<?php

header('Content-Type: application/json; charset=utf-8');

// Place routes here ⬇️

get('/data/aqi', 'aqi-data.php');

post('/data/aqi/send', 'send-aqi-data.php');


any('/test', 'test.php'); // testing route

// 🛑 last route to match is always 404
http_response_code(404);
die( json_encode([ "msg" => "404 - Not Found"]) );
