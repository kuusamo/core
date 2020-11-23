<?php

namespace Kuusamo\Vle\Service\Email;

use Kuusamo\Vle\Service\Email\Provider\PhpMail;
use Kuusamo\Vle\Service\Templating;

class EmailFactory
{
    /**
     * Create an email sending service.
     *
     * @param Templating $templating Templating.
     * @return Email
     */
    public static function create(Templating $templating): Email
    {
        $provider = new PhpMail;
        return new Email($provider, $templating);
    }
}
