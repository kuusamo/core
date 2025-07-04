<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Service\Email;

use Kuusamo\Vle\Entity\LoginToken;
use Kuusamo\Vle\Entity\User;
use Kuusamo\Vle\Helper\Environment;
use Kuusamo\Vle\Helper\UrlUtils;
use Kuusamo\Vle\Service\Email\Provider\ProviderInterface;
use Kuusamo\Vle\Service\Templating\Templating;

/**
 * Email service.
 */
class Email
{
    /**
     * @var Provider
     */
    private $provider;

    /**
     * @var Templating
     */
    private $templating;

    /**
     * Create a Mailgun object.
     *
     * @Param ProviderInterface $provider   Email provider.
     * @param Templating        $templating Templating.
     */
    public function __construct(ProviderInterface $provider, Templating $templating)
    {
        $this->provider = $provider;
        $this->templating = $templating;
    }

    /**
     * Send an email.
     *
     * @param string $recipient Email address.
     * @param string $subject   Subject.
     * @param string $message   Message body.
     * @return boolean
     */
    private function sendEmail(string $recipient, string $subject, string $message): bool
    {
        return $this->provider->sendEmail($recipient, $subject, $message);
    }

    /**
     * Send a magic link to log in.
     *
     * @param LoginToken $token Token.
     * @param string     $from  Return URL.
     * @return boolean
     */
    public function sendMagicLinkEmail(LoginToken $token, ?string $from): bool
    {
        $subject = sprintf('Login to %s', Environment::get('SITE_NAME'));
        $from = $from ? UrlUtils::sanitiseInternal($from) : '';

        $url = sprintf(
            '%s/login?token=%s&from=%s',
            Environment::get('SITE_URL'),
            $token->getToken(),
            $from,
        );

        $message = $this->templating->renderTemplate('email/magic-link.html', [
            'site' => Environment::get('SITE_NAME'),
            'url' => $url,
            'name' => $token->getUser()->getFirstName()
        ]);

        return $this->sendEmail(
            $token->getUser()->getEmail(),
            $subject,
            $message
        );
    }
}
