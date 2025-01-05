<?php
require_once '../config/Database.php';
require_once '../models/Travel.php';

class TravelController {
    private $db;
    private $travel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->travel = new Travel($this->db);
    }

    //http://localhost:9000/travelapi/api/travels

    public function read() {
        $stmt = $this->travel->read();
        $travels = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        echo json_encode($travels);
    }

    //http://localhost:9000/travelapi/api/travels/1/
    public function readSingleElement($id) {
        $this->travel->readSingleElement($id);
        if($this->travel->id > 0){
            $travel_arr = array(
                'id' => $this->travel->id,
                'available_seats' => $this->travel->available_seats
            );
            http_response_code(200);
            print_r(json_encode($travel_arr, JSON_PRETTY_PRINT));
        }
        else {
            http_response_code(404);
            echo json_encode(["message" => "Travel not found"]);
        }
    }

    //http://localhost:9000/travelapi/api/travels
    // {
    //     "available_seats": 10
    // }
    
    public function create() {
        $data = json_decode(file_get_contents("php://input"));

        $this->travel->available_seats = $data->available_seats;

        if ($this->travel->create()) {
            http_response_code(201);
            echo json_encode(['message' => 'Travel created.']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Cannot create new travel.']);
        }

    }

    //http://localhost:9000/travelapi/api/travels
    // {
    //     "id": 1,
    //     "available_seats": 15
    // }
    
    public function update() {
        $data = json_decode(file_get_contents("php://input"));

        $this->travel->id = $data->id;
        $this->travel->available_seats = $data->available_seats;

        if ($this->travel->update()) {
            echo json_encode(['message' => 'Travel updated.']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Cannot update travel.']);
        }
    }

    //http://localhost:9000/travelapi/api/travels
    // {
    //     "id": 1
    // }
    
    public function delete() {
        $data = json_decode(file_get_contents("php://input"));

        $this->travel->id = $data->id;

        if ($this->travel->delete()) {
            echo json_encode(['message' => 'Travel deleted.']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Cannot delete travel.']);
        }
    }

    //Actions su tabella Travels_Countries

    //http://localhost:9000/travelapi/api/travels/addCountry
    // {
    //     "travel_id": 1,
    //     "country_id": 2
    // }
    

    public function addCountry() {
        $data = json_decode(file_get_contents("php://input"));

        $this->travel->id = $data->travel_id;
        $country_id = $data->country_id;

        if ($this->travel->addCountry($country_id)) {
            http_response_code(201);
            echo json_encode(['message' => 'Country added to travel.']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Cannot add country to travel.']);
        }
    }

    //http://localhost:9000/travelapi/api/travels/details
    //http://localhost:9000/travelapi/api/travels/details/?country_name=Germany&&available_seats=1

    public function readDetails() {

        //optional parameters
        if (isset($_GET['country_name'])) {
            $this->travel->country_name = $_GET['country_name'];
            
        }
        if (isset($_GET['available_seats'])) {
            $this->travel->available_seats = $_GET['available_seats'];
        }

        //get info from query
        $result = $this->travel->readTravelsDetails();
        //get the row count
        $num = $result->rowCount();
        if($num>0){
            $travel_arr = array();
            $travel_arr['data'] = array();

            while($row = $result->fetch(PDO::FETCH_ASSOC)){
                extract($row);
                $travel_item = array(
                    'travel_id' => $travel_id,
                    'country_name' => $country_name,
                    'available_seats' => $available_seats
                );
                array_push($travel_arr['data'], $travel_item);
            }
            //convert to JSON and output
            http_response_code(200);
            echo json_encode($travel_arr, JSON_PRETTY_PRINT);
        }else{
            http_response_code(404);
            echo json_encode(array('message' => 'No travel found.'), JSON_PRETTY_PRINT);
        }
    }

    //http://localhost:9000/travelapi/api/travels/details/1

    public function readSingleDetails($id) {
        $this->travel->id = $id;
        //get info from query
        $result = $this->travel->readTravelDetails();
        $travel_arr = array();
        $travel_arr['data'] = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $travel_item = array(
                'travel_id' => $travel_id,
                'country_name' => $country_name,
                'available_seats' => $available_seats
            );
            array_push($travel_arr['data'], $travel_item);
        }
        //convert to JSON and output
        http_response_code(200);
        echo json_encode($travel_arr, JSON_PRETTY_PRINT);
    }

    //http://localhost:9000/travelapi/api/travels/updateCountry
    // { 
    //     "travel_id": 1, 
    //     "old_country_id": 1, 
    //     "new_country_id": 2 
    // }

    public function updateCountry() {
        $data = json_decode(file_get_contents("php://input"));

        $this->travel->id = $data->travel_id;
        $old_country_id = $data->old_country_id;
        $new_country_id = $data->new_country_id;
        
        if ($this->travel->updateCountry($old_country_id, $new_country_id)) {
            http_response_code(200);
            echo json_encode(['message' => 'Country updated for travel.']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Cannot update country for travel.']);
        }
    }

    //http://localhost:9000/travelapi/api/travels/removeCountry
    // {
    //     "travel_id": 1,
    //     "country_id": 2
    // }

    public function removeCountry() {
        $data = json_decode(file_get_contents("php://input"));

        $this->travel->id = $data->travel_id;
        $country_id = $data->country_id;

        if ($this->travel->removeCountry($country_id)) {
            echo json_encode(['message' => 'Country removed from travel.']);
        } else {
            echo json_encode(['message' => 'Cannot remove country from travel.']);
        }
    }

    //http://localhost:9000/travelapi/api/travels/countries
    // {
    //     "travel_id": 1
    // }

    // public function getCountries() {
    //     $data = json_decode(file_get_contents("php://input"));

    //     $this->travel->id = $data->travel_id;
    //     $stmt = $this->travel->getCountries();
    //     $countries = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //     echo json_encode($countries);
    // }
}
