<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Service;

use Kuusamo\Vle\Helper\Environment;

class Meta
{
    private string $title = 'Kuusamo';

    private string $description = 'Virtual learning environment.';

    private array $keywords = ['VLE'];

    protected ?string $canonical = null;

    protected ?string $ogTitle = null;

    protected ?string $ogImage = null;

    /**
     * Load in default config.
     */
    public function __construct()
    {
        $this->title = Environment::get('SITE_NAME');
    }

    /**
     * Get the title.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set the title.
     *
     * @param string  $str      Title string.
     * @param boolean $override Replace existing title (default is to append).
     * @return void
     */
    public function setTitle(string $str, bool $override = false)
    {
        if ($override === true) {
            $this->title = $str;
            return true;
        }

        $this->title = sprintf('%s - %s', $str, $this->title);
    }

    /**
     * Get the meta description.
     *
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set the meta description.
     *
     * @param string $str Description string.
     * @return void
     */
    public function setDescription(?string $str)
    {
        $this->description = $str;
    }

    /**
     * Get the keywords as a string.
     *
     * @return string
     */
    public function getKeywords(): string
    {
        return implode(',', $this->keywords);
    }

    /**
     * Add keywords from an array.
     *
     * @param array $arr Array of new keywords.
     * @return void
     */
    public function addKeywords(array $arr)
    {
        foreach ($arr as $keyword) {
            if (!in_array($keyword, $this->keywords)) {
                $this->keywords[] = $keyword;
            }
        }
    }

    /**
     * Set keywords, replacing any previous keywords.
     *
     * @param arra $arr Array of new keywords.
     * @return void
     */
    public function setKeywords(array $arr)
    {
        $this->keywords = $arr;
    }

    /**
     * Get the canonical URL.
     *
     * @return string
     */
    public function getCanonical(): ?string
    {
        return $this->canonical;
    }

    /**
     * Set the canonical URL.
     *
     * @param string $value URL.
     * @return void
     */
    public function setCanonical(string $value)
    {
        $this->canonical = $value;
    }

    /**
     * Get the OpenGraph title.
     *
     * @return string
     */
    public function getOgTitle(): ?string
    {
        return $this->ogTitle;
    }

    /**
     * Set OpenGraph title.
     *
     * @param string $value Title.
     * @return void
     */
    public function setOgTitle(string $value)
    {
        $this->ogTitle = $value;
    }

    /**
     * Get the OpenGraph image.
     *
     * @return string
     */
    public function getOgImage(): ?string
    {
        return $this->ogImage;
    }

    /**
     * Set OpenGraph image.
     *
     * @param string $value Image.
     * @return void
     */
public function setOgImage(string $value)
    {
        $this->ogImage = $value;
    }

    /**
     * Get the favicon location, if specified.
     *
     * @return string|null
     */
    public function getFavicon()
    {
        return Environment::get('FAVICON');
    }
}
