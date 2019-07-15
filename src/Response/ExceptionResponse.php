<?php


namespace Halitools\MicroCommand\Response;


class ExceptionResponse
{

    /**
     * @var string
     */
    protected $exception;

    /** @var
     * string
     */
    protected $message;

    /**
     * @var int
     */
    protected $code;

    /**
     * @var string
     */
    protected $file;

    /**
     * @var string
     */
    protected $line;

    /**
     * @var array
     */
    protected $trace;

    /**
     * ExceptionResponse constructor.
     * @param string $exception
     * @param $message
     * @param int $code
     * @param string $file
     * @param string $line
     * @param array $trace
     */
    public function __construct(string $exception, $message, $code, string $file, string $line, array $trace)
    {
        $this->exception = $exception;
        $this->message = $message;
        $this->code = $code;
        $this->file = $file;
        $this->line = $line;
        $this->trace = $trace;
    }

    /**
     * @return string
     */
    public function getException(): string
    {
        return $this->exception;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * @return string
     */
    public function getLine(): string
    {
        return $this->line;
    }

    /**
     * @return array
     */
    public function getTrace(): array
    {
        return $this->trace;
    }

}