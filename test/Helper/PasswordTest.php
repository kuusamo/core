<?php

namespace Kuusamo\Vle\Test\Helper;

use Kuusamo\Vle\Helper\Password;
use PHPUnit\Framework\TestCase;

class VideoBlockTest extends TestCase
{
    public function testClass()
    {
        $password = 'hunter2';

        $hash = Password::hash($password);

        $this->assertSame(true, Password::verify($hash, $password));
        $this->assertSame(false, Password::verify($hash, 'hunter3'));
    }
}
