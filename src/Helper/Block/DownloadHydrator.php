<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Helper\Block;

use Kuusamo\Vle\Entity\File;
use Kuusamo\Vle\Entity\Block\DownloadBlock;

class DownloadHydrator extends Hydrator
{
    public function hydrate(DownloadBlock $block, array $data)
    {
        $file = $this->db->find(
            'Kuusamo\Vle\Entity\File',
            $data['file']['id']
        );

        if ($file) {
            $block->setFile($file);
        }
    }

    public function validate(DownloadBlock $block): bool
    {
        if (($block->getFile() instanceof File) === false) {
            throw new ValidationException('Invalid download ID');
        }

        return true;
    }
}
