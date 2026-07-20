# Conception de database

## Users

+ id int AUTO
+ nom varchar
+ prenom varchar
+ role enum ('client' , 'operateur')
+ numero unique varchar
+ date_naissance
+ created_at

## Num_prefixe_valable (ex : 033 ou 034)

+ id int AUTO
+ prefix varchar

## Operation

+ id int AUTO
+ nom varchar
+ code varchar

(depot , retrait , transfert)

## Transaction

+ id int AUTO
+ user_id fk ref users(id)
+ date_op datetime
+ operation_id
+ description (si transfert)

## Transaction_detail

+ id int AUTO
+ transaction_id fk ref Transaction(id)
+ is_frais boolean ou tiniint 0 ou 1 
+ num_id
+ montant

## Frais

+ id
+ min
+ max
+ frais_val