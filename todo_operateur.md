## Mahatoky
### ajout de data test 
- prompt ia
### Operateur
#### [ok] cree les models
+ Users
+ Num_prefixe_valable
+ Operation
+ Transaction
+ Transaction_detail
+ Frais

#### [ok] configuration prefix
##### Vue
+ Page crud prefix
##### controleur
+ NumPrefixeValableController
  - POST : create
  - GET  : delete
  - GET  : list
  - GET  : modification

#### [ok] creation de type d operation
Création de frais d'opérations (dépôt,retrait,transfert) avec des barèmes de frais 
##### vue
+ list_operation (liste des operations)
+ list_frais_operation (liste des frais d une operation)
+ form_frais_operation (changer le montant min , max, val)
##### controller
+ OperationController
  - showListOperation
  - showFraisOperation
  - showFormOperation
  - updateFraisOperation
  - addFraisOperation(id_operation)

#### [ok] Situation gain via les différents frais ( retrait et transfert)
##### vue
+ [ok] gain_par_frais_operation
  - [ok] operation name / total de gain
#### controller
+ showGainPerOperation
  - [ok] model->getGaisOperation
  - [ok] afficher gain_par_frais_operaiton
#### sql
+ [ok] creer vue :
  - v_gain_per_operation (operation join (sum fais_transation group by id_operation))
#### model
+ OperationModel
+ findAllGainOperation (utiliser v_gain_per_operation)

### Situation des comptes clients [ok]
##### vue
+ [ok] situation_compte_client
  - [ok] liste des clients + montant du compte 
#### [ok] controller
+ [ok] UserControlleur
+ [ok] showSituaionCompte
  - model->getSituationCompte
  - afficher situation_compte_client
#### [ok] sql
+ creer vue :
  - [ok] v_user_client
  - [ok] v_transaction_and_type_operation
  - [ok] v_solde_client (sum (depo) - sum (retrer) - sum (montant transfer + frais trasfer))
#### model
+ [ok] UserModel
  + [ok] getSoldeCient (select * from v_solde client)

### ACTIVER CSRF