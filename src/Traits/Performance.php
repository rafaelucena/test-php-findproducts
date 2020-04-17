<?php

namespace Recruitment\Traits;

trait Performance
{
    /** @var int */
    private $executionTime;

    /**
     * @param integer $executionTime
     * @return void
     */
    public function setExecutionTime(int $executionTime): void
    {
        $this->executionTime = $executionTime;
    }

    /**
     * @return integer
     */
    public function getExecutionTime(): int
    {
        return $this->executionTime;
    }
}