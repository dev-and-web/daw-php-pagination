# DAW PHP Pagination - English documentation

[![Latest Stable Version](https://poser.pugx.org/dev-and-web/daw-php-pagination/v/stable)](https://packagist.org/packages/dev-and-web/daw-php-pagination)
[![License](https://poser.pugx.org/dev-and-web/daw-php-pagination/license)](https://packagist.org/packages/dev-and-web/daw-php-pagination)

DAW PHP Pagination is a Open Source PHP library of a simple pagination.

*Paginate easily!*
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




### Requirements

* PHP >= 7.4






## * Summary *

* Introduction
* Installation
* Pagination instance methods
* Simple example
* Example with SQL queries
* Example with a list of files of a directory
* Add argument(s) to the instance
* Examples of using rendering methods
* Use Bootstrap CSS
* Set default configuration
* Examples of image results
* Contributing






## Introduction

This Open Source pagination contains PHP files, and one CSS style sheet.
You can edit them according to your needs.

This pagination also allows you to generate a "per page".
This will generate a form HTML tag with a select HTML tag and clickable options.






## Installation

Installation via Composer:
```
composer require dev-and-web/daw-php-pagination
```


If you do not use Composer to install this package,
you will have to manually "require" before using this package.
Example:
```php
<?php

require_once 'your-path/daw-php-pagination/src/DawPhpPagination/bootstrap/load.php';
```






## Methods

| Return type | Name | Description |
| ------- | -------------- | ----------- |
| void | __construct(array $options = []) | Constructeur |
| void | paginate(int $count) | Activate the pagination |
| int | getLimit() | LIMIT: Number of items to retrieve |
| int | getOffset() | From where you start the LIMIT |
| string | render() | Make the rendering of the pagination in HTML format |
| string | perPage() | Make the rendering of the per page in HTML format |
| int | getCount() | Number of elements on which we make the pagination |
| int | getCountOnCurrentPage() | Number of items on the current page |
| int | getFrom() | To return the indexing of the first item to the current page |
| int | getTo() | To return the indexing of the last item to the current page |
| int | getCurrentPage() | Current page |
| int | getNbPages() | Number of pages |
| int | getPerPage() | The number of items displayed per page |
| bool | hasMorePages() | True if there are pages after that current page |
| bool | isFirstPage () | True if the current page is the first page |
| bool | isLastPage () | True if the current page is the last page |






## Examples

### Simple example

```php
<?php

use DawPhpPagination\Pagination;

$pagination = new Pagination();

$pagination->paginate($countElements);

$limit = $pagination->getLimit();
$offset = $pagination->getOffset();

// Here your listing of elements with a loop

echo $pagination->render();
echo $pagination->perPage();
```




### Example with SQL queries

```php
<?php

use DawPhpPagination\Pagination;

// Count Elements of a table
function countElements() {
    $sql = "SELECT COUNT(*) AS nb FROM table";
    $query = db()->query($sql);
    $result = $query->fetch();
    
    return $result->nb;
}

// Collect the Elements
function findElements($limit, $offset) {
    $sql = "SELECT * FROM table LIMIT ? OFFSET ?";
    $query = db()->prepare($sql);
    $query->bindValue(1, $limit, PDO::PARAM_INT);
    $query->bindValue(2, $offset, PDO::PARAM_INT);
    $query->execute();

    return $query;
}

// Creating an object Pagination
$pagination = new Pagination();

// Paginate
$pagination->paginate(countElements());

$limit = $pagination->getLimit();
$offset = $pagination->getOffset();

// Show elements one by one that are retrieved from the database
foreach (findElements($limit, $offset) as $article) {
    echo htmlspecialchars($article->field);
}

// Show the Pagination
echo $pagination->render();
// Show a "per page" so that the visitor can have the choice of the number of elements to display per page
echo $pagination->perPage();
```
The function "db()" is a return of result of the database connection (PDO instance for example).
Depending on your needs, you can also use this library with your favorite ORM.




### Example with a list of files of a directory

```php
<?php

use DawPhpPagination\Pagination;

$scandir = scandir('your_path_upload');

$listFilesFromPath = [];
$count = 0;
foreach ($scandir as $f) {
    if ($f != '.' && $f != '..') {
        $listFilesFromPath[] = $f;
        $count++;
    }
}

// Creating an object Pagination
$pagination = new Pagination();

// Paginate
$pagination->paginate($count);

// Listing
$files = array_slice($listFilesFromPath, $pagination->getOffset(), $pagination->getLimit());

// Show files one by one
foreach ($files as $file) {
    echo $file;
}

// Show the Pagination
echo $pagination->render();
// Show a "per page" so that the visitor can have the choice of the number of elements to display per page
echo $pagination->perPage();
```




### Add argument(s) to the instance

When creating the Pagination object, you can add an options array at the instance constructor:
```php
<?php

// Number of Elements per page
$pagination = new Pagination(['pp'=>20]);
// Is 10 by default

// Number of links alongside the current page
$pagination = new Pagination(['number_links'=>5]);
// Is 4 by default

// The choice to select potentially generate with perPage()
$pagination = new Pagination(['options_select'=>[5, 10, 50, 100, 500, 'all']]);
// The value of 'options_select' must be a array.
// Only integers and 'all' is permitted.
// Value is [5, 10, 25, 50, 100] by default

// To change the CSS style of the pagination (to another CSS class as default)
$pagination = new Pagination(['css_class_p'=>'name-css-class-of-pagintion']);
// The CSS class name is by default "block-pagination"

// To change the CSS style of the active link of the pagination (to another CSS class as default)
$pagination = new Pagination(['css_class_link_active'=>'name-css-class-of-link-active']);
// The CSS class name is by default "active"

// To change the CSS style of a page (select) (to another CSS id as default)
$pagination = new Pagination(['css_id_pp'=>'name-css-id-of-per-page']);
// The CSS ID name is by default  "per-page"
```




### Examples of using rendering methods

To display the pagination:
```php
<?php

// return string
echo $pagination->render();

// If there are already parameters in request GET, we sometimes want "to accumulate" them with the links of the pagination.
// Here is the solution:

// For to accumulate one GET to Pagination link
echo $pagination->render('get');

// For to accumulate multi GET to Pagination links
echo $pagination->render(['get1', 'get2']);
```


To view a select so that the visitor can have the choice of the number of elements to display per page, this must be added:
```php
<?php

// return string
echo $pagination->perPage();

// In parameter of this method, you can specify the form action. Example:
echo $pagination->perPage('action_url');
// If you do not do it, default action will be $_SERVER['REQUEST_URI'].
```


To view the total number of items on which we paginate:
```php
<?php

// return int
echo $pagination->getCount();
```


To view number of elements in current page:
```php
<?php

// return int
echo $pagination->getCountOnCurrentPage();
```


To return first and last elements indexes on the current page (For example display: element "nb start" to "nb end" on this page):
```php
<?php

// Return int - indexing of the first element
echo $pagination->getFrom();

// Return int - indexing of the last element
echo $pagination->getTo();
```


To view the current page:
```php
<?php

// return int
echo $pagination->getCurrentPage();
```


To view number of pages:
```php
<?php

// return int
echo $pagination->getNbPages();
```


To view the number of elements shown per page:
```php
<?php

// return int
echo $pagination->getPerPage();
```


Return bool - True if there are still pages after the current one:
```php
<?php

// return bool
$pagination->hasMorePages();
```


Retourne bool - True if the current page is the first page :
```php
<?php

// return bool
$pagination->isFirstPage();
```


Retourne bool - True if the current page is the last page :
```php
<?php

// return bool
$pagination->isLastPage();
```





## Use Bootstrap CSS

To use the CSS style of Bootstrap, it is necessary to change the CSS class of the pagination. Example:

```php
<?php

$pagination = new Pagination(['css_class_p'=>'pagination']);
// The CSS class name is by default "block-pagination"
// We must put "pagination"
```






## Set default configuration

```php

<?php

use DawPhpPagination\Config\Config;

// Change the language - Is 'fr' by default. Supported: 'fr', 'en'
Config::set(['lang'=>'en']);
```






## Examples of image results

With DAW PHP Pagination CSS:

![DAW PHP Pagination example](https://www.devandweb.fr/medias/images/packages/daw-php-pagination-example.png)

With Bootstrap CSS:

![DAW PHP Pagination example with Bootstrap](https://www.devandweb.fr/medias/images/packages/daw-php-pagination-example-with-bootstrap.png)






## Contributing

### Bugs and security Vulnerabilities

If you discover a bug or a security vulnerability within DAW PHP Pagination, please send a message to Stephen. Thank you.
All bugs and all security vulnerabilities will be promptly addressed.




### License

The DAW PHP Pagination is Open Source software licensed under the MIT license.
