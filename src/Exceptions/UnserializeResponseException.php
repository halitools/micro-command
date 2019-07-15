<?php

namespace Halitools\MicroCommand\Exceptions;

class UnserializeResponseException extends MicroCommandException
{
    private $content;

    public function __construct($content)
    {

        parent::__construct('Response was not serialized', 0, null);
        $this->content = $content;
    }

    public function render()
    {
        return $this->content;
    }

}