<?php

declare(strict_types=1);

namespace Chat\Kernel\Protocol;

/**
 * Interface ProtocolInterface
 * @package System\Kernel\Protocol
 */
interface ProtocolInterface
{
    /**
     * @return ProtocolPacket
     */
    public function getIncomingPacket() : ProtocolPacket;

    /**
     * @param ProtocolPacket $packet
     * @return void
     */
    public function sendResponse(ProtocolPacket $packet);
}
