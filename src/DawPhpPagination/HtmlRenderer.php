<?php

namespace DawPhpPagination;

/**
 * Rendu HTML de la pagination
 *
 * @link     https://github.com/dev-and-web/daw-php-pagination
 * @author   Stephen Damian <contact@devandweb.fr>
 * @license  MIT License
 */
final class HtmlRenderer extends RendererGenerator
{
    /**
     * @return string
     */
    protected function open(): string
    {
        return '<div class="center"><ul class="'.$this->pagination->getCssClassP().'">';
    }

    /**
     * Si on est pas à la 1è page, faire apparaitre : la flèche gauche (page précédante)
     *
     * @param string $ifIssetGet - Si il y a déjà des GET dans l'URL, les cumuler avec les liens
     * @return string
     */
    protected function previousLink(string $ifIssetGet): string
    {
        $html = '';

        if ($this->pagination->getCssClassP() !== self::CSS_CLASS_BOOTSRPAP) {
            $condition = $this->pagination->getCurrentPage() != 1;
            $title = ' title="'.$this->langPagination['previous'].'"';
            $cssClass = '';
        } else {
            $condition = true;
            $title = '';
            $cssClass = $this->pagination->isFirstPage() ? ' class="disabled"' : '';
        }

        if ($condition) {
            $href = 'href="?'.self::PAGE_NAME.'='.($this->pagination->getCurrentPage() - 1).''.$ifIssetGet.'"';

            $html .= '<li'.$cssClass.'><a rel="prev"'.$title.' '.$href.'>';
            $html .= '&laquo;';
            $html .= '</a></li>';
        }

        return $html;
    }

    /**
     * Si on est pas à la 1è page, faire apparaitre : aller à première page
     *
     * @param string $ifIssetGet - Si il y a déjà des GET dans l'URL, les cumuler avec les liens
     * @return string
     */
    protected function firstLink(string $ifIssetGet): string
    {
        $html = '';

        if ($this->pagination->getCurrentPage() != 1) {
            $points = ($this->pagination->getCurrentPage() > ($this->pagination->getNumberLinks() + 2))
                ? '<li class="points"><span>...</span></li>'
                : '';

            if ($this->pagination->getCssClassP() !== self::CSS_CLASS_BOOTSRPAP) {
                $title = ' title="'.$this->langPagination['first'].'"';
            } else {
                $title = '';
            }

            $href = 'href="?'.self::PAGE_NAME.'=1'.$ifIssetGet.'"';

            $html .= '<li><a'.$title.' '.$href.'>';
            $html .= '1';
            $html .= '</a></li>'.$points;
        }

        return $html;
    }

    /**
     * @param string $html
     * @return string
     */
    protected function paginationActive(string $html): string
    {
        return '<li class="'.$this->pagination->getCssClassLinkActive().'"><span>'.$html.'</span></li>';
    }

    /**
     * @param string $url
     * @param string $html
     * @return string
     */
    protected function paginationLink(string $url, string $html): string
    {
        return '<li><a href="?'.self::PAGE_NAME.'='.$url.'">'.$html.'</a></li>';
    }

    /**
     * Si on est pas à la dernière page, faire apparaitre : aller à dernière page
     *
     * @param string $ifIssetGet - Si il y a déjà des GET dans l'URL, les cumuler avec les liens
     * @return string
     */
    protected function lastLink(string $ifIssetGet): string
    {
        $html = '';

        if ($this->pagination->getCurrentPage() != $this->pagination->getPageEnd()) {
            $points = ($this->pagination->getCurrentPage() < $this->pagination->getNbPages()-($this->pagination->getNumberLinks() + 1))
                ? '<li class="points"><span>...</span></li>'
                : '';

            if ($this->pagination->getCssClassP() !== self::CSS_CLASS_BOOTSRPAP) {
                $title = ' title="'.$this->langPagination['last'].'"';
            } else {
                $title = '';
            }

            $href = 'href="?'.self::PAGE_NAME.'='.$this->pagination->getNbPages().''.$ifIssetGet.'"';

            $html .= $points.'<li><a'.$title.' '.$href.'>';
            $html .= $this->pagination->getNbPages();
            $html .= '</a></li>';
        }

        return $html;
    }

    /**
     * Si on est pas à la dernière page, faire apparaitre : la flèche droite (page suivante)
     *
     * @param string $ifIssetGet - si il y a des autres GET, les conserver dans les liens
     * @return string
     */
    protected function nextLink(string $ifIssetGet): string
    {
        $html = '';

        if ($this->pagination->getCssClassP() !== self::CSS_CLASS_BOOTSRPAP) {
            $condition = $this->pagination->getCurrentPage() != $this->pagination->getPageEnd();
            $title = ' title="'.$this->langPagination['next'].'"';
            $cssClass = '';
        } else {
            $condition = true;
            $title = '';
            $cssClass = $this->pagination->isLastPage() ? ' class="disabled"' : '';
        }

        if ($condition) {
            $href = 'href="?'.self::PAGE_NAME.'='.($this->pagination->getCurrentPage() + 1).''.$ifIssetGet.'"';

            $html .= '<li'.$cssClass.'><a rel="next"'.$title.' '.$href.'>';
            $html .= '&raquo;';
            $html .= '</a></li>';
        }

        return $html;
    }

    /**
     * @return string
     */
    protected function close(): string
    {
        return '</ul></div>';
    }

    /**
     * @return string
     */
    protected function perPageOnchange(): string
    {
        return 'onchange="document.getElementById(\''.$this->pagination->getCssIdPP().'\').submit()"';
    }

    /**
     * @param string $actionPerPage
     * @return string
     */
    protected function perPageOpenForm(string $actionPerPage): string
    {
        return '<form id="'.$this->pagination->getCssIdPP().'" action="'.$actionPerPage.'" method="get">';
    }

    /**
     * @return string
     */
    protected function perPageLabel(): string
    {
        return '<label for="nb-perpage">'.$this->langPagination['per_page'].' : </label>';
    }

    /**
     * @return string
     */
    protected function perPageInputHidden(): string
    {
        return '<input type="hidden" name="'.self::PAGE_NAME.'" value="'.$this->pagination->getCurrentPage().'">';
    }

    /**
     * @param string $onChange
     * @return string
     */
    protected function perPageOpenSelect(string $onChange): string
    {
        return '<select '.$onChange.' name="'.self::PER_PAGE_NAME.'" id="nb-perpage">';
    }

    /**
     * @param string $selected
     * @param string $valuePP
     * @param string|null $all
     * @return string
     */
    protected function perPageOption(string $selected, string $valuePP, string $all = null): string
    {
        $html = ($all !== null) ? $all : $valuePP;
        
        return '<option '.$selected.' value="'.$valuePP.'">'.$html.'</option>';
    }

    /**
     * @return string
     */
    protected function perPageCloseSelect(): string
    {
        return '</select>';
    }

    /**
     * @return string
     */
    protected function perPageCloseForm(): string
    {
        return '</form>';
    }
}
