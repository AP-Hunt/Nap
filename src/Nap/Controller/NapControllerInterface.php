<?php
namespace Nap\Controller;


interface NapControllerInterface {
    public function index(\Symfony\Component\HttpFoundation\Request $request);
    public function get(\Symfony\Component\HttpFoundation\Request $request, array $params);
    public function post(\Symfony\Component\HttpFoundation\Request $request, array $params);
    public function put(\Symfony\Component\HttpFoundation\Request $request, array $params);
    public function delete(\Symfony\Component\HttpFoundation\Request $request, array $params);
    public function options(\Symfony\Component\HttpFoundation\Request $request, array $params);
}