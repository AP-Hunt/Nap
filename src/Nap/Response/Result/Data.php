<?php
namespace Nap\Response\Result;


use Nap\Response\BodyResultInterface;

class Data implements BodyResultInterface
{

    /**
     * @var array
     */
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Gets response data
     *
     * @return  array
     */
    public function getData()
    {
        return $this->data;
    }
}