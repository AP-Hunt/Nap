<?php
namespace NapExample\Controllers\TodoLists;

use Nap\Metadata\Annotations as Nap;
use Nap\Controller\NapControllerInterface;
use Nap\Response\ActionResult;
use Nap\Response\Result\Data;
use Nap\Response\Result\HTTP\BadRequest;
use Nap\Response\Result\HTTP\NotFound;
use Nap\Response\Result\HTTP\OK;

class EntriesController implements NapControllerInterface
{
    /**
     * @Nap\Accept({"application/json"})
     * @Nap\DefaultMime("application/json")
     */
    public function index(\Symfony\Component\HttpFoundation\Request $request)
    {
        // TODO: Implement index() method.
    }

    /**
     * @Nap\Accept({"application/json"})
     * @Nap\DefaultMime("application/json")
     */
    public function get(\Symfony\Component\HttpFoundation\Request $request, array $params)
    {
        // TODO: Implement get() method.
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
        $todoId = $params["TodoLists/id"];
        $entryId = $params["Entries/id"];
        $body = array();
        parse_str($request->getContent(), $body);

        if(!isset($body["complete"]))
        {
            header("HTTP 400 Bad Request");
            return new ActionResult(new BadRequest(), new Data(array()));
        }

        $isComplete = (strtolower($body["complete"]) == "true");

        $strData = file_get_contents(DATA_PATH);
        $json = json_decode($strData, true);

        $list = $this->findTodoListIndex($todoId, $json["todo-lists"]);
        if($list === null)
        {
            return new ActionResult(new NotFound(), new Data(array()));
        }
        $entry = $this->findEntryIndexInList($entryId, $json["todo-lists"][$list]);
        if($entry === null)
        {
            return new ActionResult(new NotFound(), new Data(array()));
        }

        $json["todo-lists"][$list]["items"][$entry]["complete"] = $isComplete;
        $this->writeBackToFile(DATA_PATH, $json);

        return new ActionResult(new OK(), new Data($json["todo-lists"][$list]["items"][$entry]));

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