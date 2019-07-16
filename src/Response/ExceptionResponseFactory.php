<?php


namespace Halitools\MicroCommand\Response;


class ExceptionResponseFactory
{

    protected $customErrors = [];

    public function make(\Exception $exception): ExceptionResponseInterface
    {
        $exceptionClass = get_class($exception);
        if (!empty($this->customErrors[$exceptionClass])) {
            return $this->customErrors[$exceptionClass]::build($exception);
        }
        return ExceptionResponse::build($exception);
    }

    /**
     * @param array $customErrors
     */
    public function setCustomErrors(array $customErrors)
    {
        $this->customErrors = $customErrors;
    }

    /**
     * Register a new custom exception response. This must implement the
     * @param string $exceptionClass
     * @param string $responseClass
     */
    public function addCustomError(string $exceptionClass, string $responseClass)
    {
        $this->customErrors[$exceptionClass] = $responseClass;
    }
}