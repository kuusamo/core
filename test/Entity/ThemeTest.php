<?php

namespace Kuusamo\Vle\Test\Entity;

use Kuusamo\Vle\Entity\Theme;
use PHPUnit\Framework\TestCase;

class ThemeTest extends TestCase
{
    public function testAccessors()
    {
        $theme = new Theme;

        $this->assertSame(false, $theme->isLogoFlush());

        $theme->setLogo('/logo.png', true);
        $theme->setFooterText('Copyright');
        $theme->setColour('header', 'red');

        $this->assertSame('/logo.png', $theme->getLogo());
        $this->assertSame(true, $theme->isLogoFlush());
        $this->assertSame('Copyright', $theme->getFooterText());
        $this->assertSame(['header' => 'red'], $theme->getColours());
    }
}
