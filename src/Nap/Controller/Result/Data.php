<?php
namespace Nap\Controller\Result;


use Nap\Controller\ResultInterface;

class Data implements ResultInterface
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