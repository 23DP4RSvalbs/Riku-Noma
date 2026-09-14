
CREATE DATABASE IF NOT EXISTS riki_noma
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE riki_noma;

-- LIETOTAJS 
CREATE TABLE LIETOTAJS (
  LietotajaID INT AUTO_INCREMENT PRIMARY KEY,
  Vards VARCHAR(100) NOT NULL,
  Epasts VARCHAR(100) NOT NULL UNIQUE,
  Talrunis VARCHAR(20),
  Parole VARCHAR(255) NOT NULL,        
  RegistracijasDatums TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

--  LOMA 
CREATE TABLE LOMA (
  LomasID INT AUTO_INCREMENT PRIMARY KEY,
  LomasNosaukums VARCHAR(50) NOT NULL UNIQUE  
) ENGINE=InnoDB;

--  LIETOTAJALOMA
CREATE TABLE LIETOTAJALOMA (
  LietotajaLomasID INT AUTO_INCREMENT PRIMARY KEY,
  LietotajaID INT NOT NULL,
  LomasID INT NOT NULL,
  UNIQUE (LietotajaID, LomasID),
  FOREIGN KEY (LietotajaID) REFERENCES LIETOTAJS(LietotajaID) ON DELETE CASCADE,
  FOREIGN KEY (LomasID) REFERENCES LOMA(LomasID) ON DELETE CASCADE
) ENGINE=InnoDB;

--  ADMIN
CREATE TABLE ADMIN (
  AdministratoraID INT AUTO_INCREMENT PRIMARY KEY,
  LietotajaID INT NOT NULL UNIQUE,
  Aktivs BOOLEAN DEFAULT TRUE,
  PedejaPieslegsanas TIMESTAMP NULL,
  FOREIGN KEY (LietotajaID) REFERENCES LIETOTAJS(LietotajaID) ON DELETE CASCADE
) ENGINE=InnoDB;

--  KATEGORIJA 
CREATE TABLE KATEGORIJA (
  KategorijasID INT AUTO_INCREMENT PRIMARY KEY,
  Nosaukums VARCHAR(100) NOT NULL,
  Apraksts TEXT
) ENGINE=InnoDB;

--  RIKS 
CREATE TABLE RIKS (
  RikaID INT AUTO_INCREMENT PRIMARY KEY,
  KategorijasID INT NOT NULL,
  Nosaukums VARCHAR(100) NOT NULL,
  Apraksts TEXT,
  CenaDiena DECIMAL(10,2) NOT NULL CHECK (CenaDiena >= 0),
  Daudzums INT NOT NULL CHECK (Daudzums >= 0),
  Foto VARCHAR(255),
  RedzamsKataloga BOOLEAN DEFAULT TRUE,
  FOREIGN KEY (KategorijasID) REFERENCES KATEGORIJA(KategorijasID) ON DELETE RESTRICT
) ENGINE=InnoDB;

--  PASUTIJUMS
CREATE TABLE PASUTIJUMS (
  PasutijumaID INT AUTO_INCREMENT PRIMARY KEY,
  LietotajaID INT NOT NULL,
  Kopsumma DECIMAL(10,2) NOT NULL DEFAULT 0 CHECK (Kopsumma >= 0),
  Statuss VARCHAR(50) NOT NULL DEFAULT 'Jauns'
      CHECK (Statuss IN ('Jauns','Apstiprinats','Izpildits','Atcelts')),
  IzveidesDatums TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (LietotajaID) REFERENCES LIETOTAJS(LietotajaID) ON DELETE CASCADE
) ENGINE=InnoDB;

--  PASUTIJUMA_RIKS
CREATE TABLE PASUTIJUMA_RIKS (
  PasutijumaRikaID INT AUTO_INCREMENT PRIMARY KEY,
  PasutijumaID INT NOT NULL,
  RikaID INT NOT NULL,
  Daudzums INT NOT NULL CHECK (Daudzums > 0),
  NomasSakums DATE NOT NULL,
  NomasBeigas DATE NOT NULL,
  CHECK (NomasBeigas >= NomasSakums),
  FOREIGN KEY (PasutijumaID) REFERENCES PASUTIJUMS(PasutijumaID) ON DELETE CASCADE,
  FOREIGN KEY (RikaID) REFERENCES RIKS(RikaID) ON DELETE RESTRICT
) ENGINE=InnoDB;

--  INDEKSI 
CREATE INDEX idx_riks_kategorija      ON RIKS(KategorijasID);
CREATE INDEX idx_pas_lietotajs        ON PASUTIJUMS(LietotajaID);
CREATE INDEX idx_pas_statuss          ON PASUTIJUMS(Statuss, IzveidesDatums);
CREATE INDEX idx_pr_pasutijums        ON PASUTIJUMA_RIKS(PasutijumaID);
CREATE INDEX idx_pr_riks_datumi       ON PASUTIJUMA_RIKS(RikaID, NomasSakums, NomasBeigas);

--  SĀKOTNĒJIE DATI 
INSERT INTO LOMA (LomasNosaukums) VALUES ('Klients'), ('Administrators');
INSERT INTO KATEGORIJA (Nosaukums) VALUES
  ('Urbji'), ('Zāģi'), ('Kompresori'), ('Betona maisītāji'), ('Mērinstrumenti');

--  PIEEJAMĪBAS PĀRBAUDE 
SELECT r.RikaID, r.Nosaukums, r.Daudzums - COALESCE(SUM(pr.Daudzums),0) AS Brīvs
FROM RIKS r
LEFT JOIN PASUTIJUMA_RIKS pr
       ON pr.RikaID = r.RikaID
      AND pr.NomasBeigas  >= :sakums
      AND pr.NomasSakums  <= :beigas
WHERE r.RikaID = :rika_id AND r.RedzamsKataloga = TRUE
GROUP BY r.RikaID, r.Nosaukums, r.Daudzums
HAVING Brīvs >= :velamais_daudzums;
