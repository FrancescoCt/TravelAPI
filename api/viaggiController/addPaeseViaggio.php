<?php
    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    //initializing api
    include_once("../../core/initialize.php");

    //instantiate viaggio
    $viaggio = new Viaggio($db);

    //get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    $viaggio->viaggio_id = $data->viaggio_id;
    $viaggio->paese_id = $data->paese_id;
    //...

    //create viaggio
    if($viaggio->add_paese_viaggio()){
        http_response_code(201);
        echo json_encode(
            array('message' => 'viaggio created.')
        );
    }
    else{
        http_response_code(400);
        echo json_encode(
            array('message' => 'Error: viaggio not created.')
        );
    }


?>