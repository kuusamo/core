<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Test\Helper;

use Kuusamo\Vle\Helper\Collection;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    public function testAccess()
    {
        $collection = new Collection(['a' => 'value']);

        $this->assertSame(true, isset($collection['a']));
        $this->assertSame('value', $collection['a']);

        $this->assertSame(false, isset($collection['b']));
        $this->assertSame(null, $collection['b']);
    }

    public function testSet()
    {
        $collection = new Collection;

        $collection['c'] = 'value';

        $this->assertSame('value', $collection['c']);

        unset($collection['c']);

        $this->assertSame(null, $collection['c']);
    }
}
