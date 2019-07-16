<?php

namespace Halitools\MicroCommand\Response;

use Halitools\MicroCommand\Exceptions\RemoteException;

interface ExceptionResponseInterface
{
    public static function build(\Exception $exception): ExceptionResponseInterface;

    /**
     * @throws RemoteException
     */
    public function throw();

    public function getException();
}