<?php
namespace Nap\Controller;


interface NapControllerInterface {
    public function get(\Symfony\Component\HttpFoundation\Request $request);
    public function post(\Symfony\Component\HttpFoundation\Request $request);
    public function put(\Symfony\Component\HttpFoundation\Request $request);
    public function delete(\Symfony\Component\HttpFoundation\Request $request);
    public function options(\Symfony\Component\HttpFoundation\Request $request);
}