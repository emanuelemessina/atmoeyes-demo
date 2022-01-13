<?php

function echo_log($title, $msg){
    echo json_encode([$title => $msg]);
    echo "<br>";
}

require_once('db/db.php'); 
require_once('router/router.php'); // must be last
