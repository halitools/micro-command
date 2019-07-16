<?php

namespace Halitools\MicroCommand\Response;

use Halitools\MicroCommand\Exceptions\RemoteException;

class ExceptionResponse implements ExceptionResponseInterface
{

    protected $data = [];

    /**
     * ExceptionResponse constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public static function build(\Exception $exception): ExceptionResponseInterface
    {
        return new static([
            'exception' => get_class($exception),
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => self::traceToArray($exception)
        ]);
    }

    /**
     * @param \Exception $exception
     * @return array
     */
    protected static function traceToArray(\Exception $exception): array
    {
        $trace = [];
        foreach ($exception->getTrace() as $item) {
            unset($item['args']);
            $trace[] = $item;
        }
        return $trace;
    }

    /**
     * @throws RemoteException
     */
    public function throw()
    {
        throw $this->getException();
    }

    public function getException()
    {
        $message = sprintf('%s in %s (line %d): %s',
            $this->data['exception'],
            $this->data['file'],
            $this->data['line'],
            $this->data['message']
        );
        return new RemoteException($message, $this->data['code']);
    }

}