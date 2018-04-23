<?php

//declare(strict_types=1);

namespace Chat\Kernel\Protocol;

use Chat\Util\Logging\LoggerReferenceTrait;

/**
 * Class PostJsonProtocol
 * @package System\Kernel\Protocol
 */
class PostProtocol implements ProtocolInterface
{
    use LoggerReferenceTrait;

    /**
     * PostProtocol constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return ProtocolPacket
     */
    public function getIncomingPacket() : ProtocolPacket
    {
        $headers = [];
        if (isset($_SERVER['CONTENT_TYPE'])) {
            $headers['CONTENT_TYPE'] = $_SERVER['CONTENT_TYPE'];
        }
        
        $packet = new ProtocolPacket(
            file_get_contents("php://input"),
            $headers
        );
        
        return $packet;
    }

    /**
     * @param ProtocolPacket $packet
     * @return void
     */
    public function sendResponse(ProtocolPacket $packet)
    {
        if (!empty($packet->getHeaders())) {
            foreach ($packet->getHeaders() as $header) {
                header($header);
            }
        }
        
        echo $packet->getData();
    }
}
