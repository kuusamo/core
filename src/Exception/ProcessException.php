<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Exception;

use Exception;

/**
 * This is used for things like form processing, to allow
 * you to bail out and display a fail page at any point.
 */
class ProcessException extends Exception
{
}
