<?php
namespace NapExample\Controllers\TodoLists;

use Nap\Controller\NapControllerInterface;
use Nap\Controller\Result\Data;

class EntriesController implements NapControllerInterface
{

    public function index(\Symfony\Component\HttpFoundation\Request $request)
    {
        // TODO: Implement index() method.
    }

    public function get(\Symfony\Component\HttpFoundation\Request $request, array $params)
    {
        // TODO: Implement get() method.
    }

    public function post(\Symfony\Component\HttpFoundation\Request $request, array $params)
    {
        // TODO: Implement post() method.
    }

    public function put(\Symfony\Component\HttpFoundation\Request $request, array $params)
    {
        $todoId = $params["TodoLists/id"];
        $entryId = $params["Entries/id"];
        $body = array();
        parse_str($request->getContent(), $body);

        if(!isset($body["complete"]))
        {
            header("HTTP 400 Bad Request");
            return new Data(array());;
        }

        $isComplete = (strtolower($body["complete"]) == "true");

        $strData = file_get_contents(DATA_PATH);
        $json = json_decode($strData, true);

        $list = $this->findTodoListIndex($todoId, $json["todo-lists"]);
        if($list === null)
        {
            header("HTTP 404 Not Found");
            return new Data(array());
        }
        $entry = $this->findEntryIndexInList($entryId, $json["todo-lists"][$list]);
        if($entry === null)
        {
            header("HTTP 404 Not Found");
            return new Data(array());;
        }

        $json["todo-lists"][$list]["items"][$entry]["complete"] = $isComplete;
        $this->writeBackToFile(DATA_PATH, $json);

        return new Data($json["todo-lists"][$list]["items"][$entry]);

    }

    private function findTodoListIndex($todoId, $lists)
    {
        foreach($lists as $i => $lst)
        {
            if($lst["id"] == $todoId){
                return $i;
            }
        }
        return null;
    }

    private function findEntryIndexInList($entryId, $list)
    {
        foreach($list["items"] as $i => $entry)
        {
            if($entry["id"] == $entryId){
                return $i;
            }
        }

        return null;
    }

    private function writeBackToFile($dataPath, array $json)
    {
        $h = fopen($dataPath, "w+");
        fwrite($h, json_encode($json));
        fclose($h);
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