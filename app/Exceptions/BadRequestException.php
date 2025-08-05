<?php

namespace App\Exceptions;

/**
 * Exception na ginagamit kapag ang request ay mali o may invalid na data.
 */
class BadRequestException extends BaseException
{
    /**
     * Ang error code para sa exception na ito.
     *
     * @var string
     */
    protected string $errorCode = 'BAD_REQUEST';

    /**
     * Gumawa ng bagong bad request exception instance.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     * @param array $context
     * @return void
     */
    public function __construct(
        string $message = "The request contains invalid data",
        int $code = 400,
        \Throwable $previous = null,
        array $context = []
    ) {
        parent::__construct($message, $code, $previous, $context);
    }
}
