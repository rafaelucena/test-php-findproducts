<?php

namespace Recruitment\Services;

class Rules
{
    /** @var string */
    private $name;

    /**
     * @param array $properties
     */
    public function __construct(array $properties)
    {
        $this->fromArray($properties);
    }

    /**
     * @param array $properties
     * @return void
     */
    public function fromArray(array $properties): void
    {
        $this->setName($properties['name']);
    }

    /**
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
