<?php
    //http://localhost:9000/travelapi/api/paesi
    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    //initializing api
    include_once("../../core/initialize.php");

    //instantiate paese
    $paese = new Paese($db);

    if (isset($_GET['nome'])) {
        // Recupera il valore del parametro 'nome'
        $paese->nome = $_GET['nome'];
    }

    //get info from query
    $result = $paese->read_all();
    //get the row count
    $num = $result->rowCount();
    if($num>0){
        $paese_arr = array();
        $paese_arr['data'] = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $paese_item = array(
                'id' => $id,
                'nome' => $nome
            );
            array_push($paese_arr['data'], $paese_item);
        }
        //convert to JSON and output
        http_response_code(200);
        echo json_encode($paese_arr, JSON_PRETTY_PRINT);
    }else{
        http_response_code(404);
        echo json_encode(array('message' => 'No paese found.'), JSON_PRETTY_PRINT);
    }
?>