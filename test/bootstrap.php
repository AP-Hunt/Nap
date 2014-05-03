<?php
require_once(__DIR__."/SplClassLoader.php");

$loader = new SplClassLoader("Nap", __DIR__."/../src/");
$loader->register();