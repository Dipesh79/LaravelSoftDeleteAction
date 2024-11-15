<?php

namespace Dipesh79\LaravelSoftDeleteAction\Exception;

use Exception;
use Throwable;

/**
 * Class MissingDefaultForeignKeyValueMethodException
 *
 * Exception thrown when the method 'getDefaultForeignKeyValueForRelation' is not implemented in the model
 * while using the 'setDefault' action.
 */
class MissingDefaultForeignKeyValueMethodException extends Exception
{
    /**
     * @var string The exception message
     */
    protected $message = "The method 'getDefaultForeignKeyValueForRelation' must be implemented in the model when using 'setDefault' action.";

    /**
     * MissingDefaultForeignKeyValueMethodException constructor.
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
