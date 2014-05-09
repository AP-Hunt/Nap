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

    public function __construct($name, $required, $matchingExpression)
    {

        $this->name = $name;
        $this->required = $required;
        $this->matchingExpression = $matchingExpression;
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
}

class Stub_IntParam extends Stub_Param
{
    public function convertValue($value)
    {
        return filter_var($value, FILTER_VALIDATE_INT);
    }
}


class Stub_ParamScheme_SingleRequiredParam implements \Nap\Resource\Parameter\ParameterScheme
{
    /**
     * @return \Nap\Resource\Parameter\ParameterInterface[]
     */
    public function getParameters()
    {
        return array(
            new Stub_Param("id", true, "\d+")
        );
    }
}

class Stub_ParamScheme_SingleOptionalParam implements \Nap\Resource\Parameter\ParameterScheme
{
    /**
     * @return \Nap\Resource\Parameter\ParameterInterface[]
     */
    public function getParameters()
    {
        return array(
            new Stub_Param("id", false, "\d+")
        );
    }
}

class Stub_ParamScheme_SingleIntParam implements \Nap\Resource\Parameter\ParameterScheme
{

    /**
     * @return \Nap\Resource\Parameter\ParameterInterface[]
     */
    public function getParameters()
    {
        return array(
            new Stub_IntParam("id", true, "\d+")
        );
    }
}