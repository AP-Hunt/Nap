<?php
namespace Nap\Test\Metadata\Controllers;

use Nap\Metadata\Annotations as Nap;

class Controller implements \Nap\Controller\NapControllerInterface
{
    /**
     * @Nap\Accept({"application/json", "application/xml"})
     * @Nap\DefaultMime("default/get")
     */
    public function get(\Symfony\Component\HttpFoundation\Request $request, array $params)
    {
        // Empty
    }

    /**
     * @Nap\Accept({"application/index"})
     * @Nap\DefaultMime("default/index")
     */
    public function index(\Symfony\Component\HttpFoundation\Request $request)
    {
        // TODO: Implement index() method.
    }

    /**
     * @Nap\Accept({"application/post"})
     * @Nap\DefaultMime("default/post")
     */
    public function post(\Symfony\Component\HttpFoundation\Request $request, array $params)
    {
        // TODO: Implement post() method.
    }

    /**
     * @Nap\Accept({"application/put"})
     * @Nap\DefaultMime("default/put")
     */
    public function put(\Symfony\Component\HttpFoundation\Request $request, array $params)
    {
        // TODO: Implement put() method.
    }

    /**
     * @Nap\Accept({"application/delete"})
     * @Nap\DefaultMime("default/delete")
     */
    public function delete(\Symfony\Component\HttpFoundation\Request $request, array $params)
    {
        // TODO: Implement delete() method.
    }

    /**
     * @Nap\Accept({"application/options"})
     * @Nap\DefaultMime("default/options")
     */
    public function options(\Symfony\Component\HttpFoundation\Request $request, array $params)
    {
        // TODO: Implement options() method.
    }
}
