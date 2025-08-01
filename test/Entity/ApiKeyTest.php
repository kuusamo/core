<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Test\Entity;

use Kuusamo\Vle\Entity\ApiKey;
use PHPUnit\Framework\TestCase;

class ApiKeyTest extends TestCase
{
    public function testAccessors()
    {
        $apiKey = new ApiKey;

        $this->assertSame(32, strlen($apiKey->getKey()));
        $this->assertSame(32, strlen($apiKey->getSecret()));

        $apiKey->setDescription('Mobile app');

        $this->assertSame('Mobile app', $apiKey->getDescription());
    }
}
