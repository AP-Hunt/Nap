<?php
use \Nap\Resource\Resource;
use \Nap\Resource\Parameter\Scheme;

return array(
    new Resource("TodoLists", "/todo-lists", new Scheme\Id($required=false))
);