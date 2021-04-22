<?php

namespace DawPhpPagination\Contracts;

/**
 * @link     https://github.com/dev-and-web/daw-php-pagination
 * @author   Stephen Damian <contact@devandweb.fr>
 * @license  MIT License
 */
Interface PaginationInterface
{
    /**
     * Pagination constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = []);
    
    /**
     * @param int $nbElements - Nombre d'éléments à paginer
     */
    public function paginate(int $count): void;

    /**
     * @return int|null - offset
     */
    public function getOffset(): ?int;

    /**
     * @return int|null - limit
     */
    public function getLimit(): ?int;

    /**
     * @return int - Nombre total d'éléments sur lesquels on pagine
     */
    public function getCount(): int;

    /**
     * @return int - Nombre d'éléments sur la page en cours
     */
    public function getCountOnCurrentPage(): int;

    /**
     * Pour retourner l'indexation du premier élément sur la page en cours
     * Utile pour par exemple afficher : élement "nb start" à ...
     *
     * @return int
     */
    public function getFrom(): int;

    /**
     * Pour retourner l'indexation du deriner élément sur la page en cours
     * Utile pour par exemple afficher : élement ... à "nb end"
     *
     * @return int
     */
    public function getTo(): int;

    /**
     * @return int - Page en cours
     */
    public function getCurrentPage(): int;

    /**
     * @return int - Nombre de pages
     */
    public function getNbPages(): int;

    /**
     * @return int - Le nombre d'éléments affichés par page
     */
    public function getPerPage();

    /**
     * @return bool - True si il reste des pages après celle en cours
     */
    public function hasMorePages(): bool;

    /**
     * @return bool - True si on est sur la première page
     */
    public function isFirstPage(): bool;

    /**
     * @return bool - True si on est sur la dernière page
     */
    public function isLastPage(): bool;

    /**
     * Pour afficher la pagination
     *
     * @param array|string|null $ifIssetGet - Si il y a déjà des GET dans l'URL, les cumuler avec les liens
     * @return string
     */
    public function render($ifIssetGet = null): string;

    /**
     * Pour choisir nombre d'éléments par page à afficher
     *
     * @param null|string $action - Pour l'action du form
     * @return string
     */
    public function perPage(string $action = null): string;
}
