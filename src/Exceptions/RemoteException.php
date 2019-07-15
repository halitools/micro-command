<?php

namespace Halitools\MicroCommand\Exceptions;


class RemoteException extends MicroCommandException
{

    protected $trace;

    public function setFile(string $file)
    {
        $this->file = $file;
    }

    public function setLine(string $line)
    {
        $this->line = $line;
    }

}