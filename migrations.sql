--Dopo essere entrati come utente root applicare i comandi seguenti
--Creazione database
CREATE DATABASE IF NOT EXISTS travelapi;

--Creazione utente personalizzato (-u developer -p developer)
CREATE USER 'developer'@'%' IDENTIFIED BY 'developer';
GRANT ALL PRIVILEGES ON travelapi.* TO 'developer'@'%';
FLUSH PRIVILEGES;
SHOW GRANTS FOR 'developer'@'%';

--A questo punto uscire da mysql con i privilegi di amministratore e riloggarsi con la stringa:
--mysql -u developer -p

-- Creazione tabelle
CREATE TABLE IF NOT EXISTS Countries (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100) UNIQUE NOT NULL CHECK (Name <> '')
);

CREATE TABLE IF NOT EXISTS Travels (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    Available_seats INT NOT NULL
);

CREATE TABLE IF NOT EXISTS Travels_Countries (
    Travel_Id INT,
    Country_Id INT,
    PRIMARY KEY (Travel_Id, Country_Id),
    FOREIGN KEY (Travel_Id) REFERENCES Travels(Id),
    FOREIGN KEY (Country_Id) REFERENCES Countries(Id)
);

-- Trigger di controllo
DELIMITER $$
CREATE TRIGGER prevent_zero_available_seats_insert
BEFORE INSERT ON Travels
FOR EACH ROW
BEGIN
    IF NEW.available_seats = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Value of available seats cannot be equal to 0 during insert';
    END IF;
END;
$$



-- Esempio di inserimento dati
INSERT INTO Countries (name) VALUES ('Italy'), ('France'), ('Germany'), ('Spain'), ('Greece');
INSERT INTO Travels (available_seats) VALUES (3), (6), (1);
INSERT INTO Travels_Countries (travel_Id, country_Id) VALUES (1, 1), (1, 2), (1, 4), (2, 3);

--Esempi di select
-- SELECT 
--   v.Id as Codice_Viaggio
-- , p.nome as Nome_Paese
-- , v.Posti_disponibili as Posti_disponibili
-- FROM Viaggi v
-- JOIN Viaggi_Paesi vp ON v.Id = vp.Viaggio_Id
-- JOIN Paesi p ON vp.Paese_Id = p.Id;

-- SELECT 
--   v.Id as Codice_Viaggio
-- , p.nome as Nome_Paese
-- , v.Posti_disponibili as Posti_disponibili
-- FROM Viaggi v
-- JOIN Viaggi_Paesi vp ON v.Id = vp.Viaggio_Id
-- JOIN Paesi p ON vp.Paese_Id = p.Id
-- WHERE v.Id = 2;


