/* Zie ook Logisch Datamodel */

/* Comment verwijderen na eerste keer 
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
ALTER TABLE verkooporderregel	DROP FOREIGN KEY fk_ArtvOreg; */


DROP TABLE IF EXISTS artikel;
DROP TABLE IF EXISTS leverancier;
DROP TABLE IF EXISTS ingredienten;
DROP TABLE IF EXISTS recept;
DROP TABLE IF EXISTS gebruiker;
DROP TABLE IF EXISTS status;
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
	seriegrootte		INT (5) NOT NULL,
	TV				INT NOT NULL DEFAULT 0,
	IB				INT NOT NULL DEFAULT 0,
	GR				INT NOT NULL DEFAULT 0,
	bestelniveau		INT DEFAULT 0,
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

CREATE TABLE status (
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
	CONSTRAINT fk_StatOrd FOREIGN KEY(statuscode) REFERENCES status(statuscode)
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
	CONSTRAINT fk_StatFac FOREIGN KEY(statuscode) REFERENCES status(statuscode)
) ENGINE=InnoDB;

CREATE TABLE klant (
	klantcode 		INT NOT NULL AUTO_INCREMENT,
	naam			VARCHAR(64) NOT NULL,
	email			VARCHAR(64) NOT NULL,
	adres			VARCHAR(128) NOT NULL,
	telefoonnummer	VARCHAR(16) NOT NULL,
	PRIMARY KEY(klantcode)
) ENGINE=InnoDB AUTO_INCREMENT=100000;

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
	CONSTRAINT fk_StatvOrd FOREIGN KEY(statuscode) REFERENCES status(statuscode)
) ENGINE=InnoDB;

CREATE TABLE verkooporderregel (
	vOrdernr		INT NOT NULL,
	artikelnr		INT NOT NULL,
	aantal			INT NOT NULL,
	PRIMARY KEY(vOrdernr, artikelnr),
	CONSTRAINT fk_vOrdvReg FOREIGN KEY(vOrdernr) REFERENCES verkooporder(vOrdernr),
	CONSTRAINT fk_ArtvOreg FOREIGN KEY(artikelnr) REFERENCES artikel(artikelnr)
) ENGINE=InnoDB;

