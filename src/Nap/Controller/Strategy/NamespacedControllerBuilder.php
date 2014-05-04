<?php
namespace Nap\Controller\Strategy;

class NamespacedControllerBuilder implements \Nap\Controller\ControllerBuilderStrategy
{
    /**
     * Builds a Nap controller given a relative path to one
     *
     * @param $controllerRelativePath
     * @return \Nap\Controller\NapControllerInterface
     */
    public function buildController($controllerRelativePath)
    {
        // Strip any extensions off
        $firstDotPos = strpos($controllerRelativePath, ".");
        if($firstDotPos >= 0){
            $controllerRelativePath = substr($controllerRelativePath, 0, $firstDotPos);
        }

        //Replace directory separators with namespace slashes
        $controllerFQN = str_replace(DIRECTORY_SEPARATOR, "\\", $controllerRelativePath);

        // Build and return
        return new $controllerFQN();
    }
}