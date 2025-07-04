<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Entity\Theme;

use OzdemirBurak\Iris\Color\Hex;
use InvalidArgumentException;

class Colour
{
    private $hex;

    /**
     * Constructor. Takes a hex string.
     *
     * @param string $hex Hex value.
     */
    public function __construct(string $hex)
    {
        if ((strlen($hex) !== 4) && (strlen($hex) !== 7)) {
            throw new InvalidArgumentException('Hex codes must be 4 or 7 digits including #');
        }

        if (substr($hex, 0, 1) != '#') {
            throw new InvalidArgumentException('Hex codes must include the #');
        }

        if (!ctype_xdigit(substr($hex, 1))) {
            throw new InvalidArgumentException('Invalid hex code');
        }

        $this->hex = $hex;
    }

    /**
     * Return a lighter version of the colour.
     *
     * @return Hex
     */
    public function highlight(): Hex
    {
        $highlight = new Hex($this->hex);
        $highlight = $highlight->lighten(10);
        return $highlight;
    }

    /**
     * Return a faded  version suitable for backgrounds.
     *
     * @return Hex
     */
    public function fade(): Hex
    {
        $highlight = new Hex($this->hex);
        $highlight = $highlight->lighten(50);
        return $highlight;
    }

    /**
     * Return the colour as a hex.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->hex;
    }
}
