<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Entity\Theme;

class Theme
{
    private $logo;

    private $logoFlush = false;

    /**
     * @deprecated
     */
    private $footerText;

    private $colours = [];

    /**
     * Get the path to the logo.
     *
     * @return string|null
     */
    public function getLogo(): ?string
    {
        return $this->logo;
    }

    /**
     * Should the logo be flush?
     *
     * @return boolean
     */
    public function isLogoFlush(): bool
    {
        return $this->logoFlush;
    }

    /**
     * Set the logo path.
     *
     * @param string  $path    Path.
     * @param boolean $isFlush Set logo flush to the top of the page.
     * @return void
     */
    public function setLogo(string $path, bool $isFlush = false)
    {
        $this->logo = $path;
        $this->logoFlush = $isFlush;
    }

    /**
     * Get the footer text.
     *
     * @param string|null
     * @deprecated
     */
    public function getFooterText(): ?string
    {
        return $this->footerText;
    }

    /**
     * Set the text to be displayed in the footer.
     *
     * @param string $text Text.
     * @return void
     * @deprecated
     */
    public function setFooterText(string $text)
    {
        $this->footerText = $text;
    }

    /**
     * Get an array of colours. Could be empty.
     *
     * @return array
     */
    public function getColours(): array
    {
        return $this->colours;
    }

    /**
     * Set a colour.
     *
     * @param string $name   Key.
     * @param string $colour HEX value.
     * @return void
     */
    public function setColour(string $name, string $colour)
    {
        $this->colours[$name] = new Colour($colour);
    }
}
