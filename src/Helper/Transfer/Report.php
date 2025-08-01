<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Helper\Transfer;

class Report
{
    private $items = [];

    public function notice(string $message)
    {
        $this->items[] = $message;
    }

    public function getItems(): array
    {
        return $this->items;
    }
}
