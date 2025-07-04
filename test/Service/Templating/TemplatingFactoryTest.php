<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Test\Service\Templating;

use Kuusamo\Vle\Service\Templating\Templating;
use Kuusamo\Vle\Service\Templating\TemplatingFactory;
use PHPUnit\Framework\TestCase;

class TemplatingFactoryTest extends TestCase
{
    public function testCreate()
    {
        $templating = TemplatingFactory::create();

        $this->assertInstanceOf(
            Templating::class,
            $templating
        );
    }
}
