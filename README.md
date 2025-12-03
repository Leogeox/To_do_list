<h1>Todo List App</h1>
Quentin DEGLAS, Anisse EL BEZAZI, Lucas AUDOUBERT, Julien CLAVIER, Léo VIGIN
<br>
<h2>Notre théme</h2>
Il s’agit d’une application de liste de tâches permettant à un utilisateur de créer un compte, de se connecter, puis de créer et modifier ses propres tâches (nom, description et statut). Ces tâches sont associées à son compte.
<br>
<h3>Instructions</h3>
Cloner le projet : 

``` php
git clone https://github.com/Leogeox/To_do_list.git
```
Télécharger les dépendences : 

``` php
composer install
composer require symfony/runtime
// Si besoin.
```
Créer la BDD (Base de Données) :

``` php
// Modifier DATABASE_URL dans .env si besoin.
// Exemple : DATABASE_URL="mysql://db_login:db_password@localhost:3306/db_name"

php bin/console doctrine:database:create todo
// Si la commande ne fonctionne pas, créer la base de données manuellement.
php bin/console make:migration
php bin/console doctrine:migrations:migrate (??)
```

<h2>Les URL des pages suivantes</h2>
-  Créer son compte : /user/register <br>
-  Se connecter a son compte : /user/login <br>
-  Voir/Update & Delete ses taches : /task
