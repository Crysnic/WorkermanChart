<?php

declare(strict_types=1);

namespace Chat\Kernel;
use Chat\Kernel\Protocol\FormatInterface;
use Chat\Kernel\Protocol\ProtocolInterface;
use Symfony\Component\Config\FileLocator;

/**
 * Class SystemServiceDependenciesTrait
 * @package System\Kernel
 */
trait ChatServiceDependenciesTrait
{
    /**
     * @var FileLocator
     */
    private $commandLocator;

    /**
     * @var string
     */
    private $actionsFolder;

    /**
     * @var FormatInterface
     */
    private $format;

    /**
     * @var ProtocolInterface
     */
    private $protocol;

    /**
     * @return FormatInterface
     */
    public function getFormat(): FormatInterface
    {
        return $this->format;
    }

    /**
     * @param FormatInterface $format
     */
    public function setFormat(FormatInterface $format)
    {
        $this->format = $format;
    }

    /**
     * @return ProtocolInterface
     */
    public function getProtocol(): ProtocolInterface
    {
        return $this->protocol;
    }

    /**
     * @param ProtocolInterface $protocol
     */
    public function setProtocol(ProtocolInterface $protocol)
    {
        $this->protocol = $protocol;
    }
}
