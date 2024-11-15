<?php

namespace Dipesh79\LaravelSoftDeleteAction\Exception;

use Exception;
use Throwable;

/**
 * Class InvalidOnDeleteArrayException
 *
 * Exception thrown when the $onDelete property is not an array.
 */
class InvalidOnDeleteArrayException extends Exception
{
    /**
     * @var string The exception message
     */
    protected $message = 'The $onDelete property must be an array.';

    /**
     * InvalidOnDeleteArrayException constructor.
     *
     * @param string|null $message The exception message
     * @param int $code The exception code
     * @param Throwable|null $previous The previous throwable used for the exception chaining
     */
    public function __construct(string $message = null, int $code = 0, ?Throwable $previous = null)
    {
        if ($message) {
            $this->message = $message;
        }
        parent::__construct($this->message, $code, $previous);
    }
}
