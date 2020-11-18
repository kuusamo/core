<?php

namespace Kuusamo\Vle\Entity\Block;

use JsonSerializable;

/**
 * @Entity
 * @Table(name="blocks_videos")
 */
class VideoBlock extends Block implements JsonSerializable
{
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

    // @todo Enforce a valid provider?
    public function setProvider(string $value)
    {
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
