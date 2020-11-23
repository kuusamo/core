<?php

namespace Kuusamo\Vle\Service\Email;

use Kuusamo\Vle\Entity\User;
use Kuusamo\Vle\Helper\Environment;
use Kuusamo\Vle\Service\Email\Provider\ProviderInterface;
use Kuusamo\Vle\Service\Templating;

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
     * @param User $user User object.
     * @return boolean
     */
    public function sendMagicLinkEmail(User $user): bool
    {
        $subject = sprintf('Login to %s', Environment::get('SITE_NAME'));

        $url = sprintf(
            '%s/login?token=%s',
            Environment::get('SITE_URL'),
            $user->getSecurityToken()
        );

        $message = $this->templating->renderTemplate('email/magic-link.html', [
            'url' => $url,
            'name' => $user->getFirstName()
        ]);

        return $this->sendEmail(
            $user->getEmail(),
            $subject,
            $message
        );
    }
}
