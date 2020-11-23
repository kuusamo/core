<?php

namespace Kuusamo\Vle\Service\Email\Provider;

interface ProviderInterface
{
    public function sendEmail(string $recipient, string $subject, string $message): bool;
}
