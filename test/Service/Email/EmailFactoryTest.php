<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Test\Service\Email;

use Kuusamo\Vle\Service\Email\Email;
use Kuusamo\Vle\Service\Email\EmailFactory;
use Kuusamo\Vle\Service\Templating\Templating;
use PHPUnit\Framework\TestCase;

class EmailFactoryTest extends TestCase
{
    public function testCreate()
    {
        $templatingMock = $this->createMock(Templating::class);

        $email = EmailFactory::create($templatingMock);

        $this->assertInstanceOf(
            Email::class,
            $email
        );
    }
}
