<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Helper;

class FileUtils
{
    /**
     * Explode a filename into the file and extension.
     *
     * @param string $filename Filename.
     * @return array
     */
    public static function explode(string $filename): array
    {
        $fileParts = explode('.', $filename);

        if (count($fileParts) == 1) {
            return [$filename, null];
        }

        $extension = array_pop($fileParts);
        $file = implode('.', $fileParts);

        return [$file, $extension];
    }

    /**
     * Increment a filename.
     *
     * @param string $filename Filename.
     * @return string
     */
    public static function increment(string $filename): string
    {
        $fileParts = self::explode($filename);

        if (preg_match('/^(.+)\-(\d+)$/', $fileParts[0], $matches)) {
            $newNumber = intval($matches[2]) + 1;
            return sprintf('%s-%s.%s', $matches[1], $newNumber, $fileParts[1]);
        }

        return sprintf('%s-2.%s', $fileParts[0], $fileParts[1]);
    }

    /**
     * Generate a UUID with the correct extension.
     *
     * @param string $filename Filename.
     * @return string
     */
    public static function uuid(string $filename): string
    {
        $fileParts = self::explode($filename);

        if ($fileParts[1] === null) {
            return UuidUtils::generate();
        }

        return sprintf('%s.%s', UuidUtils::generate(), $fileParts[1]);
    }
}
