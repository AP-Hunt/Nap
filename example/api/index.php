<?php
// Constants and autoloading
define("APPLICATION_PATH", realpath(__DIR__));
define("DATA_PATH", realpath(__DIR__."/data.json"));

require_once(APPLICATION_PATH."/vendor/autoload.php");
require_once(APPLICATION_PATH."/SplClassLoader.php");

$loader = new SplClassLoader("NapExample", APPLICATION_PATH);
$loader->register();

// Application bootstrapping
$req = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
$resources = require_once(APPLICATION_PATH."/NapExample/resources.php");

$builder = new \Nap\ApplicationBuilder();
$builder->registerSerialiser("application/json", new \Nap\Serialisation\JSON());
$builder->setControllerNamespace("NapExample\Controllers");

$app = $builder->build();

try
{
    $app->start($req, $resources);
}
catch (\Nap\Resource\NoMatchingResourceException $ex)
{
    die("No resource found");
}
