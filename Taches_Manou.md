# Liste des taches

## V.0.5 (finir le truc d'abord ameliorer apres)

### Frontend

+ [x] faire formulaire connection
  + numero seulement , si numero deja present prendre l'user sinon creer
+ [x] faire formulaire operation (POST)

#### Fields

+ numero cible (a chercher quel est l'utilisateur si transfert)
+ choix operation (depot , retrait , transfert)
+ montant
+ description (si transfert)
+ date_op automatique

+ [x] historique des operations : tableau affichage (filtre par date , operation et montant)
+ [x] KPI du compte : solde actuel
+ [x] Verification si solde suffisant pour transfert ou retrait

### Backend

+ [x] formulaire operation (on cherche le compte du numero cible si c'est un transfert)
  + verifier si le numero cible est valide suivant nos prefixe
  + chercher l'utilisateur du numero cible
  + calcul du montant du frais si retrait ou transfert (si transfert sur le compte de l'envoyeur)

+ [x] fonction pour avoir le tableau historique
+ [x] fonction pour sortir le solde du compte
  + ecrire une requette SQL pour avoir la somme des retrait et des entree

## V2

Objectif : ajouter les prefixe des autres operateurs et taxes sur les frais suivant les operateurs

### Prefixe des autres operateurs

+ [x] ajouter table operateur
  + id int
  + nom varchar
  + a_nous boolean
+ [x] ajouter colonne id_operateur sur num_prefixe_valable

### Commission vers les autres operateurs

+ [x] ajouter colonne num_dest sur la table transaction pour quand les transfert se font vers l'exterieur
+ [x] creer table commission_autres lors de transfert vers autre operateur
  + id
  + dateheuredebut
  + dateheurefin nullable
  + pourcentage
  + operateur_id

+ [ ] creer table solde operateur (pour contenir les gains des operateurs sur les transactions de transfert)
  + transaction_id (reference de l'operation)
  + montant_comm
