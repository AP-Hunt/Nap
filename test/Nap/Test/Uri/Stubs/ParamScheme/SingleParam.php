<?php
namespace Nap\Test\Uri\Stubs\ParamScheme;


class SingleParam implements \Nap\Resource\Parameter\ParameterScheme
{
    /**
     * @var \Nap\Resource\Parameter\ParameterInterface
     */
    private $singleParam;

    public function __construct(\Nap\Resource\Parameter\ParameterInterface $singleParam)
    {

        $this->singleParam = $singleParam;
    }
    /**
     * @return \Nap\Resource\Parameter\ParameterInterface[]
     */
    public function getParameters()
    {
        return array(
            $this->singleParam
        );
    }
}