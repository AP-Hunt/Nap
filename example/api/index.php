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

$app = new \Nap\Application(
    new \Nap\Resource\ResourceMatcher(new \Nap\Uri\MatchableUriBuilder()),
    new \Nap\Controller\Strategy\ConventionResolver(),
    new \Nap\Controller\Strategy\NamespacedControllerBuilder()
);
$app->setResources(require_once(APPLICATION_PATH."/NapExample/resources.php"));
$app->setControllerNamespace("\NapExample\Controllers");

try
{
    $app->start($req);
}
catch (\Nap\Resource\NoMatchingResourceException $ex)
{
    die("No resource found");
}
