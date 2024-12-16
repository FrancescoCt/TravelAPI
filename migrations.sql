-- Creazione tabelle
CREATE TABLE IF NOT EXISTS Paesi (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(100) NOT NULL CHECK (Nome <> '')
);

CREATE TABLE IF NOT EXISTS Viaggi (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    Posti_disponibili INT NOT NULL
);

CREATE TABLE IF NOT EXISTS Viaggi_Paesi (
    Viaggio_Id INT,
    Paese_Id INT,
    PRIMARY KEY (Viaggio_Id, Paese_Id),
    FOREIGN KEY (Viaggio_Id) REFERENCES Viaggi(Id),
    FOREIGN KEY (Paese_Id) REFERENCES Paesi(Id)
);

-- Trigger di controllo
DELIMITER $$
CREATE TRIGGER prevent_zero_posti_disponibili_insert
BEFORE INSERT ON Viaggi
FOR EACH ROW
BEGIN
    IF NEW.posti_disponibili = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Il valore di posti_disponibili non può essere uguale a 0 durante l inserimento';
    END IF;
END;
$$



-- Esempio di inserimento dati
INSERT INTO Paesi (Nome) VALUES ('Italia') , ('Francia'), ('Germania'), ('Spagna'), ('Grecia');
INSERT INTO Viaggi (Posti_disponibili) VALUES (3) , (6), (1);
INSERT INTO Viaggi_Paesi (Viaggio_Id, Paese_Id) VALUES (1, 1), (1, 2), (1, 4), (2, 3);

--Esempi di select
SELECT 
  v.Id as Codice_Viaggio
, p.nome as Nome_Paese
, v.Posti_disponibili as Posti_disponibili
FROM Viaggi v
JOIN Viaggi_Paesi vp ON v.Id = vp.Viaggio_Id
JOIN Paesi p ON vp.Paese_Id = p.Id;

SELECT 
  v.Id as Codice_Viaggio
, p.nome as Nome_Paese
, v.Posti_disponibili as Posti_disponibili
FROM Viaggi v
JOIN Viaggi_Paesi vp ON v.Id = vp.Viaggio_Id
JOIN Paesi p ON vp.Paese_Id = p.Id
WHERE v.Id = 2;


