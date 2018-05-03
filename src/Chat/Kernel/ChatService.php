<?php

declare(strict_types=1);

namespace Chat\Kernel;

use Chat\Action\AbstractAction;
use Chat\Entity\InternalProtocol\ResponseCode;
use Chat\Entity\WsMessage;
use Chat\Exception\Protocol\MakeCommandException;
use Chat\Exception\Protocol\ProtocolException;
use Chat\Exception\Protocol\UnknownCommandException;
use Chat\Kernel\Protocol\RequestBundle;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class ChatService
 * @package Chat\Kernel
 */
class ChatService extends BaseChatService
{
    use ChatServiceDependenciesTrait;

    /**
     * SystemService constructor.
     * @param string $configFileFolder
     * @param WsMessage $message
     */
    public function __construct(string $configFileFolder, WsMessage $message)
    {
        parent::__construct($configFileFolder, $message);

        $this->actionsFolder = $configFileFolder . '/actions/';
        $this->commandLocator = new FileLocator($this->actionsFolder);
    }

    /**
     * @throws MakeCommandException
     */
    protected function startSafe(): void
    {
        $this->loadMainConfiguration();
        $this->receiveDependecies();

        $this->makeAction();
    }
    
    private function receiveDependecies(): void
    {
        $dependencyReceiver = new ChatDependencyReceiver($this);
        $dependencyReceiver->buildWithAllDependencies();
    }

    /**
     * @throws MakeCommandException
     */
    private function makeAction(): void
    {
        try {
            $this->makeActionDirectly();
        } catch (ProtocolException $protocolExc) {
            $this->wsMessage->notifySender(json_encode([
                'Result' => $protocolExc->getCode(),
                'Message' => $protocolExc->getMessage(),
                'Time' => date('Y-m-d H:i:s')
            ]));
            
            throw new MakeCommandException();
        } catch (\PDOException $e) {
            $this->wsMessage->notifySender(json_encode([
                'Result' => ResponseCode::DATABASE_ERROR,
                'Message' => 'DataBase error',
                'Time' => date('Y-m-d H:i:s')
            ]));
            
            throw new MakeCommandException();
        } catch (\Throwable $e) {
            $this->wsMessage->notifySender(json_encode([
                'Result' => ResponseCode::UNKNOWN_ERROR,
                'Message' => $e->getMessage()."\n".$e->getFile()."\t".$e->getLine(),
                'Time' => date('Y-m-d H:i:s')
            ]));
            
            throw new MakeCommandException();
        }
    }

    /**
     * @throws UnknownCommandException
     */
    private function makeActionDirectly(): void
    {
        $rows = $this->getFormat()->decode($this->wsMessage->getMessage());
        $requestBundle = new RequestBundle($this->wsMessage, $rows);

        $this->loadCommandConfiguration($requestBundle->getCommand());

        $this->startWithInternalProtocol($requestBundle);
    }

    /**
     * @param string $actionName
     * @throws ProtocolException
     */
    private function loadCommandConfiguration(string $actionName): void
    {
        $loader = new YamlFileLoader($this->getServicesContainer(), $this->commandLocator);
        try {
            $commandFile = lcfirst($actionName) . '.yml';
            $loader->load($commandFile);
        } catch (\Exception $e) {
            $this->getLogger()->debug('Not found action file for ' . $actionName);
            throw new UnknownCommandException('Not found action for ' . $actionName);
        }
    }

    /**
     * @param RequestBundle $request
     * @throws UnknownCommandException
     */
    private function startWithInternalProtocol(RequestBundle $request): void
    {
        $diCommandKey = 'action.' . strtolower($request->getCommand());
        if (!$this->getServicesContainer()->has($diCommandKey)) {
            $this->getLogger()->debug($diCommandKey . ' not provided in di container');
            throw new UnknownCommandException('Action ' . $request->getCommand() . ' not found');
        }

        $command = $this->getDiCommandKey($diCommandKey);
        if (!($command instanceof AbstractAction)) {
            $this->getLogger()->critical(
                'Wrong configuration! ' . $diCommandKey . ' must be instance of AbstractAction',
                ['tags' => ['error'],'object' => $this]
            );
            throw new \LogicException('Wrong configuration! ' . $diCommandKey . ' must be instance of AbstractAction');
        }
        
        $command->handle($request);
    }

    /**
     * @param string $diActionKey
     * @return object
     * @throws UnknownCommandException
     */
    private function getDiCommandKey(string $diActionKey)
    {
        try {
            return $this->getServicesContainer()->get($diActionKey);
        } catch (\Exception $e) {
            $this->getLogger()->critical(
                'Wrong configuration! Class not found for '.$diActionKey.'.',
                ['tags' => ['error'],'object' => $this]
            );
            throw new UnknownCommandException('Class not found for '.$diActionKey);
        }
    }
}
