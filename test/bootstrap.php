<?php
require_once(__DIR__."/../vendor/autoload.php");
require_once(__DIR__."/SplClassLoader.php");


$testLoader = new SplClassLoader("Nap\\Test", __DIR__."/../test/");
$testLoader->register();

$loader = new SplClassLoader("Nap", __DIR__."/../src/");
$loader->register();