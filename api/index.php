<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

require_once '../controllers/CountryController.php';
require_once '../controllers/TravelController.php';

function checkControllerType($requestUriArray) {
    $count = count($requestUriArray);
    if ($count == 0) {
        return null; // L'array è vuoto
    }

    $lastElement = $requestUriArray[$count - 1];
    if (!is_numeric($lastElement)) {
        //http://localhost:9000/travelapi/api/travels/ or http://localhost:9000/travelapi/api/travels/countries
        $res = in_array($lastElement, ['countries', 'travels']) ? $lastElement : $requestUriArray[$count - 2];
        //http://localhost:9000/travelapi/api/travels/details/?country_name=Germany
        $res = $res === 'details' ? $requestUriArray[$count - 3] : $res;
        return  $res;
    } elseif ($count > 1) {
        //http://localhost:9000/travelapi/api/travels/details/2 or http://localhost:9000/travelapi/api/travels/2
        $res = in_array($requestUriArray[$count - 2], ['details']) ? $requestUriArray[$count - 3] : $requestUriArray[$count - 2];
        return $res;
    } else {
        return null; // L'array ha un solo elemento che è numerico
    }
}

//Choose the controller that performs the right action
$method = $_SERVER['REQUEST_METHOD'];
$requestUriArr = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
$controllerChooser = checkControllerType($requestUriArr);

switch ($controllerChooser) {
    case 'countries':
        $controller = new CountryController();
        break;
    case 'travels':
        $controller = new TravelController();
        break;
    default:
        echo json_encode(['message' => 'Endpoint not found.']);
        exit();
}
//Perform the right action depending on method and uri composition
$action = $requestUriArr[count($requestUriArr) - 1];
switch ($method) {
    case 'GET':
        if($action === 'details' || str_contains($action, '?')) {#travels/details or travels/details/?country_name=CountryName
            $controller->readDetails();
            return;
        }
        else if(is_numeric($action)) {
            $id = $action;
            if($requestUriArr[count($requestUriArr) - 2] === 'details'){#travels/details/1
                $controller->readSingleDetails($id);
                return;
            }
            else{ #travels/1
                $controller->readSingleElement($id);
                return;
            }
        } else {#travels/ or countries/
            $controller->read();
            return;
        }
        break;
    case 'POST':
        if ($action === 'addCountry') {#travels/addCountry
            $controller->addCountry();
            return;
        }  else {
            $controller->create();
            return;
        }
        break;
    case 'PUT':
        if ($action === 'updateCountry') {#travels/addCountry
            $controller->updateCountry();
            return;
        } 
        else{
            $controller->update();
            return;
        }
        
        break;
    case 'DELETE':
        if ($action === 'removeCountry') {#travels/removeCountry
            $controller->removeCountry();
            return;
        }
        else{
          $controller->delete(); 
          return;
        }
        break;
    default:
        echo json_encode(['message' => 'Unsupported method.']);
        break;
}
