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
    $viaggio->id = $data->id; //oldPaese

    //...

    //create viaggio
    if($viaggio->update_paese_viaggio()){
        http_response_code(200);
        echo json_encode(
            array('message' => 'paese del viaggio updated.')
        );
    }
    else{
        http_response_code(404);
        echo json_encode(
            array('message' => 'Error: paese del viaggio not updated.')
        );
    }


?>