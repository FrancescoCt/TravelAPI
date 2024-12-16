<?php
   class Paese{
    //Db stuff
    private $conn;
    private $table = 'paesi';

    //Paese properties
    public $id;
    public $nome;

    //constructor with db connection

    public function __construct($db){
        $this->conn = $db;
    }

    //getting paesi from database
    public function read_all(){
        $query = 'SELECT
            p.id,
            p.nome
            FROM
            '.$this->table . ' p';
        if (!empty($this->nome)) {
            $this->nome = htmlspecialchars(strip_tags($this->nome));
            $this->nome = '%' . strtolower($this->nome) . '%';
            $query .= ' WHERE LOWER(p.nome) LIKE :nome';
        }
        //prepare statement
        $stmt = $this->conn->prepare($query);

        if (!empty($this->nome)) {
            $stmt->bindParam(':nome', $this->nome, PDO::PARAM_STR);
        }
        //execute
        $stmt->execute();
        return $stmt;
    }

    public function read_single(){
        $query = 'SELECT
            p.id,
            p.nome
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
        $this->nome = $row['nome'];
        
        return $stmt;
    }
    //creating paesi
    public function create_paese(){
        $query = 'INSERT INTO ' . $this->table . ' SET nome = :nome';
        $stmt = $this->conn->prepare($query);
        //clean data
        $this->nome = htmlspecialchars(strip_tags($this->nome));

        $stmt->bindParam(':nome', $this->nome);
        if($stmt->execute()){
            return true;
        }
        printf("Error %s.\n", $stmt->error);
        return false;
    }
    //update paese
    public function update_paese(){
        $query = 'UPDATE ' . $this->table . ' 
        SET nome = :nome
        WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        //clean data
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->nome = htmlspecialchars(strip_tags($this->nome));

        $stmt->bindParam(':nome', $this->nome);
        $stmt->bindParam(':id', $this->id);
        if($stmt->execute()){
            return true;
        }
        printf("Error %s.\n", $stmt->error);
        return false;
    }
    //delete paese
    public function delete_paese(){
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
   }
?>