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
+ [ ] Verification si solde suffisant pour transfert ou retrait

### Backend

+ [x] formulaire operation (on cherche le compte du numero cible si c'est un transfert)
  + verifier si le numero cible est valide suivant nos prefixe
  + chercher l'utilisateur du numero cible
  + calcul du montant du frais si retrait ou transfert (si transfert sur le compte de l'envoyeur)

+ [ ] fonction pour avoir le tableau historique (WIP)
+ [ ] fonction pour sortir le solde du compte
  + ecrire une requette SQL pour avoir la somme des retrait et des entree
