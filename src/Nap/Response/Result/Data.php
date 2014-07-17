<?php
namespace Nap\Response\Result;


use Nap\Response\BodyResultInterface;

class Data implements BodyResultInterface
{

    /**
     * @var mixed
     */
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Gets response data
     *
     * @return  mixed
     */
    public function getData()
    {
        return $this->data;
    }
}