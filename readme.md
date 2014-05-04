# Nap
Nap is my plaything for experimenting with a resource-centric view of building RESTful services in PHP. The aim is to be
convention based, with the only required configuration being the resources themselves. I'm also playing with making it easy to plug in to existing systems, with full support for constructor dependency injection in controllers.

Resources are defined hierarchically, with a single resource only making sense in the context of its parent and children.
The following is an example of the envisioned configuration of resources, stored in a separate PHP file.

```php
<?php
use \Nap\Resource\Resource;

return array(
    new Resource("Organisation", "/organisation", array(
        new Resource("Team", "/team", array(
            new Resource("People", "/people")
        ))
    )),

    new Resource("Project", "/project")
)
```

A resource definition as above would build relevant URLs. A team with id 3 in organisation with id 1 would be accessible through the following URL, given the above configuration

    /organisation/1/team/3