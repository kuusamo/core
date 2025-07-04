<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Test\Helper;

use Kuusamo\Vle\Helper\FileSizeUtils;
use PHPUnit\Framework\TestCase;

class FileSizeUtilsTest extends TestCase
{
    public function testHumanReadable()
    {
        $this->assertSame('1.7 MB', FileSizeUtils::humanReadable(1747372));
        $this->assertSame('1.0 B', FileSizeUtils::humanReadable(1));
        $this->assertSame('0.0 B', FileSizeUtils::humanReadable(0));
    }
}
