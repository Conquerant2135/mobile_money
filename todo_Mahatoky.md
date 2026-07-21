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

#### design 
- [ok] ajout css (via IA) de toute les page

## Version 2
### Client
- Option inclure frais de retrait lors de l’envoi
  - [en_cours] dans TransactionMdodel 
   - cree fonction makeRetraiWithFrais()
     - [ok] recuperer le frais
     - [ok] diminuer le montant a sauvgrarder du frais
     - [ok] sauvgarder le transfert
   - dans TransactionController
     - [ok] verifier si l lo option ajouter frais est coucher 
       - [ok] si oui apeller makeRretaisWihFrais()
   - [ok] dans vue ajouter checkbox ajouter frais
- Envoi multiple vers plusieurs numéros ( divisé le montant pour chaque numéro)
  - [ok] js selection multiple de numero dans vue
  - dans controleur de clientControleur
    - [ok] calculer le montant a varser a chaque compte (montant / nb_num)
    - [ok] recuperer le frais pour ce montant
    - [ok] calculer le frais total (mantant_unique*nb_num)
    - [ok] verifier si le solde du compte est soffisante (solde => frais_total + montant)
    - [ok] effectuer le transfer vers un comptes 
      - [ok] fonciton transfererArgent(montant,frais,id_user,numDest)
        - [ok] recuperer numDest
        - [ok] inseret transaction
        - [ok] sauvgarder transaction
#### todo
+ ajouter javascript insertion multiple dans transfer
+ [ou est le fichier controleur] recuperer les insertion multiple dans le controleur
+ pour chaque num faire insertion 

## config epargne
- chaque client a un pourcentage d epargghe
- ex : 20 , save base
- lors d une transfer vers le client , on ajoute une pourcentage de 20% vers mon compte preincipal , et 80% vers mon eparge 

## todo 
- config pourcentage
### [ok] base 
- epargne 
  - id client 
  - porucentage eparge
- mvt_eparge
  - id_client
  - montant

### model
- EpargneModel
  - fonction :
    - [ok] getPourcentage
    - save
    - [ok] effectuerEparge(numClient,montant)
      - calcule montant a eparger 
      - save mvt

- [enc] MvtEpargeModel
  - fonction :
    - save
    - getValeurEparge(clientId)

- TransfereModel :
  - transfererMonantVersNum 
    - appeler effectuerEparge(numClient (numDestinataire) ,montantVerser)

### Controleur 
- EpargneControler 
  - affichage form Eparge
    - verifier si chagement d eparge 
    - envoyer epargne actuel
    - envoyer valeur eparge (getValeurEparge)
  - config eparge (recup pourcentage , id user connecter)
    - save new eparge

### [ok] vue 
- [ok] form eparge 
- [ok] info eparge actuel