<?php
namespace Nap\Test\Uri\Stubs\ParamScheme;

use \Nap\Test\Uri\Stubs\IntParam;

class SingleChildRequiredIntParam extends SingleParam
{
    public function __construct($identifier = "id")
    {
        parent::__construct(new IntParam("id", false, true, "\d+", $identifier));
    }
}