<?php
    //http://localhost:9000/travelapi/api/viaggi
    //http://localhost:9000/travelapi/api/viaggi?nome_paese=Italia

    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    //initializing api
    include_once("../../core/initialize.php");

    //instantiate viaggio
    $viaggio = new Viaggio($db);

    if (isset($_GET['nome_paese'])) {
        // Recupera il valore del parametro 'nome_paese'
        $viaggio->nome_paese = $_GET['nome_paese'];
    }

    if (isset($_GET['posti_disponibili'])) {
        // Recupera il valore del parametro 'nome_paese'
        $viaggio->posti_disponibili = $_GET['posti_disponibili'];
    }
    
    //get info from query
    $result = $viaggio->read_all();
    //get the row count
    $num = $result->rowCount();
    if($num>0){
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
        //convert to JSON and output
        http_response_code(200);
        echo json_encode($viaggio_arr, JSON_PRETTY_PRINT);
    }else{
        http_response_code(404);
        echo json_encode(array('message' => 'No viaggio found.'), JSON_PRETTY_PRINT);
    }
?>