<?php

namespace Kuusamo\Vle\Service\Email;

use Kuusamo\Vle\Service\Email\Provider\PhpMail;
use Kuusamo\Vle\Service\Email\Provider\ProviderInterface;
use Kuusamo\Vle\Service\Templating\Templating;

class EmailFactory
{
    private static $provider;

    /**
     * Create an email sending service.
     *
     * @param Templating $templating Templating.
     * @return Email
     */
    public static function create(Templating $templating): Email
    {
        $provider = self::$provider ?? new PhpMail;
        return new Email($provider, $templating);
    }

    /**
     * Set a custom provider.
     *
     * @param ProviderInterface $provider Provider.
     */
    public static function setProvider(ProviderInterface $provider)
    {
        self::$provider = $provider;
    }
}
