<?php

    //http://localhost:9000/travelapi/api/paesi/2

    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    //initializing api
    include_once("../../core/initialize.php");

    //instantiate paese
    $paese = new Paese($db);

    //get info from query
    $paese->id = isset($_GET['id']) ? $_GET['id'] : 1;
    $paese->read_single();
    
    $paese_arr = array(
        'id' => $paese->id,
        'nome' => $paese->nome
    );
    http_response_code(200);
    print_r(json_encode($paese_arr, JSON_PRETTY_PRINT));
?>