<?php
class Travel {
    private $conn;
    private $table_name = "travels";
    private $table_relations = "travels_countries";
    private $table_countries = "countries";

    public $id;
    public $available_seats;
    public $country_name;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name ." ORDER BY id;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readSingleElement($id) {
        $sql = "SELECT * FROM ". $this->table_name ." WHERE id = :idRSinT";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":idRSinT", $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->id = $row['Id'];
        $this->available_seats = $row['Available_seats'];
        
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET available_seats=:available_seats";
        $stmt = $this->conn->prepare($query);

        $this->available_seats = htmlspecialchars(strip_tags($this->available_seats));

        $stmt->bindParam(":available_seats", $this->available_seats);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET available_seats = :available_seats WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $this->available_seats = htmlspecialchars(strip_tags($this->available_seats));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':available_seats', $this->available_seats);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function addCountry($country_id) {
        $query = "INSERT INTO ". $this->table_relations ." SET travel_id=:travel_id, country_id=:country_id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $country_id = htmlspecialchars(strip_tags($country_id));

        $stmt->bindParam(':travel_id', $this->id);
        $stmt->bindParam(':country_id', $country_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function readTravelsDetails() {

        $query = 'SELECT 
            t.Id as travel_id
          , c.name as country_name
          , t.available_seats as available_seats
          FROM '.$this->table_name . ' t
          JOIN '.$this->table_relations . ' tc ON t.id = tc.travel_id
          JOIN '.$this->table_countries . ' c ON tc.country_id = c.id';

        //optional parameters
        if (!empty($this->country_name)) {
            $this->country_name = htmlspecialchars(strip_tags($this->country_name));
            $this->country_name = '%' . strtolower($this->country_name) . '%';
            $query .= ' WHERE LOWER(c.name) LIKE :country_name';
        }
        if (!empty($this->available_seats)) {
            $this->available_seats = htmlspecialchars(strip_tags($this->available_seats));
            $query .= ' AND t.available_seats = :available_seats';
        }
        $query .= ' ORDER BY t.id;';
        //prepare statement
        $stmt = $this->conn->prepare($query);

        //bind parameter if it's set
        if (!empty($this->country_name)) {
            $stmt->bindParam(':country_name', $this->country_name, PDO::PARAM_STR);
        }
        if (!empty($this->available_seats)) {
            $stmt->bindParam(':available_seats', $this->available_seats, PDO::PARAM_STR);
        }
        //execute
        $stmt->execute();
        
        return $stmt;
    }

    public function readTravelDetails() {

        $query = 'SELECT 
            t.Id as travel_id
          , c.name as country_name
          , t.available_seats as available_seats
          FROM '.$this->table_name . ' t
          JOIN '.$this->table_relations . ' tc ON t.id = tc.travel_id
          JOIN '.$this->table_countries . ' c ON tc.country_id = c.id
          WHERE t.id = :id;';

        //prepare statement
        $stmt = $this->conn->prepare($query);
        //binding param
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);
        //execute
        $stmt->execute();
        
        return $stmt;
    }

    public function updateCountry($old_country_id, $new_country_id){
        $query = 'UPDATE ' . $this->table_relations . ' 
        SET country_id = :new_country_id
        WHERE travel_id = :travel_id AND country_id = :old_country_id';
        $stmt = $this->conn->prepare($query);
        //clean data
        $this->id = htmlspecialchars(strip_tags($this->id));
        $new_country_id = htmlspecialchars(strip_tags($new_country_id));
        $old_country_id = htmlspecialchars(strip_tags($old_country_id));

        $stmt->bindParam(':travel_id', $this->id);
        $stmt->bindParam(':old_country_id', $this->old_country_id);
        $stmt->bindParam(':new_country_id', $this->new_country_id);
        if($stmt->execute()){
            return true;
        }
        printf("Error %s.\n", $stmt->error);
        return false;
    }

    public function removeCountry($country_id) {
        $query = "DELETE FROM travels_countries WHERE travel_id=:travel_id AND country_id=:country_id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $country_id = htmlspecialchars(strip_tags($country_id));

        $stmt->bindParam(':travel_id', $this->id);
        $stmt->bindParam(':country_id', $country_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // public function getCountries() {
    //     $query = "SELECT c.id, c.name FROM countries c
    //               JOIN travels_countries tc ON c.id = tc.country_id
    //               WHERE tc.travel_id = :travel_id";
    //     $stmt = $this->conn->prepare($query);

    //     $this->id = htmlspecialchars(strip_tags($this->id));
    //     $stmt->bindParam(':travel_id', $this->id);

    //     $stmt->execute();
    //     return $stmt;
    // }
}
