# Commande d'utilisation de l'API


1. Pour commencer, installez les dépendances sur votre machine en tapant dans votre terminal : **composer install**
2. Ensuite faire un dump de la base de données (création de la bd, chargement des données) : **composer prepare**
3. Lancer le server local dans le projet puis ouvrir l'application Postman
4. Sélectionnez la méthode POST et mettre l'url suivante pour s'authentifier : **http://127.0.0.1:8000/api/login_check**
5. Envoyez au format JSON la requete contenant un username (*test0@api.com* ou *name0*) avec **bonjour** comme password
6. Récupérer le token obtenu, sous l'onglet *authorization* , selectionner comme type *Bearer Token* et saisir le Token dans la zone désigné
7. Pour accéder à la liste des machines à présent,avec **GET** comme méthode, entrer l'url suivante : **http://127.0.0.1:8000/api/machines**. Pour créer un item, utilisez la meme url mais avec la méthode **POST**
8. Pour accéder à un item, entrer l'url suivante avec la meme méthode : **http://127.0.0.1:8000/api/machines/id**.
9. Pour mettre à jour tous les champs d'un item, tapez dans l'url suivante avec la méthode **PUT** et pour la suppression utilisez la méthode **DELETE**: **http://127.0.0.1:8000/api/machines/id**
