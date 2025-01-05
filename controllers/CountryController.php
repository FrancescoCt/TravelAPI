<?php
require_once '../config/Database.php';
require_once '../models/Country.php';

class CountryController {
    private $db;
    private $country;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->country = new Country($this->db);
    }

    //http://localhost:9000/travelapi/api/countries
    public function read() {
        $stmt = $this->country->read();
        $countries = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($countries);
    }

    //http://localhost:9000/travelapi/api/countries/1/
    public function readSingleElement($id) {
        $this->country->readSingleElement($id);
        if($this->country->id > 0){
            $country_arr = array(
                'id' => $this->country->id,
                'name' => $this->country->name
            );
            http_response_code(200);
            print_r(json_encode($country_arr, JSON_PRETTY_PRINT));
        }
        else {
            http_response_code(404);
            echo json_encode(["message" => "Country not found"]);
        }
    }

    //http://localhost:9000/travelapi/api/countries
    // {
    //     "name": "Italy"
    // }
    
    public function create() {
        $data = json_decode(file_get_contents("php://input"));

        $this->country->name = $data->name;

        if ($this->country->create()) {
            http_response_code(201);
            echo json_encode(['message' => 'Country created.']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Cannot create new country.']);
        }
    }

    //http://localhost:9000/travelapi/api/countries
    // {
    //     "id": 1,
    //     "name": "France"
    // }
    
    public function update() {
        $data = json_decode(file_get_contents("php://input"));

        $this->country->id = $data->id;
        $this->country->name = $data->name;

        if ($this->country->update()) {
            echo json_encode(['message' => 'Country updated.']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Cannot update country.']);
        }
    }

    //http://localhost:9000/travelapi/api/countries
    // {
    //     "id": 1
    // }
    
    public function delete() {
        $data = json_decode(file_get_contents("php://input"));

        $this->country->id = $data->id;

        if ($this->country->delete()) {
            echo json_encode(['message' => 'Country deleted.']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Cannot delete country.']);
        }
    }
}
