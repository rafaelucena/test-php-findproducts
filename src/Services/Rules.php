<?php

namespace Recruitment\Services;

class Rules
{
    private $name;

    public function __construct(array $properties)
    {
        $this->fromArray($properties);
    }

    public function fromArray(array $properties)
    {
        $this->setName($properties['name']);
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }
}
