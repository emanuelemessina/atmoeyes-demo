<?php

$input_schema = [
    "id" => 'int',
    "value" => "int"
];

$qq = new QuickQuery('json-input', $input_schema);
$validated_data = $qq->getValidatedData();
$response = $qq->update(
    'data', // table
    ['value'], // fields to update
    [ // where clause
        'id' => $validated_data['id'] 
    ]);

echo json_encode($response);