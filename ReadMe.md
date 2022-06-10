# Commande d'utilisation de l'API


1. Pour commencer, installez les dépendances sur votre machine en tapant dans votre terminal : **composer install**.
2. Ensuite faire un dump de la base de données (création de la bd, chargement des données) : **composer prepare**.
3. Lancer le server local avec la commande **php bin/console server:run** dans le projet puis ouvrir l'application Postman.
4. Sélectionnez la méthode POST et mettre l'url suivante pour s'authentifier : **http://127.0.0.1:8000/api/login** ou **http://127.0.0.1:8000/api/register** afin de pouvoir s'enregistrer sur notre API.
5. Envoyez au format JSON la requete contenant un username (clé) (*test0@api.com*) avec **bonjour** comme password.
6. Récupérer le token obtenu, sous l'onglet *authorization* , selectionnez comme type *Bearer Token* et saisir le Token dans la zone désignée.
7. Pour accéder à la liste des machines d'un utilisateur,on le fait par le biais de la méthode **GET** à l'adresse **http://127.0.0.1:8000/api/all_user_machines** et **http://127.0.0.1:8000/api/machines** pour obtenir une liste de toutes les machines existantes dans notre API.
8. Pour créer un item, utilisez l'url **http://127.0.0.1:8000/api/create_machine** mais avec la méthode **POST**.
9. Pour accéder à un item, entrer l'url suivante avec la méthode **GET** : **http://127.0.0.1:8000/api/machines/id**.
10. Pour supprimer un item, utilisez en méthode **DELETE** l'url **http://127.0.0.1:8000/api/delete_machine/id** (id correspondant au numéro de la machine à supprimer). 
11. Pour mettre à jour tous les champs d'un item, tapez dans l'url suivante avec la méthode **PUT** : **http://127.0.0.1:8000/api/update_data_machine/id**.


# Autres commandes utiles

=> Liste de toutes les routes de l'API : **php bin/console debug:router**