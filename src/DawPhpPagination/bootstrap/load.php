<?php

/**
 * Si le package n'a pas été téléchargé avec Composer, il faut "require" manuellement ce fichier
 */

require_once __DIR__.'/../Contracts/Config/ConfigInterface.php';

require_once __DIR__.'/../Contracts/PaginationInterface.php';

require_once __DIR__.'/../Contracts/Support/Request/RequestInterface.php';

require_once __DIR__.'/../Config/SingletonConfig.php';

require_once __DIR__.'/../Exception/PaginationException.php';

require_once __DIR__.'/../Support/Request/Bags/ParameterBag.php';

require_once __DIR__.'/../Support/Request/Input.php';

require_once __DIR__.'/../Support/Request/Request.php';

require_once __DIR__.'/../Support/Request/Server.php';

require_once __DIR__.'/../Support/Facades/Facade.php';

require_once __DIR__.'/../Support/Facades/Input.php';

require_once __DIR__.'/../Support/Facades/Server.php';

require_once __DIR__.'/../Support/String/Str.php';

require_once __DIR__.'/../Config/Config.php';

require_once __DIR__.'/../Config/Lang.php';

require_once __DIR__.'/../Pagination.php';

require_once __DIR__.'/../RendererGenerator.php';

require_once __DIR__.'/../HtmlRenderer.php';
