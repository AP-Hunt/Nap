<?php


namespace Nap;


use Doctrine\Common\Annotations\AnnotationReader;
use Nap\Application\ActionDispatcher;
use Nap\Application\ContentNegotiation;
use Nap\Controller\ControllerBuilderStrategy;
use Nap\Controller\Strategy\ConventionResolver;
use Nap\Controller\Strategy\NamespacedControllerBuilder;
use Nap\Metadata\ControllerMetadataProvider;
use Nap\Metadata\Driver\AnnotationDriver;
use Nap\Metadata\Driver\DriverInterface;
use Nap\Resource\ResourceMatcher;
use Nap\Response\Responder;
use Nap\Serialisation\SerialiserInterface;
use Nap\Serialisation\SerialiserRegistry;
use Nap\Uri\MatchableUriBuilder;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;

class ApplicationBuilder
{
    /** @var \Symfony\Component\EventDispatcher\EventDispatcher */
    private $eventDispatcher;

    /** @var Resource\ResourceMatcher */
    private $matcher;

    /** @var Application\ActionDispatcher */
    private $actionDispatcher;

    /** @var Responder */
    private $responder;

    /** @var Serialisation\SerialiserRegistry */
    private $serialiserRegistry;

    /** @var \Nap\Controller\ControllerBuilderStrategy */
    private $controllerBuilder;

    /** @var \Nap\Metadata\Driver\DriverInterface */
    private $metadataDriver;

    public function __construct()
    {
        $this->eventDispatcher = new EventDispatcher();
        $this->matcher = new ResourceMatcher(
            new MatchableUriBuilder()
        );

        $this->controllerBuilder = new NamespacedControllerBuilder(
            new ConventionResolver()
        );

        $this->metadataDriver = new AnnotationDriver(
            new AnnotationReader()
        );

        $this->actionDispatcher = new ActionDispatcher(
            $this->eventDispatcher,
            $this->controllerBuilder,
            new ControllerMetadataProvider(
                $this->metadataDriver
            ),
            new ContentNegotiation()
        );

        $this->responder = new Responder(
            new Response()
        );

        $this->serialiserRegistry = new SerialiserRegistry();
    }

    public function build()
    {
        return new Application(
            $this->getEventDispatcher(),
            $this->getMatcher(),
            $this->getActionDispatcher(),
            $this->getSerialiserRegistry(),
            $this->getResponder()
        );
    }

    /**
     * ----------
     * Utility methods for settings
     * ----------
     */

    public function setControllerNamespace($FQN)
    {
        $this->getControllerBuilder()->setControllerRootNamesapce($FQN);
    }

    public function registerSerialiser($mimeType, SerialiserInterface $serialiser)
    {
        $this->getSerialiserRegistry()->registerSerialiser(
            $mimeType,
            $serialiser
        );
    }

    /**
     * ----------
     * Get and set all major components
     * ----------
     */

    /**
     * @return \Symfony\Component\EventDispatcher\EventDispatcher
     */
    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }

    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @return \Nap\Resource\ResourceMatcher
     */
    public function getMatcher()
    {
        return $this->matcher;
    }

    /**
     * @param \Nap\Resource\ResourceMatcher $matcher
     */
    public function setMatcher(ResourceMatcher $matcher)
    {
        $this->matcher = $matcher;
    }

    /**
     * @return \Nap\Application\ActionDispatcher
     */
    public function getActionDispatcher()
    {
        return $this->actionDispatcher;
    }

    /**
     * @param \Nap\Application\ActionDispatcher $actionDispatcher
     */
    public function setActionDispatcher(ActionDispatcher $actionDispatcher)
    {
        $this->actionDispatcher = $actionDispatcher;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response\Responder
     */
    public function getResponder()
    {
        return $this->responder;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Response\Responder $responder
     */
    public function setResponder(Responder $responder)
    {
        $this->responder = $responder;
    }

    /**
     * @return \Nap\Serialisation\SerialiserRegistry
     */
    public function getSerialiserRegistry()
    {
        return $this->serialiserRegistry;
    }

    /**
     * @param \Nap\Serialisation\SerialiserRegistry $serialiserRegistry
     */
    public function setSerialiserRegistry(SerialiserRegistry $serialiserRegistry)
    {
        $this->serialiserRegistry = $serialiserRegistry;
    }

    /**
     * @return \Nap\Controller\Strategy\NamespacedControllerBuilder
     */
    public function getControllerBuilder()
    {
        return $this->controllerBuilder;
    }

    /**
     * @param \Nap\Controller\Strategy\NamespacedControllerBuilder $controllerBuilder
     */
    public function setControllerBuilder(ControllerBuilderStrategy $controllerBuilder)
    {
        $this->controllerBuilder = $controllerBuilder;
    }

    /**
     * @return \Nap\Metadata\Driver\DriverInterface
     */
    public function getMetadataDriver()
    {
        return $this->metadataDriver;
    }

    /**
     * @param \Nap\Metadata\Driver\DriverInterface $metadataDriver
     */
    public function setMetadataDriver(DriverInterface $metadataDriver)
    {
        $this->metadataDriver = $metadataDriver;
    }
} 