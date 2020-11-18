<?php

namespace Kuusamo\Vle\Helper\Block;

use Doctrine\ORM\EntityManager;
use Exception;

class HydratorFactory
{
    /**
     * Create a brand new block object.
     *
     * @param string        $type Block type.
     * @param EntityManager $db   Database service.
     * @return Block
     */
    public static function create(string $type, EntityManager $db)
    {
        switch ($type) {
            case 'audio':
                return new AudioHydrator($db);
            case 'download':
                return new DownloadHydrator($db);
            case 'image':
                return new ImageHydrator($db);
            case 'markdown':
                return new MarkdownHydrator;
            case 'video':
                return new VideoHydrator;
            default:
                // @todo This should throw a more specific exception
                throw new Exception(sprintf('Block type %s not supported', $type));
        }
    }
}
