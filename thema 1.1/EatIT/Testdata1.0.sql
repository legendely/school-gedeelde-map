/* Zie ook Logisch Datamodel */

/*
ALTER TABLE ingredienten 		DROP FOREIGN KEY fk_LevIngred;

ALTER TABLE recept 				DROP FOREIGN KEY fk_ArtRec;
ALTER TABLE recept 				DROP FOREIGN KEY fk_IngredRec;

ALTER TABLE inkooporder 		DROP FOREIGN KEY fk_LeviOrd;
ALTER TABLE inkooporder 		DROP FOREIGN KEY fk_StatOrd;

ALTER TABLE inkooporderregel 	DROP FOREIGN KEY fk_OrdReg;
ALTER TABLE inkooporderregel 	DROP FOREIGN KEY fk_IngrOreg;

ALTER TABLE inkoopfactuur	 	DROP FOREIGN KEY fk_OrdFac;
ALTER TABLE inkoopfactuur	 	DROP FOREIGN KEY fk_StatFac;

ALTER TABLE verkooporder	 	DROP FOREIGN KEY fk_KltvOrd;
ALTER TABLE verkooporder	 	DROP FOREIGN KEY fk_StatvOrd;

ALTER TABLE verkooporderregel	DROP FOREIGN KEY fk_vOrdvReg;
ALTER TABLE verkooporderregel	DROP FOREIGN KEY fk_ArtvOreg;*/


DROP TABLE IF EXISTS artikel;
DROP TABLE IF EXISTS leverancier;
DROP TABLE IF EXISTS ingredienten;
DROP TABLE IF EXISTS recept;
DROP TABLE IF EXISTS gebruiker;
DROP TABLE IF EXISTS instatus;
DROP TABLE IF EXISTS verstatus;
DROP TABLE IF EXISTS inkooporder;
DROP TABLE IF EXISTS inkooporderregel;
DROP TABLE IF EXISTS inkoopfactuur;
DROP TABLE IF EXISTS klant;
DROP TABLE IF EXISTS verkooporder;
DROP TABLE IF EXISTS verkooporderregel;


/* Aanmaak tabellen */
CREATE TABLE artikel (
	artikelnr 		INT NOT NULL AUTO_INCREMENT,
	omschrijving 	VARCHAR(128) NOT NULL,
	verkoopprijs	FLOAT NOT NULL,
	voorraad		INT NOT NULL DEFAULT 0,
	PRIMARY KEY(artikelnr)
) ENGINE=InnoDB;

CREATE TABLE leverancier (
	levnr			INT NOT NULL AUTO_INCREMENT,
	naam			VARCHAR(64) NOT NULL,
	plaats			VARCHAR(64) NOT NULL,
	adres			VARCHAR(128) NOT NULL,
	telefoonnummer	VARCHAR(16) NOT NULL,
	PRIMARY KEY(levnr)
) ENGINE=InnoDB;

CREATE TABLE ingredienten (
	ingrednr		INT NOT NULL AUTO_INCREMENT,
	omschrijving	VARCHAR(128) NOT NULL,
	inkoopprijs		FLOAT NOT NULL,
	TV				INT NOT NULL DEFAULT 0,
	IB				INT NOT NULL DEFAULT 0,
	GR				INT NOT NULL DEFAULT 0,
	bestelniveau	INT DEFAULT 0,
	seriegrootte	INT NOT NULL,
	levnr			INT NOT NULL,
	PRIMARY KEY(ingrednr),
	CONSTRAINT fk_LevIngred FOREIGN KEY(levnr) REFERENCES leverancier(levnr)	
) ENGINE=InnoDB;

CREATE TABLE recept (
	artikelnr		INT NOT NULL,
	ingrednr		INT NOT NULL,
	aantal			INT DEFAULT 0,
	PRIMARY KEY(artikelnr, ingrednr),
	CONSTRAINT fk_ArtRec FOREIGN KEY(artikelnr) REFERENCES artikel(artikelnr),
	CONSTRAINT fk_IngredRec FOREIGN KEY(ingrednr) REFERENCES ingredienten(ingrednr)
) ENGINE=InnoDB;

CREATE TABLE gebruiker (
	gebruikersnaam	VARCHAR(32) NOT NULL,
	wachtwoord		VARCHAR(32) NOT NULL,
	PRIMARY KEY(gebruikersnaam)
) ENGINE=InnoDB;

CREATE TABLE instatus (
	statuscode		INT NOT NULL,
	omschrijving	VARCHAR(64) NOT NULL,
	PRIMARY KEY(statuscode)
) ENGINE=InnoDB;

CREATE TABLE verstatus (
	statuscode		INT NOT NULL,
	omschrijving	VARCHAR(64) NOT NULL,
	PRIMARY KEY(statuscode)
) ENGINE=InnoDB;

CREATE TABLE inkooporder (
	iOrdernr		INT NOT NULL AUTO_INCREMENT,
	datum			DATE NOT NULL,
	levnr			INT NOT NULL,
	totaalbedrag	FLOAT NOT NULL,
	statuscode		INT NOT NULL DEFAULT 1,
	PRIMARY KEY(iOrdernr),
	CONSTRAINT fk_LeviOrd FOREIGN KEY(levnr) REFERENCES leverancier(levnr),
	CONSTRAINT fk_StatOrd FOREIGN KEY(statuscode) REFERENCES instatus(statuscode)
) ENGINE=InnoDB;

CREATE TABLE inkooporderregel (
	iOrdernr		INT NOT NULL,
	ingrednr		INT NOT NULL,
	besteld			INT NOT NULL,
	geaccepteerd	INT,
	PRIMARY KEY(iOrdernr, ingrednr),
	CONSTRAINT fk_OrdReg FOREIGN KEY(iOrdernr) REFERENCES inkooporder(iOrdernr),
	CONSTRAINT fk_IngrOReg FOREIGN KEY(ingrednr) REFERENCES ingredienten(ingrednr)
) ENGINE=InnoDB;

CREATE TABLE inkoopfactuur (
	factuurnr		INT NOT NULL AUTO_INCREMENT,
	levfactuur		INT NOT NULL,
	iOrdernr		INT NOT NULL,
	factuurdatum	DATE NOT NULL,
	betaaldatum		DATE NOT NULL,
	bedrag			FLOAT NOT NULL,
	statuscode		INT NOT NULL,
	PRIMARY KEY(factuurnr),
	CONSTRAINT fk_OrdFac FOREIGN KEY(iOrdernr) REFERENCES inkooporder(iOrdernr),
	CONSTRAINT fk_StatFac FOREIGN KEY(statuscode) REFERENCES instatus(statuscode)
) ENGINE=InnoDB;

CREATE TABLE klant (
	klantcode 		INT NOT NULL AUTO_INCREMENT,
	voornaam			VARCHAR(64) NOT NULL,
	achternaam		VARCHAR(64) NOT NULL,
	email			VARCHAR(64) NOT NULL,
	adres			VARCHAR(128) NOT NULL,
	plaats			VARCHAR(128) NOT NULL,
	telefoonnummer	VARCHAR(10) NOT NULL,
	PRIMARY KEY(klantcode)
) ENGINE=InnoDB AUTO_INCREMENT=10000;

CREATE TABLE verkooporder (
	vOrdernr		INT NOT NULL AUTO_INCREMENT,
	klantcode		INT NOT NULL,
	besteldatum		DATETIME NOT NULL,
	aflevertijd		DATETIME NOT NULL,
	totaalprijs		FLOAT NOT NULL,
	afleveradres	VARCHAR(128) NOT NULL,
	statuscode		INT NOT NULL,
	PRIMARY KEY(vOrdernr),
	CONSTRAINT fk_KltvOrd FOREIGN KEY(klantcode) REFERENCES klant(klantcode),
	CONSTRAINT fk_StatvOrd FOREIGN KEY(statuscode) REFERENCES verstatus(statuscode)
) ENGINE=InnoDB;

CREATE TABLE verkooporderregel (
	vOrdernr		INT NOT NULL,
	artikelnr		INT NOT NULL,
	aantal			INT NOT NULL,
	PRIMARY KEY(vOrdernr, artikelnr),
	CONSTRAINT fk_vOrdvReg FOREIGN KEY(vOrdernr) REFERENCES verkooporder(vOrdernr),
	CONSTRAINT fk_ArtvOreg FOREIGN KEY(artikelnr) REFERENCES artikel(artikelnr)
) ENGINE=InnoDB;

INSERT INTO klant (voornaam, achternaam, email, adres, plaats, telefoonnummer) VALUES ('Henk ', 'Pietje', 'henkpietje@live.nl', 'Hendriksstraat 10', 'Groningen', '050-8754875');
INSERT INTO klant (voornaam, achternaam, email, adres, plaats, telefoonnummer) VALUES ('Jan', 'Hendriks', 'janhendriks@live.nl', 'Wilhelminestraat 15', 'Groningen', '050-9875436');
INSERT INTO klant (voornaam, achternaam, email, adres, plaats, telefoonnummer) VALUES ('Hens', 'Ricksen', 'hensricksen@live.nl', 'Pietjesstraat 85', 'Groningen', '050-8547856');
INSERT INTO klant (voornaam, achternaam, email, adres, plaats, telefoonnummer) VALUES ('Jan', 'Jofel', 'janjofel@live.nl', 'Herestraat 54', 'Groningen', '050-8754321');
INSERT INTO klant (voornaam, achternaam, email, adres, plaats, telefoonnummer) VALUES ('Marta', 'Grieten', 'martagrieten@live.nl', 'Hoofdstraat 2', 'Groningen', '050-5474513');

INSERT INTO artikel (artikelnr, omschrijving, verkoopprijs, voorraad) VALUES (1000, 'Spaghetti bolognese', 3.50, 100);
INSERT INTO artikel (artikelnr, omschrijving, verkoopprijs, voorraad) VALUES (2000, 'Lekkerbekje', 4.50, 110);
INSERT INTO artikel (artikelnr, omschrijving, verkoopprijs, voorraad) VALUES (3000, 'Halve kip', 5.50, 125);

INSERT INTO artikel (artikelnr, omschrijving, verkoopprijs, voorraad) VALUES (4000, 'Bier Sixpack', 5.50, 125);
INSERT INTO artikel (artikelnr, omschrijving, verkoopprijs, voorraad) VALUES (5000, 'Wijn Rood 1L', 7.95, 100);
INSERT INTO artikel (artikelnr, omschrijving, verkoopprijs, voorraad) VALUES (6000, 'Wijn Wit 1L', 7.95, 100);
INSERT INTO artikel (artikelnr, omschrijving, verkoopprijs, voorraad) VALUES (7000, 'Wijn Blauw 1L', 7.95, 100);

INSERT INTO artikel (artikelnr, omschrijving, verkoopprijs, voorraad) VALUES (8000, 'Cola 1L', 4.49, 100);
INSERT INTO artikel (artikelnr, omschrijving, verkoopprijs, voorraad) VALUES (9000, 'Sinas 1L', 4.49, 100);
INSERT INTO artikel (artikelnr, omschrijving, verkoopprijs, voorraad) VALUES (10000, 'Cassis 1L', 4.49, 100);

INSERT INTO leverancier (naam, plaats, adres, telefoonnummer) VALUES ('JoopINC', 'Groningen', 'Stoopstraat 10', '050-4587542');

INSERT INTO ingredienten (omschrijving, inkoopprijs, TV, IB, GR, bestelniveau, seriegrootte, levnr) VALUES ('bolognese saus', '0.05', '200', '0', '0', '10', 100,'1');
INSERT INTO ingredienten (omschrijving, inkoopprijs, TV, IB, GR, bestelniveau, seriegrootte, levnr) VALUES ('Spaghetti', '0.20', '200', '0', '0', '10', 100,'1');
INSERT INTO ingredienten (omschrijving, inkoopprijs, TV, IB, GR, bestelniveau, seriegrootte, levnr) VALUES ('Gehakt', '0.25', '200', '0', '0', '10', 100,'1');
INSERT INTO ingredienten (omschrijving, inkoopprijs, TV, IB, GR, bestelniveau, seriegrootte, levnr) VALUES ('Lekkerbekjes', '0.30', '220', '0', '0', '10', 100,'1');
INSERT INTO ingredienten (omschrijving, inkoopprijs, TV, IB, GR, bestelniveau, seriegrootte, levnr) VALUES ('Panneermiddel', '0.08', '220', '0', '0', '10',100,'1');
INSERT INTO ingredienten (omschrijving, inkoopprijs, TV, IB, GR, bestelniveau, seriegrootte, levnr) VALUES ('Halve kippen', '0.45', '200', '0', '0', '10',100,'1');
INSERT INTO ingredienten (omschrijving, inkoopprijs, TV, IB, GR, bestelniveau, seriegrootte, levnr) VALUES ('Kipkruiden', '0.10', '200', '0', '0', '10', 100,'1');
INSERT INTO ingredienten (omschrijving, inkoopprijs, TV, IB, GR, bestelniveau, seriegrootte, levnr) VALUES ('Zout', '0.05', '100', '0', '0', '10', 100,'1');
INSERT INTO ingredienten (omschrijving, inkoopprijs, TV, IB, GR, bestelniveau, seriegrootte, levnr) VALUES ('Peper', '0.05', '100', '0', '0', '10', 100,'1');

INSERT INTO recept (artikelnr, ingrednr, aantal) VALUES ('1000', '1', '200');
INSERT INTO recept (artikelnr, ingrednr, aantal) VALUES ('1000', '2', '200');
INSERT INTO recept (artikelnr, ingrednr, aantal) VALUES ('1000', '3', '200');
INSERT INTO recept (artikelnr, ingrednr, aantal) VALUES ('2000', '4', '220');
INSERT INTO recept (artikelnr, ingrednr, aantal) VALUES ('2000', '5', '220');
INSERT INTO recept (artikelnr, ingrednr, aantal) VALUES ('3000', '6', '200');
INSERT INTO recept (artikelnr, ingrednr, aantal) VALUES ('3000', '7', '200');
INSERT INTO recept (artikelnr, ingrednr, aantal) VALUES ('1000', '8', '100');
INSERT INTO recept (artikelnr, ingrednr, aantal) VALUES ('2000', '8', '100');
INSERT INTO recept (artikelnr, ingrednr, aantal) VALUES ('3000', '8', '100');
INSERT INTO recept (artikelnr, ingrednr, aantal) VALUES ('1000', '9', '100');
INSERT INTO recept (artikelnr, ingrednr, aantal) VALUES ('2000', '9', '100');
INSERT INTO recept (artikelnr, ingrednr, aantal) VALUES ('3000', '9', '100');

INSERT INTO gebruiker (gebruikersnaam, wachtwoord) VALUES ('admin', 'adminadmin');

INSERT INTO instatus (statuscode, omschrijving) VALUES ('1', 'Besteld');
INSERT INTO instatus (statuscode, omschrijving) VALUES ('2', 'Ontvangen');
INSERT INTO instatus (statuscode, omschrijving) VALUES ('3', 'OK');
INSERT INTO instatus (statuscode, omschrijving) VALUES ('4', 'Betaald');
INSERT INTO instatus (statuscode, omschrijving) VALUES ('5', 'Afgerond');

INSERT INTO verstatus (statuscode, omschrijving) VALUES ('1', 'geplaatst');
INSERT INTO verstatus (statuscode, omschrijving) VALUES ('2', 'klaar');
INSERT INTO verstatus (statuscode, omschrijving) VALUES ('3', 'afgerond');



