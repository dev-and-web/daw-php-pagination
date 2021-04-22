# DAW PHP Pagination - Documentation Française

[![Latest Stable Version](https://poser.pugx.org/dev-and-web/daw-php-pagination/v/stable)](https://packagist.org/packages/dev-and-web/daw-php-pagination)
[![License](https://poser.pugx.org/dev-and-web/daw-php-pagination/license)](https://packagist.org/packages/dev-and-web/daw-php-pagination)

DAW PHP Pagination est une librairie PHP Open Source d'une simple pagination.

*Paginez facilement !*
```php
<?php

$pagination = new Pagination();

$pagination->paginate($countElements);

$limit = $pagination->getLimit();
$offset = $pagination->getOffset();

// Here your listing of elements with a loop

echo $pagination->render();
echo $pagination->perPage();
```




### Pré-requis

* PHP >= 7.4






## * Sommaire *

* Introduction
* Installation
* Méthodes d'instance de la Pagination
* Simple exemple
* Exemple avec des requêtes SQL
* Exemple avec une liste de fichiers d'un répertoire
* Ajouter argument(s) à l'instance
* Exemples d'utilisation des méthodes de rendu
* Utiliser Bootstrap CSS
* Modifier la configuration par défaut
* Exemples de résultat en image
* Contribuer






## Introduction

Cette pagination Open Source est composée de fichiers PHP, et d'une feuille de style CSS.
Vous pouvez bien sûr styliser le CSS en fonction de vos envies.

Cette pagination vous permet aussi de générer un "par page".
Ceci générera une balise HTML form avec une balise HTML select et des options cliquables.






## Installation

Installation via Composer :
```
composer require dev-and-web/daw-php-pagination
```


Si vous n'utilisez pas Composer pour installer ce package,
vous deverez faire le "require" manuellement avant d'utiliser ce package.
Exemple :
```php
<?php

require_once 'your-path/daw-php-pagination/src/DawPhpPagination/bootstrap/load.php';
```






## Méthodes

| Type de retour | Nom | Description |
| ------- | -------------- | ----------- |
| void | __construct(array $options = []) | Constructeur |
| void | paginate(int $count) | Active la pagination |
| int | getLimit() | LIMIT : Nombre d'éléments à récupérer |
| int | getOffset() | À partir d'où on débute le LIMIT |
| string | render() | Rendre le rendu de la pagination au format HTML |
| string | perPage() | Rendre le rendu du par page au format HTML |
| int | getCount() | Nombre d'éléments sur lesquels paginer |
| int | getCountOnCurrentPage() | Nombre d'éléments sur la page en cours |
| int | getFrom() | Pour retourner l'indexation du premier élément sur la page en cours |
| int | getTo() | Pour retourner l'indexation du dernier élément sur la page en cours |
| int | getCurrentPage() | Page en cours |
| int | getNbPages() | Nombre de pages |
| int | getPerPage() | Le nombre d'éléments affichés par page |
| bool | hasMorePages() | True si il reste des pages après celle en cours |
| bool | isFirstPage() | True si la page en cours est la première page |
| bool | isLastPage() | True si la page en cours est la dernière page |






## Exemples

### Exemple simple

```php
<?php

use DawPhpPagination\Pagination;

$pagination = new Pagination();

$pagination->paginate($countElements);

$limit = $pagination->getLimit();
$offset = $pagination->getOffset();

// Ici votre listage d'éléments avec une boucle

echo $pagination->render();
echo $pagination->perPage();
```




### Exemple avec des requetes SQL

```php
<?php

use DawPhpPagination\Pagination;

// Récupérer nombre d'elements d'une table
function countElements() {
    $sql = "SELECT COUNT(*) AS nb FROM table";
    $query = db()->query($sql);
    $result = $query->fetch();
    
    return $result->nb;
}

// Récupérer les éléments
function findElements($limit, $offset) {
    $sql = "SELECT * FROM table LIMIT ? OFFSET ?";
    $query = db()->prepare($sql);
    $query->bindValue(1, $limit, PDO::PARAM_INT);
    $query->bindValue(2, $offset, PDO::PARAM_INT);
    $query->execute();

    return $query;
}

// Création d'un objet Pagination
$pagination = new Pagination();

// Paginer
$pagination->paginate(countElements());

$limit = $pagination->getLimit();
$offset = $pagination->getOffset();

// Afficher les éléments un par un qui sont récupérés de la BDD
foreach (findElements($limit, $offset) as $article) {
    echo htmlspecialchars($article->field);
}

// Afficher la Pagination
echo $pagination->render();
// Show a select so that the user can choose the number of items to display per page
echo $pagination->perPage();
```
La fonction "db()" doit retourner le résultat de la connexion à une base de donnée (le résultat d'une instance de PDO par exemple).
Mais vous n'êtes pas obligé de créer un fonction "db()", vous pouvez utiliser cette librairie avec un ORM par exemple.




### Exemple avec une liste de fichiers d'un répertoire

```php
<?php

use DawPhpPagination\Pagination;

$scandir = scandir('votre_dossier_d_upload');

$listFilesFromPath = [];
$count = 0;
foreach ($scandir as $f) {
    if ($f != '.' && $f != '..') {
        $listFilesFromPath[] = $f;
        $count++;
    }
}

// Création d'un objet Pagination
$pagination = new Pagination();

// Paginer
$pagination->paginate($count);

// Liste
$files = array_slice($listFilesFromPath, $pagination->getOffset(), $pagination->getLimit());

// Afficher les fichiers un par un
foreach ($files as $file) {
    echo $file;
}

// Afficher la Pagination
echo $pagination->render();
// Show a select so that the user can choose the number of items to display per page
echo $pagination->perPage();
```




### Ajouter argument(s) à l'instance

Lors de la création de l'objet Pagination, vous pouvez passer un tableaux d'options au constructeur de l'instance :
```php
<?php

// Nombre d'éléments par page
$pagination = new Pagination(['pp'=>20]);
// Est à 10 par défaut

// Nombre de liens aux cotés de la page courante
$pagination = new Pagination(['number_links'=>5]);
// Est à 4 par défaut

// The otpions of the select TAG to be possibly generated with perPage()
$pagination = new Pagination(['options_select'=>[5, 10, 50, 100, 500, 'all']]);
// La valeur de 'options_select' doit être un tableaux.
// Seul des nombres entiers et 'all' sont autorisés.
// La valeur est [5, 10, 25, 50, 100] par défaut

// Pour changer le style CSS de la pagination (mettre une autre class CSS que celle par défaut)
$pagination = new Pagination(['css_class_p'=>'name-css-class-of-pagintion']);
// La class CSS se nomme par défaut "block-pagination"

// Pour changer le style CSS du lien actif de la pagination (mettre une autre class CSS que celle par défaut)
$pagination = new Pagination(['css_class_link_active'=>'name-css-class-of-link-active']);
// La class CSS se nomme par défaut "active"

// Pour changer le style CSS du par page (select) (mettre un autre id CSS que celui par défaut)
$pagination = new Pagination(['css_id_pp'=>'name-css-id-of-per-page']);
// L'ID CSS se nomme par défaut "per-page"
```




### Exemples d'utilisation des méthodes de rendu

Pour afficher la pagination :
```php
<?php

// return string
echo $pagination->render();

// Si il y a déjà des paramètres envoyés en GET, on a parfois envie des les "cumuler" avec les liens de la pagination.
// Voici la solution :

// Pour "cumuler" un GET aux liens de Pagination
echo $pagination->render('get');

// Pour "cumuler" des GET aux liens de Pagination
echo $pagination->render(['get1', 'get2']);
```


Pour afficher un select pour que le visiteur puisse avoir le choix du nombre d'éléments à afficher par page, il faut ajouter ceci :
```php
<?php

// return string
echo $pagination->perPage();

// En paramètre de cette méthode, on peut préciser l'action du formulaire. Exemple :
echo $pagination->perPage('action_url');
// Si vous ne le faite pas, l'action prendra par défaut comme valeur $_SERVER['REQUEST_URI'].
```


Pour afficher le nombre total d'éléments sur lesquels on pagine :
```php
<?php

// return int
echo $pagination->getCount();
```


Pour afficher le nombre d'éléments de la page en cours :
```php
<?php

// return int
echo $pagination->getCountOnCurrentPage();
```


Pour retourner l'indexation du premier élément et l'indexation du dernier élément sur la page en cours (utile pour par exemple afficher : élément "nombre_X" à "nombre_X" sur cette page) :
```php
<?php

// return int - L'indexation du premier élément sur la page en cours
echo $pagination->getFrom();

// return int - L'indexation du dernier élément sur la page en cours
echo $pagination->getTo();
```


Pour afficher la page en cours :
```php
<?php

// return int
echo $pagination->getCurrentPage();
```


Pour afficher nombre de pages :
```php
<?php

// return int
echo $pagination->getNbPages();
```


Pour afficher le nombre d'éléments qui sont affichés par page :
```php
<?php

// return int
echo $pagination->getPerPage();
```


Retourne bool - True si il reste des pages après celle en cours :
```php
<?php

// return bool
$pagination->hasMorePages();
```


Retourne bool - True si la page en cours est la première page :
```php
<?php

// return bool
$pagination->isFirstPage();
```


Retourne bool - True si la page en cours est la dernière page :
```php
<?php

// return bool
$pagination->isLastPage();
```





## Utiliser Bootstrap CSS

Pour utiliser le style CSS de Bootstrap, il faut changer la class CSS de la pagination. Exemple :

```php
<?php

$pagination = new Pagination(['css_class_p'=>'pagination']);
// La class CSS se nomme par défaut "block-pagination"
// Il faut lui mettre "pagintion"
```






## Modifier la configuration par défaut

```php

<?php

use DawPhpPagination\Config\Config;

// Changer la langue. Est à 'fr' par défaut. 'fr' et 'en' sont supportés
Config::set(['lang'=>'en']);
```






## Exemples de résultat en image

Avec le CSS de DAW PHP Pagination :

![DAW PHP Pagination exemple](https://www.devandweb.fr/medias/images/packages/daw-php-pagination-example.png)

Avec le CSS de Bootstrap :

![DAW PHP Pagination exemple avec Bootstrap](https://www.devandweb.fr/medias/images/packages/daw-php-pagination-example-with-bootstrap.png)






## Contribuer

### Bugs et vulnérabilités de sécurité

Si vous découvrez un bug ou une faille de sécurité au sein de DAW PHP Pagination, merci d'envoyez message à Steph.
Tout les bugs et failles de sécurité seront traitées rapidement.




### Licence

DAW PHP Pagination est une librarie Open Source sous la licence MIT.
