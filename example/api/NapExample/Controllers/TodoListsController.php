<?php
namespace NapExample\Controllers;

use Nap\Metadata\Annotations as Nap;
use Nap\Response\ActionResult;
use Nap\Response\Result\Data;
use Nap\Response\Result\HTTP\NotFound;
use Nap\Response\Result\HTTP\OK;

class TodoListsController implements \Nap\Controller\NapControllerInterface
{
    /**
     * @Nap\Accept({"application/json"})
     * @Nap\DefaultMime("application/json")
     */
    public function index(\Symfony\Component\HttpFoundation\Request $request)
    {
        $strData = file_get_contents(DATA_PATH);
        $json = json_decode($strData, true);

        $output = array_map(function(array $list) {
            return array(
                "id" => $list["id"],
                "name" => $list["name"]
            );
        }, $json["todo-lists"]);

        return new ActionResult(new OK(), new Data($output));
    }

    /**
     * @Nap\Accept({"application/json"})
     * @Nap\DefaultMime("application/json")
     */
    public function get(\Symfony\Component\HttpFoundation\Request $request, array $params)
    {
        $strData = file_get_contents(DATA_PATH);
        $json = json_decode($strData, true);

        $id = $params["TodoLists/id"];
        $list = $this->findIdInTodoLists($id, $json["todo-lists"]);
        if($list != null)
        {
            return new ActionResult(new OK(), new Data($list));
        }
        else
        {
            return new ActionResult(new NotFound(), new Data(array()));
        }

    }

    private function findIdInTodoLists($id, array $todoLists)
    {
        foreach($todoLists as $list){
            if($list["id"] === $id){
                return $list;
            }
        }

        return null;
    }

    /**
     * @Nap\Accept({"application/json"})
     * @Nap\DefaultMime("application/json")
     */
    public function post(\Symfony\Component\HttpFoundation\Request $request, array $params)
    {
        // TODO: Implement post() method.
    }

    /**
     * @Nap\Accept({"application/json"})
     * @Nap\DefaultMime("application/json")
     */
    public function put(\Symfony\Component\HttpFoundation\Request $request, array $params)
    {
        // TODO: Implement put() method.
    }

    /**
     * @Nap\Accept({"application/json"})
     * @Nap\DefaultMime("application/json")
     */
    public function delete(\Symfony\Component\HttpFoundation\Request $request, array $params)
    {
        // TODO: Implement delete() method.
    }

    /**
     * @Nap\Accept({"application/json"})
     * @Nap\DefaultMime("application/json")
     */
    public function options(\Symfony\Component\HttpFoundation\Request $request, array $params)
    {
        // TODO: Implement options() method.
    }
}