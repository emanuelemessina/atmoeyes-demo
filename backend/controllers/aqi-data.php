<?php 

$id = $parameters[0];

echo json_encode( 
    
    QuickQuery::select(
        'data', 
        '*', 
        is_null($id) ? null : ["id"=>$id]
    ) 

);