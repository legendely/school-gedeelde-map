-- Project EatIT
-- Groep: F4
-- Versie: 1.4


-- De tabellen van de database EatIT toevoegen.
-- De primary keys worden ook toegevoegd bij het maken van de tabellen.
CREATE TABLE AantalArtVerkocht
  (
    BestNR					 INT NOT NULL ,
    ArtNR					 INT NOT NULL ,
    Aantal					 INT NOT NULL ,
	PRIMARY KEY ( BestNR, ArtNR )
  ) ;

CREATE TABLE AantalVerkocht
  (
    BestNR					 INT NOT NULL ,
    GerNR					 INT NOT NULL ,
    Aantal					 INT NOT NULL ,
	PRIMARY KEY ( BestNR, GerNR ) 
  ) ;

CREATE TABLE Aantalingredienten
  (
    AiNR					 INT NOT NULL AUTO_INCREMENT ,
    GerNR					 INT NOT NULL ,
    ArtNR					 INT NOT NULL ,
    ING_Aantal				 INT NOT NULL ,
	PRIMARY KEY ( AiNR ) 
  ) ;

CREATE TABLE Afdeling
  (
    AfdNR					 INT NOT NULL AUTO_INCREMENT ,
    AFD_Naam				 VARCHAR (30) NOT NULL ,
    AFD_Manager				 INT ,
	PRIMARY KEY ( AfdNR ) 
  ) ;

CREATE TABLE Bestelling
  (
    BestNR					 INT NOT NULL AUTO_INCREMENT ,
    KlantNR					 INT NOT NULL ,
    MedNR					 INT ,
    BEST_Datum				 DATE NOT NULL ,
    BEST_Status				 VARCHAR (20) NOT NULL ,
	PRIMARY KEY ( BestNR ) 
  ) ;

CREATE TABLE Gerecht
  (
    GerNR					 INT NOT NULL AUTO_INCREMENT ,
    GER_Naam				 VARCHAR (30) NOT NULL ,
    GER_Prijs				 DOUBLE ,
    GER_Beschrijving		 VARCHAR (100) ,
	PRIMARY KEY ( GerNR ) 
  ) ;

CREATE TABLE Artikelen
  (
    ArtNR					 INT NOT NULL AUTO_INCREMENT ,
    ART_Naam				 VARCHAR (30) ,
    ART_TechnischeVoorraad	 INT ,
    ART_InBestelling		 INT ,
    ART_Gereserveerd		 INT ,
    ART_BestelNiveau		 INT ,
    ART_Leverancier			 INT ,
    ART_Prijs 				 DOUBLE ,
	PRIMARY KEY ( ArtNR ) 
  ) ;

CREATE TABLE Inkoopfactuur
  (
    InkfNR					 INT NOT NULL AUTO_INCREMENT ,
    Inkf_Status				 VARCHAR (60) ,
    Bedrag					 INT NOT NULL ,
	PRIMARY KEY ( InkfNR ) 
  ) ;

CREATE TABLE Inkooporder
  (
    OrderNR					 INT NOT NULL AUTO_INCREMENT ,
    IngNR					 INT NOT NULL ,
    LevNR					 INT NOT NULL ,
    Aantal					 INT ,
	PRIMARY KEY ( OrderNR ) 
  ) ;

CREATE TABLE Bestelorder
  (
    ArtNR					 INT NOT NULL ,
    OrderNR					 INT NOT NULL ,
    Aantal					 INT ,
	PRIMARY KEY ( ArtNR, OrderNR )
  ) ;

CREATE TABLE Klant
  (
    KlantNR					 INT NOT NULL AUTO_INCREMENT ,
    KL_Voornaam				 VARCHAR (20) NOT NULL ,
    KL_Achternaam			 VARCHAR (20) NOT NULL ,
    KL_Telefoonnummer		 VARCHAR (16) NOT NULL ,
    KL_Mail					 VARCHAR (60) NOT NULL ,
    KL_Plaats				 VARCHAR (30) NOT NULL ,
    KL_Adres				 VARCHAR (30) NOT NULL ,
    KL_Postcode				 VARCHAR (6)  NOT NULL ,
    KL_Wachtwoord			 VARCHAR (60) NOT NULL ,
	PRIMARY KEY ( KlantNR ) 
  ) ;

CREATE TABLE Leverancier
  (
    LevNR					 INT NOT NULL AUTO_INCREMENT ,
    LEV_Naam				 VARCHAR (30) NOT NULL ,
    LEV_Telefoonnummer		 VARCHAR (16) ,
    LEV_Mail				 VARCHAR (60) ,
    LEV_Plaats				 VARCHAR (30) ,
    LEV_Adres				 VARCHAR (30) ,
    LEV_Postcode			 VARCHAR (6) ,
	PRIMARY KEY ( LevNR ) 
  ) ;

CREATE TABLE Medewerkers
  (
    MedNR					 INT NOT NULL AUTO_INCREMENT ,
    MED_Voornaam			 VARCHAR (20) NOT NULL ,
    MED_Achternaam			 VARCHAR (20) NOT NULL ,
    MED_Mail				 VARCHAR (60) ,
    MED_Telefoonnummer		 VARCHAR (16) ,
    MED_Plaats				 VARCHAR (30) ,
    MED_Adres				 VARCHAR (30) ,
    MED_Postcode			 VARCHAR (6) ,
	MED_Wachtwoord			 VARCHAR (60) ,
    Afdeling				 INT ,
    Manager_ID				 INT ,
	PRIMARY KEY ( MedNR ) 
  ) ;


-- Alle Foreign Keys worden hieronder toegevoegd.
ALTER TABLE AantalArtVerkocht ADD CONSTRAINT AantalArtVerkocht_Artikelen_FK FOREIGN KEY ( ArtNR ) REFERENCES Artikelen ( ArtNR ) ;

ALTER TABLE AantalArtVerkocht ADD CONSTRAINT AantalArtVerkocht_Bestelling_FK FOREIGN KEY ( BestNR ) REFERENCES Bestelling ( BestNR ) ;

ALTER TABLE AantalVerkocht ADD CONSTRAINT AantalVerkocht_Bestelling_FK FOREIGN KEY ( BestNR ) REFERENCES Bestelling ( BestNR ) ;

ALTER TABLE AantalVerkocht ADD CONSTRAINT AantalVerkocht_Gerecht_FK FOREIGN KEY ( GerNR ) REFERENCES Gerecht ( GerNR ) ;

ALTER TABLE Aantalingredienten ADD CONSTRAINT Aantalingredienten_Artikelen_FK FOREIGN KEY ( ArtNR ) REFERENCES Artikelen ( ArtNR ) ;

ALTER TABLE Aantalingredienten ADD CONSTRAINT Aantalingredienten_Gerecht_FK FOREIGN KEY ( GerNR ) REFERENCES Gerecht ( GerNR ) ;

ALTER TABLE Artikelen ADD CONSTRAINT Artikelen_Leverancier_FK FOREIGN KEY ( ART_Leverancier ) REFERENCES Leverancier ( LevNR ) ;

ALTER TABLE Bestelling ADD CONSTRAINT Bestelling_Klant_FK FOREIGN KEY ( KlantNR ) REFERENCES Klant ( KlantNR ) ;

ALTER TABLE Bestelling ADD CONSTRAINT Bestelling_Medewerkers_FK FOREIGN KEY ( MedNR ) REFERENCES Medewerkers ( MedNR ) ;

ALTER TABLE Bestelorder ADD CONSTRAINT Bestelorder_Artikelen_FK FOREIGN KEY ( ArtNR ) REFERENCES Artikelen ( ArtNR ) ;

ALTER TABLE Bestelorder ADD CONSTRAINT Bestelorder_Inkooporder_FK FOREIGN KEY ( OrderNR ) REFERENCES Inkooporder ( OrderNR ) ;

ALTER TABLE Inkoopfactuur ADD CONSTRAINT Inkoopfactuur_Inkooporder_FK FOREIGN KEY ( InkfNR ) REFERENCES Inkooporder ( OrderNR ) ;

ALTER TABLE Medewerkers ADD CONSTRAINT Medewerkers_Afdeling_FK FOREIGN KEY ( Afdeling ) REFERENCES Afdeling ( AfdNR ) ;

ALTER TABLE Medewerkers ADD CONSTRAINT Medewerkers_Medewerkers_FK FOREIGN KEY ( Manager_ID ) REFERENCES Medewerkers ( MedNR ) ;