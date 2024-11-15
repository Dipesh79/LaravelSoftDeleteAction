<?php

namespace Dipesh79\LaravelSoftDeleteAction\Exception;

use Exception;
use Throwable;

/**
 * Class MissingSoftDeletesTraitException
 *
 * Exception thrown when the SoftDeletes trait is not used in the model.
 */
class MissingSoftDeletesTraitException extends Exception
{
    /**
     * @var string The exception message
     */
    protected $message = 'The SoftDeletes trait is not used in the model.';

    /**
     * MissingSoftDeletesTraitException constructor.
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
