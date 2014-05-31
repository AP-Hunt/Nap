<?php
class Stub_Param implements \Nap\Resource\Parameter\ParameterInterface
{
    /**
     * @var
     */
    private $name;
    /**
     * @var
     */
    private $required;
    /**
     * @var
     */
    private $matchingExpression;

    public function __construct($name, $required, $matchingExpression, $identifier)
    {

        $this->name = $name;
        $this->required = $required;
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
     * Whether the parameter is mandatory within the route
     *
     * @return boolean
     */
    public function isRequired()
    {
        return $this->required;
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
}

class Stub_IntParam extends Stub_Param
{
    public function convertValue($value)
    {
        return filter_var($value, FILTER_VALIDATE_INT);
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

class Stub_ParamScheme_SingleRequiredParam extends Stub_ParamScheme_SingleParam
{
    public function __construct()
    {
        parent::__construct(new Stub_Param("id", true, "\d+", "id"));
    }

}

class Stub_ParamScheme_SingleOptionalParam extends Stub_ParamScheme_SingleParam
{
    public function __construct()
    {
        parent::__construct(new Stub_Param("id", false, "\d+", "id"));
    }
}

class Stub_ParamScheme_SingleIntParam extends Stub_ParamScheme_SingleParam
{
    public function __construct($identifier = "id")
    {
        parent::__construct(new Stub_IntParam("id", true, "\d+", $identifier));
    }
}