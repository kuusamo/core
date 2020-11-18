<?php

namespace Kuusamo\Vle\Test\Helper;

use Kuusamo\Vle\Helper\UrlUtils;
use PHPUnit\Framework\TestCase;

class UrlUtilsTest extends TestCase
{
    public function testSanitiseInternal()
    {
        $this->assertEquals('/test', UrlUtils::sanitiseInternal('/test'));
        $this->assertEquals('http//test', UrlUtils::sanitiseInternal('http://test'));
    }
}
