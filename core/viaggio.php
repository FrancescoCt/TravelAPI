<?php
   class Viaggio{
    //Db stuff
    private $conn;
    private $table = 'viaggi';
    private $table1 = 'paesi';
    private $tableDetails = 'viaggi_paesi';

    //viaggio properties
    public $id;
    public $posti_disponibili;
    public $nome_paese;
    public $viaggio_id;
    public $paese_id;

    //constructor with db connection

    public function __construct($db){
        $this->conn = $db;
    }

    //getting viaggi from database
    public function read_table_viaggi(){
         $query = 'SELECT
             p.id,
             p.posti_disponibili
             FROM
             '.$this->table . ' p';
         //prepare statement
         $stmt = $this->conn->prepare($query);
         //execute
         $stmt->execute();
         return $stmt;
    }

    public function read_single_viaggio(){
         $query = 'SELECT
             p.id,
             p.posti_disponibili
             FROM
             '.$this->table . ' p
             WHERE p.id = ? LIMIT 1';
         //prepare statement
         $stmt = $this->conn->prepare($query);
         //binding param
         $stmt->bindParam(1, $this->id);
         //execute
         $stmt->execute();

         $row = $stmt->fetch(PDO::FETCH_ASSOC);

         $this->id = $row['id'];
         $this->posti_disponibili = $row['posti_disponibili'];
        
         return $stmt;
    }
    public function read_all(){
        $query = 'SELECT 
          v.Id as codice_viaggio
        , p.nome as nome_paese
        , v.Posti_disponibili as posti_disponibili
        FROM '.$this->table . ' v
        JOIN '.$this->tableDetails . ' vp ON v.Id = vp.Viaggio_Id
        JOIN '.$this->table1 . ' p ON vp.Paese_Id = p.Id';
        
        //optional parameters
        if (!empty($this->nome_paese)) {
            $this->nome_paese = htmlspecialchars(strip_tags($this->nome_paese));
            $this->nome_paese = '%' . strtolower($this->nome_paese) . '%';
            $query .= ' WHERE LOWER(p.nome) LIKE :nome_paese';
        }
        if (!empty($this->posti_disponibili)) {
            $this->posti_disponibili = htmlspecialchars(strip_tags($this->posti_disponibili));
            $query .= ' AND v.posti_disponibili = :posti_disponibili';
        }
        //prepare statement
        $stmt = $this->conn->prepare($query);
        
        //bind parameter if it's set
        if (!empty($this->nome_paese)) {
            $stmt->bindParam(':nome_paese', $this->nome_paese, PDO::PARAM_STR);
        }
        if (!empty($this->posti_disponibili)) {
            $stmt->bindParam(':posti_disponibili', $this->posti_disponibili, PDO::PARAM_STR);
        }
        //execute
        $stmt->execute();
        return $stmt;
    }
    public function read_viaggio_details(){
        $query = 'SELECT 
          v.Id as codice_viaggio
        , p.nome as nome_paese
        , v.Posti_disponibili as posti_disponibili
        FROM '.$this->table . ' v
        JOIN '.$this->tableDetails . ' vp ON v.Id = vp.Viaggio_Id
        JOIN '.$this->table1 . ' p ON vp.Paese_Id = p.Id
        WHERE v.Id = :id;';
        //prepare statement
        $stmt = $this->conn->prepare($query);
        //binding param
        $stmt->bindParam(':id', $this->id);
        //execute
        $stmt->execute();
        
        return $stmt;
    }
    //update viaggio
    public function update_table_viaggio(){
        $query = 'UPDATE ' . $this->table . ' 
        SET posti_disponibili = :posti_disponibili
        WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        //clean data
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->posti_disponibili = htmlspecialchars(strip_tags($this->posti_disponibili));

        $stmt->bindParam(':posti_disponibili', $this->posti_disponibili);
        $stmt->bindParam(':id', $this->id);
        if($stmt->execute()){
            return true;
        }
        printf("Error %s.\n", $stmt->error);
        return false;
    }
    public function update_paese_viaggio(){
        $query = 'UPDATE ' . $this->tableDetails . ' 
        SET paese_id = :paese_id
        WHERE viaggio_id = :viaggio_id AND paese_id = :id';
        $stmt = $this->conn->prepare($query);
        //clean data
        $this->viaggio_id = htmlspecialchars(strip_tags($this->viaggio_id));
        $this->paese_id = htmlspecialchars(strip_tags($this->paese_id));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':viaggio_id', $this->viaggio_id);
        $stmt->bindParam(':paese_id', $this->paese_id);
        $stmt->bindParam(':id', $this->id);
        if($stmt->execute()){
            return true;
        }
        printf("Error %s.\n", $stmt->error);
        return false;
    }
    //delete viaggio
    public function delete_paese_viaggio(){
        $query = 'DELETE FROM ' . $this->tableDetails . ' WHERE paese_id = :paese_id AND viaggio_id = :viaggio_id';
        $stmt = $this->conn->prepare($query);
        //clean data
        $this->paese_id = htmlspecialchars(strip_tags($this->paese_id));
        $this->viaggio_id = htmlspecialchars(strip_tags($this->viaggio_id));
        $stmt->bindParam(':paese_id', $this->paese_id);
        $stmt->bindParam(':viaggio_id', $this->viaggio_id);
        if($stmt->execute()){
            return true;
        }
        printf("Error %s.\n", $stmt->error);
        return false;
    }
    public function delete_viaggio(){
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        //clean data
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);
        if($stmt->execute()){
            return true;
        }
        printf("Error %s.\n", $stmt->error);
        return false;
    }

    //getting viaggi from database
    
    //creating viaggi
    public function create_viaggio(){
        $query = 'INSERT INTO ' . $this->table . ' SET posti_disponibili = :posti_disponibili';
        $stmt = $this->conn->prepare($query);
        //clean data
        $this->posti_disponibili = htmlspecialchars(strip_tags($this->posti_disponibili));

        $stmt->bindParam(':posti_disponibili', $this->posti_disponibili);
        if($stmt->execute()){
            return true;
        }
        printf("Error %s.\n", $stmt->error);
        return false;
    }
    public function add_paese_viaggio(){
        $query = 'INSERT INTO '.$this->tableDetails.' (Viaggio_Id, Paese_Id) VALUES (:viaggio_id, :paese_id)';
        $stmt = $this->conn->prepare($query);
        //clean data
        $this->viaggio_id = htmlspecialchars(strip_tags($this->viaggio_id));
        $this->paese_id = htmlspecialchars(strip_tags($this->paese_id));

        $stmt->bindParam(':viaggio_id', $this->viaggio_id);
        $stmt->bindParam(':paese_id', $this->paese_id);
        if($stmt->execute()){
            return true;
        }
        printf("Error %s.\n", $stmt->error);
        return false;
    }

   }
?>