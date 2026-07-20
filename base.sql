PRAGMA foreign_keys = ON;

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

CREATE INDEX idx_transactions_user ON transactions(user_id);
CREATE INDEX idx_transactions_destinataire ON transactions(destinataire_id);
CREATE INDEX idx_frais_operation ON frais(operation_id);