<?php

namespace Kuusamo\Vle\Test\Validation;

use Kuusamo\Vle\Validation\AwardingBodyValidator;
use Kuusamo\Vle\Validation\ValidationException;

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

    public function testEmptyName()
    {
        $this->expectException(ValidationException::class);

        $body = $this->createMock('Kuusamo\Vle\Entity\AwardingBody');
        $body->method('getName')->willReturn('');
        $body->method('getAuthoriserName')->willReturn('Jane Smith');
        $body->method('getAuthoriserRole')->willReturn('Director of Teaching');

        $validator = new AwardingBodyValidator;
        $validator($body);
    }

    public function testEmptyAuthoriserName()
    {
        $this->expectException(ValidationException::class);

        $body = $this->createMock('Kuusamo\Vle\Entity\AwardingBody');
        $body->method('getName')->willReturn('ABC Awards');
        $body->method('getAuthoriserName')->willReturn('');
        $body->method('getAuthoriserRole')->willReturn('Director of Teaching');

        $validator = new AwardingBodyValidator;
        $validator($body);
    }
}
