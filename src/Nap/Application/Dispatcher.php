<?php
namespace Nap\Application;


class Dispatcher
{
    /**
     * @param   \Nap\Controller\NapControllerInterface      $controller
     * @param   \Symfony\Component\HttpFoundation\Request   $request
     * @param   array                                       $parameters
     *
     * @return  \Nap\Controller\ResultInterface
     */
    public function dispatchMethod(
        \Nap\Controller\NapControllerInterface $controller,
        \Symfony\Component\HttpFoundation\Request $request,
        array $parameters
    ) {
        $controllerMethodCall = function($method) use($controller, $request, $parameters){
            return $controller->{$method}($request, $parameters);
        };

        $controllerIndexCall = function() use($controller, $request){
            return $controller->index($request);
        };

        switch(strtolower($request->getMethod()))
        {
            case "get":
                if(count($parameters) === 0){
                    return $controllerIndexCall();
                    break;
                }

                return $controllerMethodCall("get");
                break;

            case "post":
                return $controllerMethodCall("post");
                break;

            case "put":
                return $controllerMethodCall("put");
                break;

            case "delete":
                return $controllerMethodCall("delete");
                break;

            case "options":
                return $controllerMethodCall("options");
                break;
        }
    }
} 