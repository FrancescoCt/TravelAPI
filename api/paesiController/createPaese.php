<?php
    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    //initializing api
    include_once("../../core/initialize.php");

    //instantiate paese
    $paese = new Paese($db);

    //get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    $paese->nome = $data->nome;
    //...

    //create paese
    if($paese->create_paese()){
        http_response_code(201);
        echo json_encode(
            array('message' => 'Paese created.')
        );
    }
    else{
        http_response_code(500);
        echo json_encode(
            array('message' => 'Error: Paese not created.')
        );
    }


?>