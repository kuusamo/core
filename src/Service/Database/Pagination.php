<?php

namespace Kuusamo\Vle\Service\Database;

use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;

class Pagination
{
    private $paginator;
    private $page;
    private $resultsPerPage;

    /**
     * Create a pagination class.
     *
     * @param Query   $query          Doctrine query.
     * @param integer $page           Page number.
     * @param integer $resultsPerPage Results per page.
     */
    public function __construct(Query $query, int $page = 1, int $resultsPerPage = 50)
    {
        $firstResult = ($page * $resultsPerPage) - $resultsPerPage;

        $query->setFirstResult($firstResult)->setMaxResults($resultsPerPage);

        $this->page = $page;
        $this->resultsPerPage = $resultsPerPage;
        $this->paginator = new Paginator($query);
    }

    /**
     * Return the items to iterate through.
     *
     * @return Paginator
     */
    public function results(): Paginator
    {
        return $this->paginator;
    }

    /**
     * Return an array of pages.
     *
     * @return array
     */
    public function pages(): array
    {
        $pages = [];
        $totalResults = $this->paginator->count();
        $totalPages = ceil($totalResults / $this->resultsPerPage);
        

        for ($i = 1; $i <= $totalPages; $i++) {
            $pages[] = [
                'number' => $i,
                'active' => $i === $this->page
            ];
        }

        return $pages;
    }

    /**
     * Does this query have any results?
     *
     * @return boolean
     */
    public function hasResults(): bool
    {
        return $this->paginator->count() > 0;
    }

    /**
     * Does this query have more than one page?
     *
     * @return boolean
     */
    public function hasPages(): bool
    {
        return $this->paginator->count() > $this->resultsPerPage;
    }
}
