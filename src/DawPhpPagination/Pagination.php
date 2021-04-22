<?php

namespace DawPhpPagination;

use DawPhpPagination\Contracts\PaginationInterface;
use DawPhpPagination\Support\Request\Request;

/**
 * Classe client.
 *
 * Pour générer une pagination.
 *
 * _Fonctionnement de ce package :
 * Pour générer le rendu,
 * la classe "Pagination" fait appelle à la classe "HtmlRenderer"
 * qui est une classe enfant de "RendererGenerator".
 *
 * @link     https://github.com/dev-and-web/daw-php-pagination
 * @author   Stephen Damian <contact@devandweb.fr>
 * @license  MIT License
 */
final class Pagination implements PaginationInterface
{
    /**
     * @var Request
     */
    private Request $request;

    /**
     * @var null|int
     */
    private ?int $getP = null;

    /**
     * @var null|int|string - Si 'all' dans URL, ça sera un string
     */
    private $getPP = null;

    /**
     * Nombre d'elements par page
     *
     * @var null|int
     */
    private ?int $perPage = null;

    /**
     * Nombre de pages total
     *
     * @var int
     */
    private int $nbPages;

    /**
     * Page en cours
     *
     * @var int
     */
    private int $currentPage;

    /**
     * Page départ
     *
     * @var int
     */
    private int $pageStart;

    /**
     * Page fin 
     *
     * @var int
     */
    private int $pageEnd;

    /**
     * Les options du <select>
     *
     * @var array
     */
    private array $arrayOptionsSelect = [];

    /**
     * OFFSET - à partir d'où on débute le LIMIT
     *
     * @var null|int
     */
    private ?int $offset;

    /**
     * LIMIT - nombre d'éléments à récupérer
     *
     * @var null|int
     */
    private ?int $limit;

    /**
     * Nombre d'éléments sur lesquels on effectue la pagination
     *
     * @var int
     */
    private int $count;

    /**
     * Nombre d'éléments par page par defaut
     *
     * @var int
     */
    private int $defaultPerPage;

    /**
     * Nombre de liens aux cotés de la page courante
     *
     * @var int
     */
    private int $numberLinks;

    /**
     * Classe CSS de la pagination
     *
     * @var string
     */
    private string $cssClassP;

    /**
     * Classe CSS du lien actif de la pagination
     *
     * @var string
     */
    private string $cssClassLinkActive;

    /**
     * ID CSS du "par page" de la pagination
     *
     * @var string
     */
    private string $cssIdPP;

    /**
     * @var HtmlRenderer
     */
    private HtmlRenderer $htmlRenderer;

    /**
     * @const string
     */
    const REGEX_INTEGER = '/^[0-9]+$/';

    /**
     * @const string
     */
    const ALL = 'all';
    
    /**
     * Pagination constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->request = new Request();

        if ($this->request->getGet()->has('page')) {
            $this->getP = (int) round($this->request->getGet()->get('page'));
        }

        if ($this->request->getGet()->has('pp')) {
            $this->getPP = $this->request->getGet()->get('pp');
        }

        $this->defaultPerPage = isset($options['pp']) && is_integer($options['pp']) ? $options['pp'] : 10;
        $this->numberLinks = isset($options['number_links']) && is_integer($options['number_links']) ? $options['number_links'] : 10;
        $this->cssClassP = $options['css_class_p'] ?? 'block-pagination';
        $this->cssClassLinkActive = $options['css_class_link_active'] ?? 'active';
        $this->cssIdPP = $options['css_id_pp'] ?? 'per-page';
        $this->arrayOptionsSelect = $options['options_select'] ?? [5, 10, 25, 50, 100];

        $this->htmlRenderer = new HtmlRenderer($this);
    }

    /**
     * Active la pagination
     *
     * @param int $count - Nombre d'éléments à paginer
     */
    public function paginate(int $count): void
    {
        $this->count = $count;

        $this->treatmentPerPage();
                        
        if ($this->perPage !== null) {
            $this->nbPages = ceil($this->count / $this->perPage);
        } else {
            $this->nbPages = 1;
        }

        if ($this->getP !== null && $this->getP > 0 && $this->getP <= $this->nbPages && preg_match(self::REGEX_INTEGER, $this->getP)) {
            $this->currentPage = $this->getP;
        } else {
            $this->currentPage = 1;
        }

        $this->setLimitAndSetOffset();
    }

    /**
     * Traitement du nombre d'éléments par page (pour <select>)
     */
    private function treatmentPerPage(): void
    {
        if ($this->getPP !== null && (preg_match(self::REGEX_INTEGER, $this->getPP) || $this->getPP == self::ALL)) {
            if (in_array($this->getPP, $this->arrayOptionsSelect)) {
                if ($this->getPP == self::ALL) {
                    $this->perPage = null;
                    $this->getP = 1;
                } else {
                    $this->perPage = (int) round($this->getPP);
                }
            } else {
                $this->perPage = $this->defaultPerPage;
            }
        } else {
            $this->perPage = $this->defaultPerPage;
        }
    }

    /**
     * Pour "setter" le limit et le offset
     */
    private function setLimitAndSetOffset(): void
    {
        if ($this->perPage === null) {
            $this->offset = null;
            $this->limit = null;
        } else {
            $this->offset = ($this->currentPage - 1) * $this->perPage;
            $this->limit = $this->perPage;
        }
    }

    /**
     * @return null|int - offset
     */
    public function getOffset(): ?int
    {
        return $this->offset;
    }

    /**
     * @return null|int - limit
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * @return int - Nombre total d'éléments sur lesquels on pagine
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @return int - Nombre d'éléments sur la page en cours
     */
    public function getCountOnCurrentPage(): int
    {
        if ($this->count < $this->perPage || $this->perPage === null) {
            return $this->count;
        } else {
            if ($this->hasMorePages()) {
                return $this->perPage;
            } else {
                return $this->getCountOnLastPage();
            }
        }
    }

    /**
     * Pour retourner l'indexation du premier élément sur la page en cours
     * Utile pour par exemple afficher : élement "nb start" à ...
     *
     * @return int
     */
    public function getFrom(): int
    {
        return $this->getFromTo()['from'];
    }

    /**
     * Pour retourner l'indexation du dernier élément sur la page en cours
     * Utile pour par exemple afficher : élement ... à "nb end"
     *
     * @return int
     */
    public function getTo(): int
    {
        return $this->getFromTo()['to'];    
    }

    /**
     * Pour retourner l'indexation du premier élément et l'indexation du deriner élément sur la page en cours
     * Utile pour par exemple afficher : élement "nb start" à "nb end" sur cette page
     *
     * @return array - Array associatif
     *    'from' => nb start
     *    'to' => nb end
     */
    private function getFromTo(): array
    {
        if ($this->count < $this->perPage || $this->perPage === null) {
            $start = 1;
            $end = $this->count;
        } else {
            if ($this->hasMorePages()) {
                $end = $this->perPage * $this->currentPage;
                $start = ($end - $this->perPage) + 1;
            } else {
                $endTest = $this->perPage * $this->currentPage;
                $start = ($endTest - $this->perPage) + 1;

                $end = $start + $this->getCountOnLastPage();
            }
        }

        return ['from' => $start, 'to' => $end];
    }

    /**
     * @return int - Le nombre d'éléments sur la dernière page
     */
    private function getCountOnLastPage(): int
    {
        $a = $this->perPage * $this->nbPages;
        $b = $a - $this->count;
        $c = $this->perPage - $b;

        return $c;
    }

    /**
     * @return int - Page en cours
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * @return int - Nombre de pages
     */
    public function getNbPages(): int
    {
        return $this->nbPages;
    }

    /**
     * @return int - Le nombre d'éléments affichés par page
     */
    public function getPerPage()
    {
        return ($this->perPage !== null) ? $this->perPage : '';
    }

    /**
     * @return int - Le nombre d'éléments affichés par page par defaut
     */
    public function getDefaultPerPage()
    {
        return ($this->defaultPerPage !== null) ? $this->defaultPerPage : '';
    }

    /**
     * @return null|int|string
     */
    public function getGetPP()
    {
        return $this->getPP;
    }

    /**
     * @return int
     */
    public function getPageStart(): int
    {
        return $this->pageStart;
    }

    /**
     * @return int
     */
    public function getPageEnd(): int
    {
        return $this->pageEnd;
    }

    /**
     * @return int
     */
    public function getNumberLinks(): int
    {
        return $this->numberLinks;
    }

    /**
     * @return string
     */
    public function getCssClassP(): string
    {
        return $this->cssClassP;
    }

    /**
     * @return string
     */
    public function getCssClassLinkActive(): string
    {
        return $this->cssClassLinkActive;
    }

    /**
     * @return string
     */
    public function getCssIdPP(): string
    {
        return $this->cssIdPP;
    }

    /**
     * @return array
     */
    public function getArrayOptionsSelect(): array
    {
        return $this->arrayOptionsSelect;
    }

    /**
     * @return bool - True si il reste des pages après celle en cours
     */
    public function hasMorePages(): bool
    {
        return $this->currentPage < $this->nbPages; 
    }

    /**
     * @return bool - True si on est sur la première page
     */
    public function isFirstPage(): bool
    {
        if ($this->request->getGet()->has('page')) {
            return $this->getP == 1; 
        }
        
        return true;
    }

    /**
     * @return bool - True si on est sur la dernière page
     */
    public function isLastPage(): bool
    {
        return $this->getP == $this->nbPages; 
    }

    /**
     * Rendre le rendu de la pagination au format HTML
     *
     * @param array|string|null $ifIssetGet - Si il y a déjà des GET dans l'URL, les cumuler avec les liens
     * @return string
     */
    public function render($ifIssetGet = null): string
    {
        $this->setPageStart()->setPageEnd();

        return $this->htmlRenderer->render($ifIssetGet);
    }

    /**
     * "Limiter le début". pageStart, les éventuels liens cliquables qui seront après la page en cours
     *
     * @return $this
     */
    private function setPageStart()
    {
        $firstPage = $this->currentPage - $this->numberLinks;

        if ($firstPage >= 1) {
            $this->pageStart = $firstPage;
        } else {
            $this->pageStart = 1;
        }

        return $this;
    }

    /**
     * "Limiter la fin". pageEnd, les éventuels liens cliquables qui seront avant la page en cours
     */
    private function setPageEnd()
    {
        $lastPage = $this->currentPage + $this->numberLinks;

        if ($lastPage <= $this->nbPages) {
            $this->pageEnd = $lastPage;
        } else {
            $this->pageEnd = $this->nbPages;
        }
    }

    /**
     * Rendre le rendu du per page au format HTML
     *
     * @param null|string $action - Pour l'action du form
     * @return string
     */
    public function perPage(string $action = null): string
    {
        return $this->htmlRenderer->perPage($this->request, $action);
    }
}
