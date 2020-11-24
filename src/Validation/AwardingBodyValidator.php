<?php

namespace Kuusamo\Vle\Validation;

use Kuusamo\Vle\Entity\AwardingBody;

class AwardingBodyValidator
{
    public function __invoke(AwardingBody $body): bool
    {
        if ($body->getName() == '') {
            throw new ValidationException('Awarding body name empty');
        }

        if ($body->getAuthoriserName() == '') {
            throw new ValidationException('Authoriser name empty');
        }

        if ($body->getAuthoriserRole() == '') {
            throw new ValidationException('Authoriser role empty');
        }

        return true;
    }
}
