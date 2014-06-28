<?php
namespace Nap\Application;

use Nap\Controller\ResultInterface;
use \Nap\Serialisation\SerialiserInterface;
use \Symfony\Component\HttpFoundation\Request;

class Responder
{
    /** @var SerialiserInterface[] */
    private $serialisers;

    public function __construct()
    {
        $this->serialisers = array();
    }

    public function registerSerialiser($mimeType, SerialiserInterface $serialiser)
    {
        $this->serialisers[$mimeType] = $serialiser;
    }

    public function respond(
        Request $request,
        ContentNegotiatorInterface $contentNegotiator,
        ResultInterface $result
    ) {
        $mimeType = $contentNegotiator->getBestMatch($request->headers->get("Accept"));
        $serialiser = $this->getSerialiser($mimeType);

        $serialised = $serialiser->serialise($result->getData());

        print $serialised;
    }

    private function getSerialiser($mimeType)
    {
        return $this->serialisers[$mimeType];
    }
}