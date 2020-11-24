<?php

namespace Kuusamo\Vle\Test\Entity;

use Kuusamo\Vle\Entity\AwardingBody;
use PHPUnit\Framework\TestCase;

class AwardingBodyTest extends TestCase
{
    public function testAccessors()
    {
        $imageMock = $this->createMock('Kuusamo\Vle\Entity\Image');

        $body = new AwardingBody;

        $body->setId(10);
        $body->setName('ABC Awards');
        $body->setLogo($imageMock);
        $body->setAuthoriserName('Jane Smith');
        $body->setAuthoriserSignature('J. Smith');
        $body->setAuthoriserRole('Director of Teaching');

        $this->assertSame(10, $body->getId());
        $this->assertSame('ABC Awards', $body->getName());
        $this->assertSame($imageMock, $body->getLogo());
        $this->assertSame('Jane Smith', $body->getAuthoriserName());
        $this->assertSame('J. Smith', $body->getAuthoriserSignature());
        $this->assertSame('Director of Teaching', $body->getAuthoriserRole());

        $body->setAuthoriserSignature('');

        $this->assertNull($body->getAuthoriserSignature());
    }
}
