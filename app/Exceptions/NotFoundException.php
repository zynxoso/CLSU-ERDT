<?php

namespace App\Exceptions;

/**
 * Exception thrown when a requested resource is not found.
 */
class NotFoundException extends BaseException
{
    /**
     * The error code for this exception.
     *
     * @var string
     */
    protected string $errorCode = 'RESOURCE_NOT_FOUND';

    /**
     * Create a new not found exception instance.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     * @param array $context
     * @return void
     */
    public function __construct(
        string $message = "The requested resource was not found",
        int $code = 404,
        \Throwable $previous = null,
        array $context = []
    ) {
        parent::__construct($message, $code, $previous, $context);
    }
}
