<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Test\Helper;

use Kuusamo\Vle\Helper\UuidUtils;
use PHPUnit\Framework\TestCase;

class UuidUtilsTest extends TestCase
{
    public function testGenerate()
    {
        $x = UuidUtils::generate();
        $this->assertSame(36, strlen(UuidUtils::generate()));
    }

    public function testSettingPreset()
    {
        UuidUtils::preset('123e4567-e89b-12d3-a456-426614174000');

        // first time it should return our preset
        $this->assertSame(
            '123e4567-e89b-12d3-a456-426614174000',
            UuidUtils::generate()
        );

        // second time it should not
        $this->assertNotSame(
            '123e4567-e89b-12d3-a456-426614174000',
            UuidUtils::generate()
        );
    }
}
