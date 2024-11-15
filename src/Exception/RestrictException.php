<?php

namespace Dipesh79\LaravelSoftDeleteAction\Exception;

use Exception;
use Throwable;

/**
 * Class RestrictException
 *
 * Exception thrown when a restricted action is attempted.
 */
class RestrictException extends Exception
{
    /**
     * RestrictException constructor.
     *
     * @param string $message The exception message
     * @param int $code The exception code
     * @param Throwable|null $previous The previous throwable used for the exception chaining
     */
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
