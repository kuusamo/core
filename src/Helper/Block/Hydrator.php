<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Helper\Block;

use Doctrine\ORM\EntityManager;

abstract class Hydrator
{
    /**
     * Database service.
     */
    protected $db;

    /**
     * Constructor.
     *
     * @param EntityManager $db Database service. Optional as some do not use it.
     */
    public function __construct(EntityManager $db = null)
    {
        $this->db = $db;
    }
}
