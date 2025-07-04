<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Test\Entity;

use Kuusamo\Vle\Entity\Theme\Theme;
use PHPUnit\Framework\TestCase;

class ThemeTest extends TestCase
{
    public function testAccessors()
    {
        $theme = new Theme;

        $this->assertSame(false, $theme->isLogoFlush());

        $theme->setLogo('/logo.png', true);
        $theme->setFooterText('Copyright');
        $theme->setColour('primary', '#f0f0f0');

        $this->assertSame('/logo.png', $theme->getLogo());
        $this->assertSame(true, $theme->isLogoFlush());
        $this->assertSame('Copyright', $theme->getFooterText());
        $this->assertIsArray($theme->getColours());
    }
}
