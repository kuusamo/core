<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Test\Entity;

use Kuusamo\Vle\Entity\AwardingBody;
use Kuusamo\Vle\Entity\Course;
use Kuusamo\Vle\Entity\Image;
use Kuusamo\Vle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

class CourseTest extends TestCase
{
    public function testAccessors()
    {
        $awardingBodyMock = $this->createMock(AwardingBody::class);
        $imageMock = $this->createMock(Image::class);

        $course = new Course;

        $this->assertSame(true, $course->isCertificateAvailable());
        $this->assertSame(Course::PRIVACY_PRIVATE, $course->getPrivacy());

        $course->setId(10);
        $course->setName('Chemistry 101');
        $course->setSlug('chemistry-101');
        $course->setQualification('Chemistry Diploma');
        $course->setAwardingBody($awardingBodyMock);
        $course->setCertificateAvailable(false);
        $course->setImage($imageMock);
        $course->setPrivacy(Course::PRIVACY_OPEN);
        $course->setWelcomeText('Welcome!');

        $this->assertSame(10, $course->getId());
        $this->assertSame('Chemistry 101', $course->getName());
        $this->assertSame('chemistry-101', $course->getSlug());
        $this->assertSame('Chemistry Diploma', $course->getQualification());
        $this->assertSame($awardingBodyMock, $course->getAwardingBody());
        $this->assertSame(false, $course->isCertificateAvailable());
        $this->assertSame($imageMock, $course->getImage());
        $this->assertSame(Course::PRIVACY_OPEN, $course->getPrivacy());
        $this->assertSame('/course/chemistry-101', $course->uri());
        $this->assertSame('Welcome!', $course->getWelcomeText());

        $this->assertSame(
            '{"name":"Chemistry 101","slug":"chemistry-101","qualification":"Chemistry Diploma","certificateAvailable":false,"privacy":"OPEN","welcomeText":"Welcome!","modules":[]}',
            json_encode($course)
        );

        $course->setQualification('');
        $course->setWelcomeText('');

        $this->assertNull($course->getQualification());
        $this->assertNull($course->getWelcomeText());
    }

    public function testModules()
    {
        $course = new Course;

        $this->assertInstanceOf(
            ArrayCollection::class,
            $course->getModules()
        );
    }

    public function testUsers()
    {
        $userMock = $this->createMock(User::class);

        $course = new Course;

        $this->assertSame(false, $course->hasUsers());

        $course->getUsers()->add($userMock);

        $this->assertSame(true, $course->hasUsers());
    }

    public function testInvalidPrivacy()
    {
        $this->expectException(InvalidArgumentException::class);

        $course = new Course;
        $course->setPrivacy('made up privacy');
    }
}
