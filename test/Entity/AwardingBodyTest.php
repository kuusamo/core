<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Test\Entity;

use Kuusamo\Vle\Entity\AwardingBody;
use Kuusamo\Vle\Entity\Image;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class AwardingBodyTest extends TestCase
{
    public function testAccessors()
    {
        $imageMock = $this->createMock(Image::class);

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
        $body->setAuthoriserRole('');

        $this->assertNull($body->getAuthoriserSignature());
        $this->assertNull($body->getAuthoriserRole());
    }

    public function testCourses()
    {
        $body = new AwardingBody;

        $this->assertSame(false, $body->hasCourses());

        $this->assertInstanceOf(
            ArrayCollection::class,
            $body->getCourses()
        );

        $body->getCourses()->add((object)[]);

        $this->assertSame(true, $body->hasCourses());
    }

    public function testAccreditees()
    {
        $body = new AwardingBody;

        $this->assertSame(false, $body->hasAccreditees());

        $this->assertInstanceOf(
            ArrayCollection::class,
            $body->getAccreditees()
        );

        $body->getAccreditees()->add((object)[]);

        $this->assertSame(true, $body->hasAccreditees());
    }

    public function testAccreditations()
    {
        $body = new AwardingBody;

        $this->assertInstanceOf(
            ArrayCollection::class,
            $body->getAccreditations()
        );
    }
}
