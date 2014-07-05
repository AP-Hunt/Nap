<?php

namespace Nap\Events;

use Nap\Response\ActionResult;
use Symfony\Component\EventDispatcher\Event;

class ResultEvent extends Event
{
    /** @var ActionResult|null */
    protected $result;

    /** @return ActionResult|null */
    public function getResult()
    {
        return $this->result;
    }

    public function setResult(ActionResult $result)
    {
        $this->result = $result;
    }
} 