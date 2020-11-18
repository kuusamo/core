<?php

namespace Kuusamo\Vle\Service\Storage;

interface StorageInterface
{
    public function get(string $key): StorageObject;
    public function put(string $key, string $body, string $contentType);
    public function delete(string $key);
}
