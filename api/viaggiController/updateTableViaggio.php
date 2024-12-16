<?php
    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    //initializing api
    include_once("../../core/initialize.php");

    //instantiate viaggio
    $viaggio = new Viaggio($db);

    //get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    $viaggio->id = $data->id;
    $viaggio->posti_disponibili = $data->posti_disponibili;
    //...

    //create viaggio
    if($viaggio->update_table_viaggio()){
        http_response_code(200);
        echo json_encode(
            array('message' => 'viaggio updated.')
        );
    }
    else{
        http_response_code(400);
        echo json_encode(
            array('message' => 'Error: viaggio not updated.')
        );
    }

    //Nota: ho creato un updateViaggioForm.html, aprendo la pagina parte una fetch che esegue la post correttamente (Con PostMan ho avuto difficoltà)
    


?>