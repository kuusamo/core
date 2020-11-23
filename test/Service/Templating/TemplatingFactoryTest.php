<?php

namespace Kuusamo\Vle\Test\Service\Templating;

use Kuusamo\Vle\Service\Templating\TemplatingFactory;
use PHPUnit\Framework\TestCase;

class TemplatingFactoryTest extends TestCase
{
    public function testCreate()
    {
        $templating = TemplatingFactory::create();

        $this->assertInstanceOf(
            'Kuusamo\Vle\Service\Templating\Templating',
            $templating
        );
    }
}
