# Nap
Nap is my plaything for experimenting with a resource-centric view of building RESTful services in PHP. The aim is to be
convention based, with the only required configuration being the resources themselves. I'm also playing with making it easy to plug in to existing systems, with full support for constructor dependency injection in controllers.

Resources are defined hierarchically, with a single resource only making sense in the context of its parent and children.
The following is an example of the envisioned configuration of resources, stored in a separate PHP file.

```php
<?php
use \Nap\Resource\Resource;
use \Nap\Resource\Parameter;

return array(
    new Resource("Organisation", "/organisation", new Scheme\Id(), array(
        new Resource("Teams", "/teams", new Scheme\Id(), array(
            new Resource("People", "/people")
        ))
    )),

    new Resource("Project", "/project")
)
```

A resource definition as above would build the following urls

    /organisation/
    /organisation/<id>
    /organisation/<id>/teams/
    /organisation/<id>/teams/<id>
    /organisation/<id>/teams/<id>/people/
    /project/

Each of these urls would be routed to the applicable controller (`OrganisationController` in the case of `/organisation/` for example)
and the correct method, based on the HTTP verb and number of parameters.

## Basic usage example
The following example assumes you already have autoloading for the controller namespace defined

```php
require_once(__DIR__."/../vendor/autoload.php");

use \Nap\Resource\Resource;
use \Nap\Resource\Parameter\Scheme;

// Resources could from an external file
// ie $resources = require_once("path/to/definition.php");
$resources = array(
    new Resource("Organisation", "/organisation", new Scheme\Id(true), array(
        new Resource("Teams", "/teams", new Scheme\Id(true), array(
            new Resource("People", "/people")
        ))
    )),

    new Resource("Project", "/project", new Scheme\Id())
);

$app = new \New\Application(
    new \Nap\Resource\ResourceMatcher(new \Nap\Uri\MatchableUriBuilder()),
    new \Nap\Controller\Strategy\ConventionResolver(),
    new \Nap\Controller\Strategy\NamespacedControllerBuilder()
);
$app->setResources($resources);
$request = new \Symfony\Component\HttpFoundation\Request::createFromGlobals();

try{
    $app->start($request);
}
catch(\Nap\Resource\NoMatchingResourceException $ex){
    die("No matching resource could be found");
}
```