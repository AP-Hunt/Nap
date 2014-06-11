<?php
namespace Nap\Resource\Parameter\Scheme;


use Nap\Resource\Parameter\IntParam;
use Nap\Resource\Parameter\ParameterScheme;

class Id implements ParameterScheme
{
    /**
     * @var array
     */
    private $params;

    public function __construct($selfRequired = false, $childRequired = true)
    {
        $this->params = array(
            new IntParam("id", $selfRequired, $childRequired)
        );
    }
    /**
     * @return \Nap\Resource\Parameter\ParameterInterface[]
     */
    public function getParameters()
    {
        return $this->params;
    }
}