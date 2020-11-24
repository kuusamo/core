<?php

namespace Kuusamo\Vle\Test\Validation;

use Kuusamo\Vle\Validation\AwardingBodyValidator;
use PHPUnit\Framework\TestCase;

class AwardingBodyValidatorTest extends TestCase
{
    public function testValid()
    {
        $body = $this->createMock('Kuusamo\Vle\Entity\AwardingBody');
        $body->method('getName')->willReturn('ABC Awards');
        $body->method('getAuthoriserName')->willReturn('Jane Smith');
        $body->method('getAuthoriserRole')->willReturn('Director of Teaching');

        $validator = new AwardingBodyValidator;

        $this->assertSame(true, $validator($body));
    }

    /**
     * @expectedException Kuusamo\Vle\Validation\ValidationException
     */
    public function testEmptyName()
    {
        $body = $this->createMock('Kuusamo\Vle\Entity\AwardingBody');
        $body->method('getName')->willReturn('');
        $body->method('getAuthoriserName')->willReturn('Jane Smith');
        $body->method('getAuthoriserRole')->willReturn('Director of Teaching');

        $validator = new AwardingBodyValidator;
        $validator($body);
    }

    /**
     * @expectedException Kuusamo\Vle\Validation\ValidationException
     */
    public function testEmptyAuthoriserName()
    {
        $body = $this->createMock('Kuusamo\Vle\Entity\AwardingBody');
        $body->method('getName')->willReturn('ABC Awards');
        $body->method('getAuthoriserName')->willReturn('');
        $body->method('getAuthoriserRole')->willReturn('Director of Teaching');

        $validator = new AwardingBodyValidator;
        $validator($body);
    }

    /**
     * @expectedException Kuusamo\Vle\Validation\ValidationException
     */
    public function testEmptyAuthoriserRole()
    {
        $body = $this->createMock('Kuusamo\Vle\Entity\AwardingBody');
        $body->method('getName')->willReturn('ABC Awards');
        $body->method('getAuthoriserName')->willReturn('Jane Smith');
        $body->method('getAuthoriserRole')->willReturn('');

        $validator = new AwardingBodyValidator;
        $validator($body);
    }
}
