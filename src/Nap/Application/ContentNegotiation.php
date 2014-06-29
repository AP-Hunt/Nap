<?php
namespace Nap\Application;

/**
 * Standard content negotiator.
 *
 * Wrapper around package willdurand/negotiation
 */
class ContentNegotiation implements ContentNegotiatorInterface
{
    /** @var    \Negotiation\NegotiatorInterface */
    private $contentNegotiator;

    public function __construct()
    {
        $this->contentNegotiator = new \Negotiation\FormatNegotiator();
    }

    /**
     * Gets the best matching header value from the given values
     *
     * @param $header
     * @param array $priorities
     * @return string
     */
    public function getBestMatch($header, array $priorities = array())
    {
        return $this->contentNegotiator->getBest($header, $priorities)->getValue();
    }
}