<?php

namespace Kuusamo\Vle\Service\Authorisation;

use Kuusamo\Vle\Service\Session\Session;
use Doctrine\ORM\EntityManager;

/**
 * Create authorisation objects.
 */
class AuthorisationFactory
{
    /**
     * Create a new authorisation instance.
     *
     * @param Session       $session Session service.
     * @param EntityManager $db      Database.
     * @return Authorisation
     */
    public static function create(Session $session, EntityManager $db): Authorisation
    {
        return new Authorisation($session, $db);
    }
}
