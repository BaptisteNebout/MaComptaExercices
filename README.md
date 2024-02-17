# Macompta - Ecritures

Le but de ce projet est de créer des APIs dans le cadre d'une application web PHP de gestion d'écritures comptables.

## Desciption

Une écriture comptable est une transaction financière qui doit être en crédit ou en débit.

Elle est principalement composée d'un montant, d'un libellé, d'une date et d'un type d'opération.

Chaque écriture est rattaché à un compte.

Il faudra créer l'API REST permettant de pouvoir gérer ces écritures sur le principe d'un CRUD (Create / Update / Delete).

Il faudra en faire de même pour la ressource "comptes".

Elles devront être testées via INSOMNIA ou équivalent.

Un export des données INSOMNIA devra être fourni pour pouvoir facilement exécuter les appels. (format INSOMNIA, curl...)

## Prérequis

* PHP 7+
* Utilisation de SlimPHP ou équivalent (Lumen, Symfony, Laravel...)
* MySQL
* Git
* Pas d'utilisation d'un ORM (utilisation de requêtes SQL natives)
* Idéalement, respect des conventions PSR2. Fichier des rulesets joint. (voir l'outil phpcs pour plus de détail)

Le candidat peut utiliser les outils de son choix pour le développement en dehors des prérequis.
L'idée est d'utiliser la plateforme que le candidat connait le mieux. Il ne s'agit pas de juger de la connaissance d'un framework.

## Points d'évaluation

* Qualité du code
* Robustesse des contrôles

Il faudra travailler sur la branche main, et créer un commit pour chaque exercice: `git commit -m "Exercice 1: création de la table"`.

Une fois terminé, Il faudra zipper le projet final et l'envoyer par mail à epham@macompta.fr ou partager le projet via github.

Ne pas hésitez à me contacter par mail pour la moindre question. Il s'agit de se projeter comme s'il l'on collaborait ensemble et que ce projet est une mission.

## Implémentation

L'uuid n'est pas forcément connu à ce stade. Il s'agit d'un format pour gérer les identifiants (comme un auto-increment) mais avec un format spécifique. 
Il peut être gérer comme une string de 36 caractères au niveau base. Au niveau "usage", cela se traite comme n'importe quel identiifiant.

Pour la gestion des uuid, le package ramsey/uuid (https://github.com/ramsey/uuid) peut être utiliser : 

 use Ramsey\Uuid\Uuid;
 $uuid = Uuid::uuid4();

Toutes les opérations sont en général rattachés à un compte. Il faut donc que les endpoints commencent par identifier le compte.


Exemple : 

```
GET /comptes/{uuid}/ecritures
```

Il n'est cependant pas demander un système d'authentification. Il n'y a pas besoin d'implétention un système de session par exemple.
L'uuid du compte servant simplement de "filtre".

Pour les migrations, un simple fichier *.sql à lancer à la main peut suffire.

Sinon, le package utilisé chez macompta est : https://github.com/davedevelopment/phpmig

## Exercice 1

Créer la table "écritures", et pouvoir lancer une migration pour générer cette table.

La table "écritures" est composée des champs suivants:

```
| Nom du champ | Type | description |
|`uuid` | PRIMARY KEY  VARCHAR(36)| uuid de l'écriture |
|`compte_uuid` | PRIMARY KEY  VARCHAR(36)| uuid de du compte |
|`label` | VARCHAR 255 NOT NULL DEFAULT '' | Libellé de l'écriture |
|`date` | date NULL  | Date de l'écriture |
|`type` | Enum "C", "D" | Type d'opération "C" => Crédit, "D" => Débit |
|`amount` | double(14,2) NOT NULL DEFAULT 0.00 | Montant |
|`created_at` | timestamp NULL DEFAULT current_timestamp() | Date de création |
|`updated_at` | timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() | Date  de modification |
```

La table "comptes" est composée des champs suivants:

```
| Nom du champ | Type | description |
|`uuid` | PRIMARY KEY  VARCHAR(36)| uuid du compte |
|`login` | VARCHAR 255 NOT NULL DEFAULT | identifiant de connexion |
|`password` | VARCHAR(255) NOT NULL | mdp du compte
|`name` | nom du compte
|`created_at` | timestamp NULL DEFAULT current_timestamp() | Date de création |
|`updated_at` | timestamp NULL DEFAULT 
```


Créé une clé étrangère dans la table ecritures(compte_uuid) vers comptes(uuid) avec UPDATE RESTRICT et DELETE CASCADE.


## Exercice 2

Création d'un endpoint pour récupérer la liste des écritures pour **UN** compte sous ce format

```
GET /comptes/{uuid}/ecritures
```


```
Response

200
{
	"items" => [
		{ 
			
			label,
			[...]
		},
		{
			label,
			[...]
		}
		
	]
}
```

## Exercice 3

Création d'un endpoint pour l'ajout d'une ecriture **DANS UN** compte.

```
POST /comptes/{uuid}/ecritures
Body
{
	"label": "xxx",
	"date" : "dd/mm/yyyy",
	[...]
}
```

```
Response 201
{
	"uuid": "uuid qui vient d'être généré"
}
```

**Contraintes de validation:**

Le montant ne doit pas être négatif.
La date saisie doit être une date valide.


## Exercice 4

Création d'un endpoint pour modifier une écriture.
Dans le body devra être transmis systématiquement TOUS les champs. Pas seulement ceux qui doivent être modifiés.
```
PUT /comptes/{uuid}/ecritures/{uuid}
Body
{
	"uuid": "eee"
	"label": "xxx"
	[...]
	""
}
```

Reponse 204

**Contraintes de validation:**

Les mêmes contraintes de validation s'appliquent que dans la création.

## Exercice 5

Bon ben, pas de surpise, on supprime !

```
DELETE /comptes/{uuid}/ecritures/{uuid}
```

```
Response 204
```




## Exercice 6,7,8,9

Même chose que pour écritures mais pour les comptes : GET , POST, PUT, DELETE.
```
GET /comptes/{uuid}
POST /comptes
PUT /comptes/{uuid}
DELETE /comptes/{uuid}
```

**Contraintes de validation:**

login / password obligatoire à la création. 
un compte avec ecritures ne peut être supprimé.
login NON modifiable.



## Exercice 10

Endpoint pour récupérer la liste de TOUS les comptes avec ses écritures.

Le format de sortie est à définir par le candidat mais doit être proche des deux endpoints GET précédent.

La méthode pour récupérer les données devra être optimisé. S'il y a un grand nombre de données, cela devra avoir peu d'impact sur la requête.

---------------------------------------------------------------------------------------------------------------------------------------------------

**Environnement de dev**
- win 11
- WampServer 3.3.2 
- Composer 2.7.1
- Laravel 10
- MySQL
- PHP 8.2.13
- Postman v10.23
- GitHub

**Commandes utiles**
initialisation du projet:
- cd maCompta-exercices
- composer install

Création de la DB et alimentation :
- php artisan migrate
- php artisan db:seed --class=CompteSeeder
- php artisan db:seed --class=EcritureSeeder