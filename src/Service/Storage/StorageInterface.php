<?php

namespace Kuusamo\Vle\Service\Storage;

interface StorageInterface
{
    public function get(string $key): StorageObject;
    public function put(string $key, $body, string $contentType): bool;
    public function delete(string $key): bool;
}
