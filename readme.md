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

### Bootstrapping
```php
require_once(__DIR__."/../vendor/autoload.php");
define("CONTROLLER_NAMESPACE", "\Example\Controllers");

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

$appBuilder = new \Nap\ApplicationBuilder();
$builder->registerSerialiser("application/json", new \Nap\Serialisation\JSON());
$builder->setControllerNamespace(CONTROLLER_NAMESPACE);

$request = new \Symfony\Component\HttpFoundation\Request::createFromGlobals();
$app = $builder->build();

$app->start($request, $resources);
```

### Controller
```php
namespace Example\Controllers;

use Nap\Metadata\Annotations as Nap;
use Nap\Response\ActionResult;
use Nap\Response\Result\Data;
use Nap\Response\Result\HTTP\OK;

class OrganisationController implements \Nap\Controller\NapControllerInterface
{
    /**
     * Allow index to respond in either JSON or XML
     * @Nap\Accept({"application/json", "application/xml"})
     *
     * But default to JSON should the client not send an Accept header
     * @Nap\DefaultMime("application/json")
     */
    public function index(\Symfony\Component\HttpFoundation\Request $request)
    {
        /** @var Organisation[] $organisations **/
        $organisations = $this->repository->getOrganisations();

        // Return an action result with the OK status and some data
        return new ActionResult(new OK(), new Data($organisations));
    }

    // .. And so on for other methods
}
```