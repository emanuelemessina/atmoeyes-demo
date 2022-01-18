<?php

require 'Medoo.php';

use Medoo\Medoo;

$database = new Medoo([
    // [required]
    'type' => 'mysql',
    'host' => 'localhost',
    'database' => 'my_emanuelemessina',
    'username' => 'emanuelemessina',
    'password' => '',

    // [optional] Table prefix, all table names will be prefixed as PREFIX_table.
    'prefix' => 'atmoeyes_',

    // [optional] Enable logging, it is disabled by default for better performance.
    'logging' => false,
    'error' => PDO::ERRMODE_SILENT
]);

class QuickQuery
{
    private $validated_data;

    public function getValidatedData(){
        return $this->validated_data;
    }

    function getValidationFilter($type)
    {
        switch ($type) {
            case "int":
                return FILTER_VALIDATE_INT;
            case "float":
                return FILTER_VALIDATE_FLOAT;
        }
    }

    /**
     * Validates input data array based on given input schema
     * @param array $input_schema column-type pair array
     * @param array $input_data
     * @return array column-value validated data array (otherwise dies on error)
     */
    function validateData($input_schema, $input_data)
    {
        $data = [];

        foreach ($input_schema as $key => $type) {

            $result =  filter_var($input_data[$key], $this->getValidationFilter($type));

            if ($result || ($type == "int" && $result === 0) ) {
                $data[$key] = $result; // update data array with filtered value
            } else {
                http_response_code(400);
                $response = ["errorInfo" => "Wrong input variable of type $type: $key"];
                die(json_encode($response));
            }
        }
        
        return $data;
    }

    /**
     * Validates input based on input schema upon construction
     * @param string $input_from one of "form-data" , "json-input"
     */
    public function __construct(String $input_from, array $input_schema)
    {

        /*
        if(empty($input_from) and empty($input_schema))
            return; // instantiated for retrieving data, not pushing
        */

        $input_data = [];

        switch ($input_from) {
            case 'form-data':
                $input_data = $_POST;
                break;
            case 'json-input':
                $input_data = json_decode(file_get_contents('php://input'), true);

                if (is_null($input_data)) {
                    http_response_code(400);
                    $response = ["errorInfo" => "Invalid input JSON"];
                    die(json_encode($response));
                }
                break;
            default:
                $input_data = $_POST;
                break;
        }

        $this->validated_data = $this->validateData($input_schema, $input_data);
    }

    /**
     * Packs database write operation result response
     * 
     * @param PDOStatement $operation
     * @param array $data column-value array response data
     * @return array[] result of the operation
     */
    private function pack_write_operation($operation, $data)
    {
        if ($GLOBALS["database"]->error) {
            http_response_code(500);
        }

        $response = [
            "data" => $data,
            "rowCount" => $operation->rowCount(),
            "errorCode" => $operation->errorCode(),
            "errorInfo" => $operation->errorInfo()
        ];

        return $response;
    }

    /**
     * Push data to table as it is
     * @param string $table
     */
    public function push($table)
    {
        $insert = $GLOBALS["database"]->insert($table, $this->validated_data);
        return $this->pack_write_operation($insert, $this->validated_data);
    }

    /**
     * Select from table 
     * 
     * @param string $table
     * @param array[] $data_mapping output data structure or columns to select (use "*" to return all columns)
     * @param array|null $where clauses (leave null to select all)
     * @return array[] response result of operation
     */
    public static function select($table, $data_mapping, $where = null)
    {

        $select = $GLOBALS["database"]->select($table, $data_mapping, $where);

        if ($GLOBALS["database"]->error) {
            http_response_code(500);
            return [ 
                "errorCode" => $GLOBALS["database"]->errorInfo[0],
                "errorInfo" => $GLOBALS["database"]->errorInfo[2]
            ];
        }

        $response = [
            "data" => $select,
        ];

        return $response;
    }

    /**
     * 
     * Update record 
     * 
     * @param string $table
     * @param string[] $fields to update
     * @param array[] $where column-value matching clauses array
     * @return array[] response result of operation
     */
    public function update($table, $fields, $where)
    {
        $update_pack = [];
        foreach ($fields as $field) {
            $update_pack[$field] = $this->validated_data[$field];
        }

        $update = $GLOBALS["database"]->update($table, $update_pack, $where);
        return $this->pack_write_operation($update, $update_pack);
    }
}
