<?php

namespace Kuusamo\Vle\Test\Entity;

use Kuusamo\Vle\Entity\Setting;
use PHPUnit\Framework\TestCase;

class SettingTest extends TestCase
{
    public function testAccessors()
    {
        $setting = new Setting;

        $setting->setKey('footer');
        $setting->setValue('<p>Copyright</p>');

        $this->assertSame('footer', $setting->getKey());
        $this->assertSame('<p>Copyright</p>', $setting->getValue());
    }
}
