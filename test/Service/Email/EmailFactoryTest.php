<?php

namespace Kuusamo\Vle\Test\Service\Email;

use Kuusamo\Vle\Service\Email\EmailFactory;
use PHPUnit\Framework\TestCase;

class EmailFactoryTest extends TestCase
{
    public function testAccessors()
    {
        $templatingMock = $this->createMock('Kuusamo\Vle\Service\Templating\Templating');

        $email = EmailFactory::create($templatingMock);

        $this->assertInstanceOf(
            'Kuusamo\Vle\Service\Email\Email',
            $email
        );
    }
}
