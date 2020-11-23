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
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=iso-8859-1';

        return mail($recipient, $subject, $message, implode("\r\n", $headers));
    }
}
