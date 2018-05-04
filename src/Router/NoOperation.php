<?php

namespace Router;

class NoOperation
{
    public function __call($name, $arguments)
    {
        return new self;
    }
}