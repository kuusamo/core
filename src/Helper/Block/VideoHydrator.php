<?php

namespace Kuusamo\Vle\Helper\Block;

use Kuusamo\Vle\Entity\Block\VideoBlock;

class VideoHydrator extends Hydrator
{
    public function hydrate(VideoBlock $block, array $data)
    {
        $block->setProvider($data['provider']);
        $block->setProviderId($data['providerId']);

        if (isset($data['duration'])) {
            $block->setDuration(intval($data['duration']));
        }
    }

    public function validate(VideoBlock $block): bool
    {
        if ($block->getProvider() == '') {
            throw new ValidationException('No provider');
        } elseif ($block->getProviderId() == '') {
            throw new ValidationException('No provider ID');
        }

        return true;
    }
}
