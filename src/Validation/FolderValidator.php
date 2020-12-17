<?php

namespace Kuusamo\Vle\Validation;

use Kuusamo\Vle\Entity\Folder;

class FolderValidator
{
    public function __invoke(Folder $folder): bool
    {
        if ($folder->getName() == '') {
            throw new ValidationException('Folder name empty');
        }

        if (strlen($folder->getName()) > 128) {
            throw new ValidationException('Folder names cannot be more than 128 characters');
        }

        return true;
    }
}
