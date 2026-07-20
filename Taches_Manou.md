# Liste des taches

## V.0.5 (finir le truc d'abord ameliorer apres)

### Frontend

+ [ ] faire formulaire connection
  + numero seulement , si numero deja present prendre l'user sinon creer
+ [ ] faire formulaire operation (POST)

#### Fields

+ numero cible (a chercher quel est l'utilisateur si transfert)
+ choix operation (depot , retrait , transfert)
+ montant
+ description (si transfert)
+ date_op automatique

+ [ ] historique des operations : tableau affichage (filtre par date , operation et montant)
+ [ ] KPI du compte : solde actuel

### Backend

+ [ ] formulaire operation (on cherche le compte du numero cible si c'est un transfert)
  + verifier si le numero cible est valide suivant nos prefixe
  + chercher l'utilisateur du numero cible
  + calcul du montant du frais si retrait ou transfert (si transfert sur le compte de l'envoyeur)

+ [ ] fonction pour sortir le solde du compte
  + ecrire une requette SQL pour avoir la somme des retrait et des entree
+ [ ] fonction pour avoir le tableau historique
