<?php
    //http://localhost:9000/travelapi/api/viaggi/2

    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    //initializing api
    include_once("../../core/initialize.php");

    //instantiate viaggio
    $viaggio = new Viaggio($db);
    $viaggio->id = isset($_GET['id']) ? $_GET['id'] : 1;
    $result = $viaggio->read_viaggio_details();
    $viaggio_arr = array();
        $viaggio_arr['data'] = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $viaggio_item = array(
                'codice_viaggio' => $codice_viaggio,
                'nome_paese' => $nome_paese,
                'posti_disponibili' => $posti_disponibili
            );
            array_push($viaggio_arr['data'], $viaggio_item);
        }
    http_response_code(200);
    print_r(json_encode($viaggio_arr, JSON_PRETTY_PRINT));
?>