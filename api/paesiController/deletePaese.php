<?php
    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    //initializing api
    include_once("../../core/initialize.php");

    //instantiate paese
    $paese = new Paese($db);

    //get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    $paese->id = $data->id;
    //...

    //delete paese

    if($paese->delete_paese()){
        http_response_code(200);
        echo json_encode(
            array('message' => 'Paese deleted.')
        );
    }
    else{
        http_response_code(400);
        echo json_encode(
            array('message' => 'Error: Paese not deleted.')
        );
    }

?>