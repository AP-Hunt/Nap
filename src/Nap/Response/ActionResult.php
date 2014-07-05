<?php
namespace Nap\Response;


class ActionResult
{
    /**
     * @var HeaderResultsInterface
     */
    private $headers;

    /**
     * @var BodyResultInterface
     */
    private $data;

    public function __construct(
        HeaderResultsInterface $headers,
        BodyResultInterface $data
    ) {

        $this->headers = $headers;
        $this->data = $data;
    }

    /**
     * @return \Nap\Response\BodyResultInterface
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return \Nap\Response\HeaderResultsInterface
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param \Nap\Response\BodyResultInterface $data
     */
    public function setData(BodyResultInterface $data)
    {
        $this->data = $data;
    }

    /**
     * @param \Nap\Response\HeaderResultsInterface $headers
     */
    public function setHeaders(HeaderResultsInterface $headers)
    {
        $this->headers = $headers;
    }

}