<?php
namespace Nap\Events\ActionDispatcher;

use Nap\Controller\ResultInterface;
use Symfony\Component\EventDispatcher\Event;

class ControllerExecutedEvent extends Event
{

    /**
     * @var \Nap\Controller\ResultInterface
     */
    private $result;
    /**
     * @var string
     */
    private $mimeType;

    public function __construct(
        ResultInterface $result,
        $mimeType
    ) {
        $this->result = $result;
        $this->mimeType = $mimeType;
    }

    /**
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @return \Nap\Controller\ResultInterface
     */
    public function getResult()
    {
        return $this->result;
    }


}