-- 1. Désactiver les contraintes de clés étrangères pour exécuter les DROP sans conflit
PRAGMA foreign_keys = OFF;

-- 2. Suppression des vues existantes
DROP VIEW IF EXISTS v_solde_compte_client;
DROP VIEW IF EXISTS v_montant_recu_client;
DROP VIEW IF EXISTS v_detail_montant_compte_client;
DROP VIEW IF EXISTS v_transaction_and_type_operation;
DROP VIEW IF EXISTS v_user_client;

-- 3. Suppression des tables existantes
DROP TABLE IF EXISTS transactions;
DROP TABLE IF EXISTS frais;
DROP TABLE IF EXISTS operation;
DROP TABLE IF EXISTS num_prefixe_valable;
DROP TABLE IF EXISTS users;

-- 4. Réactivation des contraintes de clés étrangères
PRAGMA foreign_keys = ON;

-- 5. Création des tables
CREATE TABLE users (
    id             INTEGER PRIMARY KEY AUTOINCREMENT,
    nom            TEXT,                 
    prenom         TEXT,
    role           TEXT NOT NULL CHECK (role IN ('client','operateur')),
    numero         TEXT NOT NULL UNIQUE,
    date_naissance DATE,
    mot_de_passe   TEXT,
    created_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE num_prefixe_valable (
    id     INTEGER PRIMARY KEY AUTOINCREMENT,
    prefix TEXT NOT NULL UNIQUE,
    id_operateur INT,
    actif  INTEGER NOT NULL DEFAULT 1 CHECK (actif IN (0,1))
);

CREATE TABLE operation (
    id   INTEGER PRIMARY KEY AUTOINCREMENT,
    nom  TEXT NOT NULL,
    code TEXT NOT NULL UNIQUE
);

CREATE TABLE frais (
    id           INTEGER PRIMARY KEY AUTOINCREMENT,
    operation_id INTEGER NOT NULL REFERENCES operation(id),
    min          NUMERIC NOT NULL,
    max          NUMERIC NOT NULL,
    frais_val    NUMERIC NOT NULL
);

CREATE TABLE transactions (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id         INTEGER NOT NULL REFERENCES users(id),
    operation_id    INTEGER NOT NULL REFERENCES operation(id),
    destinataire_id INTEGER REFERENCES users(id),                 
    montant         NUMERIC NOT NULL,                             
    frais_montant   NUMERIC NOT NULL DEFAULT 0,                   
    description     TEXT,
    date_op         DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE reduction (
    reduct NUMERIC NOT NULL
);

INSERT INTO reduction (reduct) VALUES (60.0);

-- 6. Création des vues

CREATE VIEW v_user_client AS
SELECT
    *
FROM
    users
WHERE
    role = 'client';

CREATE VIEW v_transaction_and_type_operation AS
SELECT
    t.*,
    o.code code_operation
FROM
    transactions t
    JOIN operation o ON t.operation_id = o.id;

-- RECUPERER LE SOLDE D UN COMPTE CLIENT
CREATE VIEW v_detail_montant_compte_client AS
SELECT
    user_id,
    SUM(
        CASE
            WHEN code_operation = 'DEP' THEN montant
            ELSE 0
        END
    ) AS montant_total_depo,
    SUM(
        CASE
            WHEN code_operation = 'RET' THEN montant + frais_montant
            ELSE 0
        END
    ) AS montant_total_retrait_plus_frais,
    SUM(
        CASE
            WHEN code_operation = 'TRA' THEN montant + frais_montant
            ELSE 0
        END
    ) AS montant_transmise_plus_frais
FROM
    v_transaction_and_type_operation
GROUP BY
    user_id;

-- RECUPERER LE MONTANT RECU POUR UN CLIENT PRECIS
CREATE VIEW v_montant_recu_client AS
SELECT
    destinataire_id user_id,
    SUM(montant) total_montant_recu
FROM
    transactions
WHERE
    destinataire_id IS NOT NULL
GROUP BY
    destinataire_id;

-- RECUPERER LE SOLDE D UN COMPTE (Inclut la correction avec v_user_client pour ne manquer aucun client)
CREATE VIEW v_solde_compte_client AS 
SELECT
    u.id AS user_id,
    (
        COALESCE(vdmcc.montant_total_depo, 0) + 
        COALESCE(vmrc.total_montant_recu, 0) - 
        COALESCE(vdmcc.montant_total_retrait_plus_frais, 0) - 
        COALESCE(vdmcc.montant_transmise_plus_frais, 0)
    ) AS solde
FROM
    v_user_client u
    LEFT JOIN v_detail_montant_compte_client vdmcc ON u.id = vdmcc.user_id
    LEFT JOIN v_montant_recu_client vmrc ON u.id = vmrc.user_id;

-- 7. Création des index
CREATE INDEX idx_transactions_user ON transactions(user_id);
CREATE INDEX idx_transactions_destinataire ON transactions(destinataire_id);
CREATE INDEX idx_frais_operation ON frais(operation_id);

-- 8. Insertion des données de test

-- Operations
INSERT INTO operation (nom, code) VALUES 
('Dépôt', 'DEP'),
('Retrait', 'RET'),
('Transfert', 'TRA');

-- Préfixes autorisés
INSERT INTO num_prefixe_valable (prefix, actif) VALUES 
('034', 1),
('032', 1),
('033', 1);

-- Utilisateurs
INSERT INTO users (nom, prenom, role, numero, date_naissance, mot_de_passe) VALUES 
('Rabe', 'Jean', 'client', '0341234567', '1995-05-12', 'hash_pass_1'),
('Rakoto', 'Marie', 'client', '0329876543', '1998-11-23', 'hash_pass_2'),
('Randria', 'Paul', 'operateur', '0331122334', '1990-01-01', 'hash_pass_3');

-- Frais
INSERT INTO frais (operation_id, min, max, frais_val) VALUES 
((SELECT id FROM operation WHERE code = 'DEP'), 0, 1000000, 0),
((SELECT id FROM operation WHERE code = 'RET'), 1000, 50000, 1000),
((SELECT id FROM operation WHERE code = 'RET'), 50001, 500000, 2500),
((SELECT id FROM operation WHERE code = 'TRA'), 1000, 100000, 500),
((SELECT id FROM operation WHERE code = 'TRA'), 100001, 1000000, 1500);

-- Transactions
INSERT INTO transactions (user_id, operation_id, destinataire_id, montant, frais_montant, description) VALUES 
(
    (SELECT id FROM users WHERE numero = '0341234567'),
    (SELECT id FROM operation WHERE code = 'DEP'),
    NULL,
    100000,
    0,
    'Dépôt initial en agence'
);

INSERT INTO transactions (user_id, operation_id, destinataire_id, montant, frais_montant, description) VALUES 
(
    (SELECT id FROM users WHERE numero = '0341234567'),
    (SELECT id FROM operation WHERE code = 'TRA'),
    (SELECT id FROM users WHERE numero = '0329876543'),
    20000,
    500,
    'Envoi d''argent'
);

INSERT INTO transactions (user_id, operation_id, destinataire_id, montant, frais_montant, description) VALUES 
(
    (SELECT id FROM users WHERE numero = '0329876543'),
    (SELECT id FROM operation WHERE code = 'RET'),
    NULL,
    10000,
    1000,
    'Retrait d''espèces'
);


CREATE TABLE operateur (
    id     INTEGER PRIMARY KEY AUTOINCREMENT,
    nom    TEXT NOT NULL UNIQUE,
    a_nous INTEGER NOT NULL DEFAULT 0 CHECK (a_nous IN (0,1))
);
ALTER TABLE num_prefixe_valable ADD COLUMN operateur_id INTEGER REFERENCES operateur(id);

CREATE TABLE commission_autres (
    id           INTEGER PRIMARY KEY AUTOINCREMENT,
    operateur_id INTEGER NOT NULL REFERENCES operateur(id),
    date_debut   DATETIME NOT NULL,
    date_fin     DATETIME,
    pourcentage  NUMERIC NOT NULL
);

CREATE INDEX idx_commission_operateur ON commission_autres(operateur_id);

CREATE TABLE commission_transaction (
    id             INTEGER PRIMARY KEY AUTOINCREMENT,
    transaction_id INTEGER NOT NULL UNIQUE REFERENCES transactions(id),
    operateur_id   INTEGER NOT NULL REFERENCES operateur(id),
    montant_comm   NUMERIC NOT NULL
);

ALTER TABLE transactions ADD COLUMN num_dest TEXT;

CREATE VIEW solde_par_operateur AS
SELECT
    o.id AS operateur_id,
    o.nom,
    SUM(ct.montant_comm) AS total_commission
FROM commission_transaction ct
JOIN operateur o ON o.id = ct.operateur_id
GROUP BY o.id;

-- 1. Réinitialisation rapide des tables concernées (dans l'ordre pour les FK)
DELETE FROM commission_transaction;
DELETE FROM commission_autres;
DELETE FROM transactions;
DELETE FROM num_prefixe_valable;
DELETE FROM operateur;
DELETE FROM users;

-- 2. Insertion des Opérateurs
-- Note : 'NotreReseau' est notre propre réseau (a_nous = 1)
INSERT INTO operateur (id, nom, a_nous) VALUES 
(1, 'NotreReseau', 1),
(2, 'Telma', 0),
(3, 'Orange', 0),
(4, 'Airtel', 0);

-- 3. Association des Préfixes aux Opérateurs
INSERT INTO num_prefixe_valable (prefix, actif, operateur_id) VALUES 
('034', 1, 2), -- Telma
('032', 1, 3), -- Orange
('033', 1, 4), -- Airtel
('038', 1, 1); -- Notre réseau interne

-- 4. Configuration des Taux de Commission pour les Opérateurs Tiers
-- Ces taux seront appliqués lors des transferts vers des numéros externes
INSERT INTO commission_autres (operateur_id, date_debut, date_fin, pourcentage) VALUES 
(2, '2026-01-01 00:00:00', NULL, 1.50), -- 1.5% pour Telma
(3, '2026-01-01 00:00:00', NULL, 2.00), -- 2.0% pour Orange
(4, '2026-01-01 00:00:00', NULL, 1.00); -- 1.0% pour Airtel

-- 5. Insertion des Utilisateurs
INSERT INTO users (id, nom, prenom, role, numero, date_naissance, mot_de_passe) VALUES 
(1, 'Rabe', 'Jean', 'client', '0381234567', '1995-05-12', 'hash_pass_1'),    -- Client Interne
(2, 'Rakoto', 'Marie', 'client', '0389876543', '1998-11-23', 'hash_pass_2'),   -- Client Interne
(3, 'Randria', 'Paul', 'operateur', '0381122334', '1990-01-01', 'hash_pass_3');-- Agent / Opérateur

-- 6. Transactions de Test

-- A. Dépôt initial sur le compte de Jean (User 1) : +150 000
INSERT INTO transactions (id, user_id, operation_id, destinataire_id, num_dest, montant, frais_montant, description) VALUES 
(1, 1, (SELECT id FROM operation WHERE code = 'DEP'), NULL, '0381234567', 150000, 0, 'Dépôt d''ouverture de compte');

-- B. Transfert interne de Jean (User 1) vers Marie (User 2) : 30 000 (Pas de commission tiers)
INSERT INTO transactions (id, user_id, operation_id, destinataire_id, num_dest, montant, frais_montant, description) VALUES 
(2, 1, (SELECT id FROM operation WHERE code = 'TRA'), 2, '0389876543', 30000, 500, 'Transfert interne vers Marie');

-- C. Transfert externe de Jean (User 1) vers un numéro Orange (0321112233) : 50 000
INSERT INTO transactions (id, user_id, operation_id, destinataire_id, num_dest, montant, frais_montant, description) VALUES 
(3, 1, (SELECT id FROM operation WHERE code = 'TRA'), NULL, '0321112233', 50000, 500, 'Transfert vers Orange');

-- D. Commission générée pour la transaction C (Orange = 2.0% de 50 000 = 1 000)
INSERT INTO commission_transaction (transaction_id, operateur_id, montant_comm) VALUES 
(3, 3, 1000.00);

-- E. Transfert externe de Marie (User 2) vers un numéro Telma (0349998877) : 10 000
INSERT INTO transactions (id, user_id, operation_id, destinataire_id, num_dest, montant, frais_montant, description) VALUES 
(4, 2, (SELECT id FROM operation WHERE code = 'TRA'), NULL, '0349998877', 10000, 500, 'Transfert vers Telma');

-- F. Commission générée pour la transaction E (Telma = 1.5% de 10 000 = 150)
INSERT INTO commission_transaction (transaction_id, operateur_id, montant_comm) VALUES 
(4, 2, 150.00);


