<?php

namespace Kuusamo\Vle\Service\Authorisation;

use Kuusamo\Vle\Entity\User;
use Kuusamo\Vle\Service\Session;
use Doctrine\ORM\EntityManager;

// @todo Unit test
class Authorisation
{
    private $session;
    private $db;

    /**
     * Constructor.
     *
     * @param Session       $session Session service.
     * @param EntityManager $db      Database.
     */
    public function __construct(Session $session, EntityManager $db)
    {
        $this->session = $session;
        $this->db = $db;
    }

    /**
     * Authorise a user.
     *
     * @param User $user User object.
     * @return void
     */
    public function authoriseUser(User $user)
    {
        // @todo Do we need all of these values? Update deauthorise too
        $this->session->set('user_id', $user->getId());
        $this->session->set('user_email', $user->getEmail());
        $this->session->set('user_name', $user->getFullName());
        $this->session->regenerateId();
    }

    /**
     * Deauthorise user.
     *
     * @return void
     */
    public function deauthoriseUser()
    {
        $this->session->remove('user_id');
        $this->session->remove('user_email');
        $this->session->remove('user_name');
        $this->session->regenerateId();
    }

    /**
     * Is the user logged in?
     *
     * @return boolean
     */
    public function isLoggedIn(): bool
    {
        return $this->session->get('user_id') !== null;
    }

    /**
     * Get the user ID.
     *
     * @return integer|null
     */
    public function getId()
    {
        return $this->session->get('user_id');
    }

    /**
     * Get the user object, if one exists.
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->getId()) {
            return $this->db->find('Kuusamo\Vle\Entity\User', $this->getId());
        }

        return null;
    }
}
