<?php
class Stub_Param implements \Nap\Resource\Parameter\ParameterInterface
{
    private $name;
    private $requiredForSelf;
    private $requiredForChildren;
    private $matchingExpression;

    public function __construct($name, $requiredForSelf, $requiredForChildren, $matchingExpression, $identifier)
    {

        $this->name = $name;
        $this->requiredForSelf = $requiredForSelf;
        $this->requiredForChildren = $requiredForChildren;
        $this->matchingExpression = $matchingExpression;
        $this->identifier = $identifier;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns a regular expression matching the parameter
     *
     * @return string
     */
    public function getMatchingExpression()
    {
        return $this->matchingExpression;
    }

    /**
     * Converts the matched value in to the intended data type
     *
     * @param   string $value
     * @return  mixed
     */
    public function convertValue($value)
    {
        // TODO: Implement convertValue() method.
    }

    /**
     * Get a unique identifier for this parameter, different from the name.
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Whether the parameter is mandatory for this resource
     *
     * @return boolean
     */
    public function isRequiredForSelf()
    {
        return $this->requiredForSelf;
    }

    /**
     * Whether the parameter is mandatory for this resource's children
     *
     * @return boolean
     */
    public function isRequiredForChildren()
    {
        return $this->requiredForChildren;
    }
}

class Stub_IntParam extends Stub_Param
{
    public function convertValue($value)
    {
        return filter_var($value, FILTER_VALIDATE_INT);
    }

}

class Stub_ParamScheme implements \Nap\Resource\Parameter\ParameterScheme
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

class Stub_ParamScheme_SingleParam implements \Nap\Resource\Parameter\ParameterScheme
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

class Stub_ParamScheme_SingleSelfRequiredParam extends Stub_ParamScheme_SingleParam
{
    public function __construct()
    {
        parent::__construct(new Stub_Param("id", true, false, "\d+", "id"));
    }

}

class Stub_ParamScheme_SingleSelfOptionalParam extends Stub_ParamScheme_SingleParam
{
    public function __construct()
    {
        parent::__construct(new Stub_Param("id", false, false, "\d+", "id"));
    }
}

class Stub_ParamScheme_SingleSelfRequiredIntParam extends Stub_ParamScheme_SingleParam
{
    public function __construct($identifier = "id")
    {
        parent::__construct(new Stub_IntParam("id", true, false, "\d+", $identifier));
    }
}

class Stub_ParamScheme_SingleChildRequiredIntParam extends Stub_ParamScheme_SingleParam
{
    public function __construct($identifier = "id")
    {
        parent::__construct(new Stub_IntParam("id", false, true, "\d+", $identifier));
    }
}