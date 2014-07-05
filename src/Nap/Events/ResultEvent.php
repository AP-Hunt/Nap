<?php

namespace Nap\Events;

use Nap\Response\ResultBase;
use Symfony\Component\EventDispatcher\Event;

class ResultEvent extends Event
{
    /** @var ResultBase|null */
    protected $result;

    /** @return ResultBase|null */
    public function getResult()
    {
        return $this->result;
    }

    public function setResult(ResultBase $result)
    {
        $this->result = $result;
    }
} 