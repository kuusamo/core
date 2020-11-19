<?php

namespace Kuusamo\Vle\Entity\Block;

use InvalidArgumentException;
use JsonSerializable;

/**
 * @Entity
 * @Table(name="blocks_videos")
 */
class VideoBlock extends Block implements JsonSerializable
{
    const PROVIDER_VIMEO = 'vimeo';
    const PROVIDER_YOUTUBE = 'youtube';

    /**
     * @Column(type="string")
     */
    private $provider;

    /**
     * @Column(type="string", name="provider_id")
     */
    private $providerId;

    /**
     * @Column(type="integer", nullable=true)
     */
    private $duration;

    public function getProvider(): string
    {
        return $this->provider;
    }

    public function setProvider(string $value)
    {
        if (!in_array($value, [self::PROVIDER_VIMEO, self::PROVIDER_YOUTUBE])) {
            throw new InvalidArgumentException('Unsupported provider');
        }

        $this->provider = $value;
    }

    public function getProviderId(): string
    {
        return $this->providerId;
    }

    public function setProviderId(string $value)
    {
        $this->providerId = $value;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $value)
    {
        $this->duration = $value;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'type' => Block::TYPE_VIDEO,
            'provider' => $this->provider,
            'providerId' => $this->providerId
        ];
    }
}
