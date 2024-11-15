<?php

namespace Dipesh79\LaravelSoftDeleteAction\Exception;

use Exception;
use Throwable;

/**
 * Class InvalidActionOnDeleteException
 *
 * Exception thrown when an invalid delete action value is provided in the array.
 * The action must be either 'cascade', 'restrict', 'setNull', or 'setDefault'.
 */
class InvalidActionOnDeleteException extends Exception
{
    /**
     * @var string The exception message
     */
    protected $message = "Invalid delete action value on array. The action must be either 'cascade', 'restrict', 'setNull', or 'setDefault'.";

    /**
     * InvalidActionOnDeleteException constructor.
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
