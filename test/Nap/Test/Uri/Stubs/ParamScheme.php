<?php
namespace Nap\Test\Uri\Stubs;

class ParamScheme implements \Nap\Resource\Parameter\ParameterScheme
{
    private $params;

    /**
     * @param \Nap\Resource\Parameter\ParameterInterface[] $params
     */
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * @return \Nap\Resource\Parameter\ParameterInterface[]
     */
    public function getParameters()
    {
        return $this->params;
    }
}