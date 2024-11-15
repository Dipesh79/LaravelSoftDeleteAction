<?php

namespace Dipesh79\LaravelSoftDeleteAction\Exception;

use Exception;
use Throwable;

/**
 * Class MissingOnDeletePropertyException
 *
 * Exception thrown when the $onDelete property is not defined in the model.
 */
class MissingOnDeletePropertyException extends Exception
{
    /**
     * @var string The exception message
     */
    protected $message = 'The $onDelete property is not defined in the model.';

    /**
     * MissingOnDeletePropertyException constructor.
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
