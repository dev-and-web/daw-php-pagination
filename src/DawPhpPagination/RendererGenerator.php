<?php

namespace DawPhpPagination;

use DawPhpPagination\Contracts\PaginationInterface;
use DawPhpPagination\Contracts\Support\Request\RequestInterface;
use DawPhpPagination\Config\Lang;
use DawPhpPagination\Support\String\Str;
use DawPhpPagination\Support\Facades\Server;

/**
 * Rendu de la pagination
 *
 * @link     https://github.com/dev-and-web/daw-php-pagination
 * @author   Stephen Damian <contact@devandweb.fr>
 * @license  MIT License
 */
abstract class RendererGenerator
{
    /**
     * La variable de chaîne de requête utilisée pour stocker la page
     *
     * @const string
     */
    const PAGE_NAME = 'page';

    /**
     * La variable de chaîne de requête utilisée pour stocker le per page
     *
     * @const string
     */
    const PER_PAGE_NAME = 'pp';

    /**
     * La classe CSS de Bootstrap (pour rendre 100% compatible liens de la pagination avec Bootstrap)
     *
     * @const string
     */ 
    const CSS_CLASS_BOOTSRPAP = 'pagination';

    /**
     * @var PaginationInterface
     */
    protected PaginationInterface $pagination;

    /**
     * Sera array ou string - pour cumuler les éventuels GET avec le render et le perPage
     *
     * @var array|string
     */
    private $accumulateIfHasQueryParams;

    /**
     * @var string - Pour récupérer langue
     */
    protected $langPagination;

    /**
     * RenderGenerator constructor.
     *
     * @param PaginationInterface $pagination
     */
    final public function __construct(PaginationInterface $pagination)
    {
        $this->pagination = $pagination;

        $this->langPagination = Lang::getInstance()->pagination(); 
    }

    /**
     * Pour afficher la pagination
     *
     * @param array|string|null $ifIssetGet - Si il y a déjà des GET dans l'URL, les cumuler avec les liens
     * @return string
     */
    final public function render($ifIssetGet = null): string
    {
        $html = '';

        $andIfHasQueryParams = $this->accumulateIfHasQueryParams($ifIssetGet);

        if ($this->pagination->getGetPP() != Pagination::ALL && $this->pagination->getCount() > $this->pagination->getPerPage()) {
            $html .= $this->open();

            $html .= $this->previousLink(Str::andIfHasQueryParams($andIfHasQueryParams));
            $html .= $this->firstLink(Str::andIfHasQueryParams($andIfHasQueryParams));

            for ($i = $this->pagination->getPageStart(); $i <= $this->pagination->getPageEnd(); $i++) {
                if ($i == $this->pagination->getCurrentPage()) {
                    $html .= $this->paginationActive($i);
                } else {
                    if ($i != 1 && $i != $this->pagination->getNbPages()) {
                        $html .= $this->paginationLink($i.Str::andIfHasQueryParams($andIfHasQueryParams), $i);
                    }
                }
            }

            $html .= $this->lastLink(Str::andIfHasQueryParams($andIfHasQueryParams));
            $html .= $this->nextLink(Str::andIfHasQueryParams($andIfHasQueryParams));

            $html .= $this->close();
        }

        return $html;
    }

    /**
     * Pour cumuler les éventuel GET
     *
     * @param array|string - $ifIssetGet
     * @return array - les éventuels paramètres déjàs en GET à cumuler avec pagination
     */
    private function accumulateIfHasQueryParams($ifIssetGet): array
    {
        if ($ifIssetGet != null) {
            $this->accumulateIfHasQueryParams = $ifIssetGet;  // pour perPage

            $andIfHasQueryParams = [self::PER_PAGE_NAME];  // pour render
            if (is_array($this->accumulateIfHasQueryParams)) {
                foreach ($this->accumulateIfHasQueryParams as $oneGet) {
                    $andIfHasQueryParams[] = $oneGet;
                }
            } else {
                $andIfHasQueryParams[] = $ifIssetGet;
            }

        } else {
            $andIfHasQueryParams = [self::PER_PAGE_NAME];
        }

        return $andIfHasQueryParams;
    }

    /**
     * Pour choisir nombre d'éléments à afficher par page
     *
     * @param RequestInterface $request
     * @param null|string $action - Pour l'action du form
     * @return string
     */
    final public function perPage(RequestInterface $request, string $action = null): string
    {
        $html = '';

        if ($this->pagination->getCount() > 5 && $this->pagination->getCount() > $this->pagination->getDefaultPerPage()) {
            $actionPerPage = $action != null ? $action : Server::getRequestUri();

            $onChange = !$request->isAjax() ? $this->perPageOnchange() : '';

            $html .= $this->perPageOpenForm($actionPerPage);
            $html .= $this->perPageLabel();
            $html .= $this->perPageInputHidden();
            $html .= $this->perPageOpenSelect($onChange);    

            foreach ($this->pagination->getArrayOptionsSelect() as $valuePP) {
                $html .= $this->generateOption($valuePP);
            }

            $html .= $this->perPageCloseSelect();

            if ($this->accumulateIfHasQueryParams != null) {
                $html .= Str::inputHiddenIfHasQueryParams($this->accumulateIfHasQueryParams);
            }

            $html .= $this->perPageCloseForm();
        }

        return $html;
    }

    /**
     * @param string $valuePP
     * @return string
     */
    private function generateOption(string $valuePP): string
    {
        $html = '';

        $selectedPP = ($valuePP == $this->pagination->getGetPP())
            ? 'selected'
            : '';
        $selectedDefault = ($this->pagination->getGetPP() == null && $valuePP == $this->pagination->getDefaultPerPage())
            ? 'selected'
            : '';

        if (
            $this->pagination->getCount() > $valuePP &&
            $valuePP != 5 &&
            $valuePP != $this->pagination->getDefaultPerPage() &&
            $valuePP != Pagination::ALL
        ) {
            $html .= $this->perPageOption($selectedDefault.$selectedPP, $valuePP);
        } elseif ($valuePP == 5 || $valuePP == $this->pagination->getDefaultPerPage() || $valuePP == Pagination::ALL) {
            if ($valuePP == Pagination::ALL) {
                $html .= $this->perPageOption($selectedDefault.$selectedPP, $valuePP, $this->langPagination[Pagination::ALL]);
            } else {
                $html .= $this->perPageOption($selectedDefault.$selectedPP, $valuePP);
            }
        }

        return $html;
    }
}
