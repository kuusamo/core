<?php

namespace Kuusamo\Vle\Entity\Theme;

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
     * Return the colour as a hex.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->hex;
    }
}
