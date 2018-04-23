<?php

declare(strict_types=1);

namespace Chat\Kernel\Protocol;

use Chat\Util\ToStringTrait;

/**
 * Class ProtocolHeaders
 * @package System\Kernel\Protocol
 */
class ProtocolHeaders
{
    use ToStringTrait;
    
    /**
     * @var string
     */
    private $contentType;

    /**
     * ProtocolHeaders constructor.
     * @param string $contentType
     */
    public function __construct(string $contentType)
    {
        $this->contentType = $contentType;
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }
}
