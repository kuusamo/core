<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Test\Helper\Form;

use Kuusamo\Vle\Helper\Form\Select;
use PHPUnit\Framework\TestCase;

class SelectTest extends TestCase
{
    public function testTitle()
    {
        $select = new Select;
        $select->addOption('option-1', 'Option 1');
        $select->addOption('option-2');
        $select->setDefaultOption('option-1');

        $result = [
            ['value' => 'option-1', 'label' => 'Option 1', 'selected' => true],
            ['value' => 'option-2', 'label' => 'option-2', 'selected' => false]
        ];

        $this->assertSame($result, $select());
    }
}
