<?php

    //http://localhost:9000/travelapi/api/paesi/2

    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    //initializing api
    include_once("../../core/initialize.php");

    //instantiate viaggio
    $viaggio = new Viaggio($db);

    //get info from query
    $viaggio->id = isset($_GET['id']) ? $_GET['id'] : 1;
    $viaggio->read_single_viaggio();
    
    $viaggio_arr = array(
        'id' => $viaggio->id,
        'posti_disponibili' => $viaggio->posti_disponibili
    );
    http_response_code(200);
    print_r(json_encode($viaggio_arr, JSON_PRETTY_PRINT));
?>