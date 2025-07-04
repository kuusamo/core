<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Test\Helper;

use Kuusamo\Vle\Helper\TokenGenerator;
use PHPUnit\Framework\TestCase;

class TokenGeneratorTest extends TestCase
{
    public function testGenerate()
    {
        $token = TokenGenerator::generate();

        $this->assertIsString($token);
        $this->assertEquals(64, strlen($token));
    }

    public function testShort()
    {
        $token = TokenGenerator::short();

        $this->assertIsString($token);
        $this->assertEquals(32, strlen($token));
    }
}
