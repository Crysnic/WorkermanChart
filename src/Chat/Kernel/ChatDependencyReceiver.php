<?php

declare(strict_types=1);

namespace Chat\Kernel;

use Chat\Exception\DiException;
use Chat\Kernel\Protocol\FormatInterface;
use Chat\Kernel\Protocol\ProtocolInterface;
use Psr\Log\LoggerInterface;

/**
 * Class ChatDependencyReceiver
 * @package Chat\Kernel
 */
class ChatDependencyReceiver
{
    /**
     * @var ChatService
     */
    private $chatService;

    /**
     * ChatDependencyReceiver constructor.
     * @param ChatService $chatService
     */
    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    /**
     * @throws DiException
     */
    public function build()
    {
        $this->receiveLogger();
        $this->receiveFormat();
        $this->receiveProtocol();
    }

    /**
     * @throws DiException
     * @throws \LogicException
     * @throws \Exception
     */
    private function receiveLogger()
    {
        if (!$this->chatService->getServicesContainer()->has('logger')) {
            $this->chatService->getLogger()->critical(
                'Logger not provided in dependency injection',
                ['tags' => ['error']]
            );
            throw new DiException('Logger');
        }

        $logger = $this->chatService->getServicesContainer()->get('logger');

        if (!($logger instanceof LoggerInterface)) {
            throw new \LogicException();
        }

        $this->chatService->setLogger($logger);
    }

    /**
     * @throws DiException
     * @throws \LogicException
     * @throws \Exception
     */
    private function receiveFormat()
    {
        if (!$this->chatService->getServicesContainer()->has('format')) {
            $this->chatService->getLogger()->critical(
                'Format not provided in dependency injection',
                ['tags' => ['error']]
            );
            throw new DiException('Format');
        }

        $format = $this->chatService->getServicesContainer()->get('format');
        if (!($format instanceof FormatInterface)) {
            $this->chatService->getLogger()->critical(
                'Format is not instance of FormatInterface',
                ['tags' => ['error']]
            );
            throw new \LogicException();
        }

        $this->chatService->setFormat($format);
    }

    /**
     * @throws DiException
     * @throws \LogicException
     * @throws \Exception
     */
    private function receiveProtocol()
    {
        if (!$this->chatService->getServicesContainer()->has('protocol')) {
            $this->chatService->getLogger()->critical(
                'Protocol not provided in dependency injection',
                ['tags' => ['error']]
            );
            throw new DiException('Protocol');
        }

        $protocol = $this->chatService->getServicesContainer()->get('protocol');
        if (!($protocol instanceof ProtocolInterface)) {
            $this->chatService->getLogger()->critical(
                'Protocol is not instance of ProtocolInterface',
                ['tags' => ['error']]
            );
            throw new \LogicException();
        }

        $this->chatService->setProtocol($protocol);
    }
}
