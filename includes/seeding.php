<?php
include_once('config.php');
//Inserimento di dati di prova

try {
    $conn = $db;
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Controlla se la tabella esiste
    $tableExists = $conn->query("SHOW TABLES LIKE 'Paesi'")->rowCount() > 0;

    if (!$tableExists) {
        // Crea la tabella se non esiste
        $createTableSQL = "CREATE TABLE Paesi (
            ID INT AUTO_INCREMENT PRIMARY KEY,
            Nome VARCHAR(100) NOT NULL
        )";
        $conn->exec($createTableSQL);
        echo "Tabella 'Paesi' creata con successo.<br>";
    }

    // Controlla se la tabella è vuota
    $isEmpty = $conn->query("SELECT COUNT(*) FROM Paesi")->fetchColumn() == 0;

    if ($isEmpty) {
        // Esegui l'inserimento se la tabella è vuota
        $insertSQL = "INSERT INTO Paesi (Nome) VALUES ('Italia'), ('Francia'), ('Germania')";
        $conn->exec($insertSQL);
        echo "Dati inseriti con successo.<br>";
    } else {
        echo "La tabella 'Paesi' non è vuota.<br>";
    }
}
catch(PDOException $e) {
    echo "Errore: " . $e->getMessage();
}
?>