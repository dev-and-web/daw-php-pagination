# DAW PHP Pagination - Library for paginate

[![Latest Stable Version](https://poser.pugx.org/dev-and-web/daw-php-pagination/v/stable)](https://packagist.org/packages/dev-and-web/daw-php-pagination)
[![License](https://poser.pugx.org/dev-and-web/daw-php-pagination/license)](https://packagist.org/packages/dev-and-web/daw-php-pagination)

DAW PHP Pagination is a Open Source PHP library of a simple pagination.

*Paginate easily!*




### Author

Package developed by:
[Développeur PHP Freelance](https://www.devandweb.fr/freelance/developpeur-php)
[![Developpeur Freelance](https://www.devandweb.fr/medias/app/website/developpeur-web.png)](https://www.devandweb.fr)




### Simple example

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




### Documentation

* The documentation is in folder "docs" of this package:

[English](https://github.com/dev-and-web/daw-php-pagination/tree/3.0/docs/en/doc.md)
|
[French](https://github.com/dev-and-web/daw-php-pagination/tree/3.0/docs/fr/doc.md)




## Installation

Installation via Composer:
```
composer require dev-and-web/daw-php-pagination
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
