<?php

namespace Kuusamo\Vle\Service\Email\Provider;

class PhpMail implements ProviderInterface
{
    /**
     * Send an email.
     *
     * @param string $recipient Recipent address.
     * @param string $subject   Subject.
     * @param string $message   Message body.
     * @return boolean
     */
    public function sendEmail(string $recipient, string $subject, string $message): bool
    {
        return mail($recipient, $subject, $message);
    }
}
