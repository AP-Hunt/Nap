<?php
namespace Nap\Application;


interface ContentNegotiatorInterface
{
    /**
     * Gets the best matching header value from the given values
     *
     * @param $header
     * @param array $priorities
     * @return string
     */
    public function getBestMatch($header, array $priorities = array());
} 