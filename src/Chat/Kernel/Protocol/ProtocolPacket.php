<?php

declare(strict_types=1);

namespace Chat\Kernel\Protocol;
use Chat\Util\ConverterClass\ToStringTrait;

/**
 * Class ProtocolPacket
 * @package System\Kernel\Protocol
 */
class ProtocolPacket
{
    use ToStringTrait;
    
    /**
     * @var string
     */
    private $data;

    /**
     * @var array
     */
    private $headers;

    /**
     * ProtocolPacket constructor.
     * @param string $data
     * @param array $headers
     */
    public function __construct(string $data, array $headers = [])
    {
        $this->data = $data;
        $this->headers = $headers;
    }

    /**
     * @return string
     */
    public function getData() : string
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function getHeaders() : array
    {
        return $this->headers;
    }
}
