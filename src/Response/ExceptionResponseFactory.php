<?php


namespace Halitools\MicroCommand\Response;


class ExceptionResponseFactory
{

    public static function make(\Exception $exception): ExceptionResponse
    {
        $trace = [];
        foreach ($exception->getTrace() as $item) {
            unset($item['args']);
            $trace[] = $item;
        }
        return new ExceptionResponse(
            get_class($exception),
            self::createMessage($exception),
            $exception->getCode(),
            $exception->getFile(),
            $exception->getLine(),
            $trace
        );
    }

    private static function createMessage(\Exception $exception): string
    {
        return get_class($exception) . ' in ' . $exception->getFile() . ' (line ' . $exception->getLine() .'): ' .  $exception->getMessage();
    }

}