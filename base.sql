-- 1. Désactiver les contraintes de clés étrangères pour exécuter les DROP sans conflit
PRAGMA foreign_keys = OFF;

-- 2. Suppression des tables existantes (pour réinitialiser la base)
DROP TABLE IF EXISTS transactions;
DROP TABLE IF EXISTS frais;
DROP TABLE IF EXISTS operation;
DROP TABLE IF EXISTS num_prefixe_valable;
DROP TABLE IF EXISTS users;

-- 3. Réactivation des contraintes de clés étrangères
PRAGMA foreign_keys = ON;

-- 4. Création des tables
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

-- 5. Création des index
CREATE INDEX idx_transactions_user ON transactions(user_id);
CREATE INDEX idx_transactions_destinataire ON transactions(destinataire_id);
CREATE INDEX idx_frais_operation ON frais(operation_id);

-- 6. Insertion des données de test

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