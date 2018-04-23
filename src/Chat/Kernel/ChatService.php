<?php

declare(strict_types=1);

namespace Chat\Kernel;

use Chat\Entity\InternalProtocol\ResponseCode;
use Chat\Exception\Protocol\ProtocolException;
use Chat\Exception\Protocol\UnknownCommandException;
use Chat\Kernel\Protocol\AnswerBundle;
use Chat\Kernel\Protocol\ProtocolPacket;
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
     * @param string $environment
     */
    public function __construct(string $configFileFolder, string $environment)
    {
        parent::__construct($configFileFolder, $environment);

        $this->commandsFolder = $configFileFolder . '/commands/';
        $this->commandLocator = new FileLocator($this->commandsFolder);
    }


    /**
     *
     */
    protected function startSafe(): void
    {
        $this->loadMainConfiguration();
        $this->receiveDependecies();

        $answerBundle = $this->makeAction();

        $this->sendAnswer($answerBundle);
    }

    /**
     *
     */
    private function receiveDependecies(): void
    {
        $dependencyReceiver = new ChatDependencyReceiver($this);
        $dependencyReceiver->buildWithAllDependencies();
    }

    /**
     * @return AnswerBundle
     */
    private function makeAction(): AnswerBundle
    {
        try {
            return $this->makeActionDirectly();
        } catch (ProtocolException $protocolExc) {
            $params = [
                'Result' => $protocolExc->getCode(),
                'Message' => $protocolExc->getMessage(),
                'Time' => date('Y-m-d H:i:s')
            ];
            return new AnswerBundle($params);
        } catch (\PDOException $e) {
            $params = [
                'Result' => ResponseCode::DATABASE_ERROR,
                'Message' => 'DataBase error',
                'Time' => date('Y-m-d H:i:s')
            ];
            return new AnswerBundle($params);
        } catch (\Throwable $e) {
            $params = [
                'Result' => ResponseCode::UNKNOWN_ERROR,
                'Message' => "Unknown error",
                'Time' => date('Y-m-d H:i:s')
            ];
            return new AnswerBundle($params);
        }
    }

    /**
     * @return AnswerBundle
     */
    private function makeActionDirectly(): AnswerBundle
    {
        $packet = $this->getProtocol()->getIncomingPacket();

        $this->logRequest($packet->getData());

        $rows = $this->getFormat()->decode($packet->getData());
        $requestBundle = new RequestBundle(
            $packet->getData(),
            $rows,
            md5(microtime())
        );

        $this->loadCommandConfiguration($requestBundle->getCommand());

        return $this->startWithInternalProtocol($requestBundle);
    }

    /**
     * @param string $data
     */
    private function logRequest(string $data)
    {
        $this->getLogger()->log('info', "Prepare data", [
                "data" => $data,
                "tags" => ["api", "request_data_prepared"],
                "remote_addr" => $this->getClientIp()
            ]
        );
    }

    /**
     * @return string
     */
    private function getClientIp(): string
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return (string) $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return (string) $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return (string) $_SERVER['REMOTE_ADDR'];
        }
    }

    /**
     * @param string $actionName
     * @throws ProtocolException
     */
    private function loadCommandConfiguration(string $actionName)
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
     * @return AnswerBundle
     * @throws UnknownCommandException
     */
    private function startWithInternalProtocol(RequestBundle $request): AnswerBundle
    {
        $diCommandKey = 'command.' . strtolower($request->getCommand());
        if (!$this->getServicesContainer()->has($diCommandKey)) {
            $this->getLogger()->debug($diCommandKey . ' not provided in di container');
            throw new UnknownCommandException('Command ' . $request->getCommand() . ' not found');
        }

        $action = $this->getDiCommandKey($diCommandKey);
        if (!($action instanceof AbstractAction)) {
            $this->getLogger()->critical(
                'Wrong configuration! ' . $diCommandKey . ' must be instance of AbstractAction',
                ['tags' => ['error'],'object' => $this]
            );
            throw new \LogicException('Wrong configuration! ' . $diCommandKey . ' must be instance of AbstractAction');
        }

        $action->setServicesContainer($this->getServicesContainer());

        return $action->handle($request);
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

    /**
     * @param AnswerBundle $answerBundle
     */
    private function sendAnswer(AnswerBundle $answerBundle)
    {
        $responseBody = $this->getFormat()->encode($answerBundle);

        $this->getProtocol()->sendResponse(
            new ProtocolPacket($responseBody, ['Content-Type: json'])
        );
    }
}
