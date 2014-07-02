<?php
namespace Nap\Test\Uri\Stubs\ParamScheme;

use \Nap\Test\Uri\Stubs\Param;

class SingleSelfRequiredParam extends SingleParam
{
    public function __construct()
    {
        parent::__construct(new Param("id", true, false, "\d+", "id"));
    }

}