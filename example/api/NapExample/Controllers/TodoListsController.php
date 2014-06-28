<?php
namespace NapExample\Controllers;

use Nap\Controller\Result\Data;

class TodoListsController implements \Nap\Controller\NapControllerInterface
{
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

        return new Data($output);
    }

    public function get(\Symfony\Component\HttpFoundation\Request $request, array $params)
    {
        $strData = file_get_contents(DATA_PATH);
        $json = json_decode($strData, true);

        $id = $params["TodoLists/id"];
        $list = $this->findIdInTodoLists($id, $json["todo-lists"]);
        if($list != null)
        {
            return new Data($list);
        }
        else
        {
            header("HTTP 404 Not Found");
            return new Data(array());
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

    public function post(\Symfony\Component\HttpFoundation\Request $request, array $params)
    {
        // TODO: Implement post() method.
    }

    public function put(\Symfony\Component\HttpFoundation\Request $request, array $params)
    {
        // TODO: Implement put() method.
    }

    public function delete(\Symfony\Component\HttpFoundation\Request $request, array $params)
    {
        // TODO: Implement delete() method.
    }

    public function options(\Symfony\Component\HttpFoundation\Request $request, array $params)
    {
        // TODO: Implement options() method.
    }
}