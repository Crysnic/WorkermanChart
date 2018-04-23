<?php

declare(strict_types=1);

namespace Chat\Kernel;

use Chat\Exception\PhpException;
use Chat\Util\Logging\LoggerReference;
use Chat\Util\Logging\LoggerReferenceTrait;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Chat\Entity\InternalProtocol\ResponseCode;

/**
 * Class ChatService
 * @package Chat\Kernel
 */
abstract class BaseChatService implements LoggerReference
{
    use LoggerReferenceTrait;

    /**
     * @var string
     */
    private $environment;

    /**
     * @var ContainerBuilder
     */
    private $servicesContainer;

    /**
     * @var FileLocator
     */
    protected $mainLocator;

    /**
     * @var string[]
     */
    protected $loadedActionsFiles = [];

    /**
     * @return ContainerBuilder
     */
    public function getServicesContainer() : ContainerBuilder
    {
        return $this->servicesContainer;
    }

    /**
     * @param ContainerBuilder $servicesContainer
     * @return void
     */
    public function setServicesContainer(ContainerBuilder $servicesContainer)
    {
        $this->servicesContainer = $servicesContainer;
    }

    /**
     * @param string $configFileFolder
     * @param string $environment
     */
    public function __construct(string $configFileFolder, string $environment)
    {
        $this->setServicesContainer(new ContainerBuilder());
        $this->environment = $environment;
        $this->mainLocator = new FileLocator($configFileFolder);
    }

    public function run(): void
    {
        $this->establishEnvironment();

        try {
            $this->startSafe();
        } catch (\Throwable $t) {
            echo json_encode(['Result' => ResponseCode::UNKNOWN_ERROR, 'Message' => $t->getMessage()]);
            $this->getLogger()->critical($t->getMessage(), ['object' => $this, 'exception' => $t, 'tags' =>['error']]);
        } catch (\Exception $e) {
            echo json_encode(['Result' => ResponseCode::UNKNOWN_ERROR, 'Message' => $e->getMessage()]);
            $this->getLogger()->critical($e->getMessage(), ['object' => $this, 'exception' => $e, 'tags' =>['error']]);
        }
    }

    abstract protected function startSafe(): void;

    /**
     * Internal method for PHP environment
     */
    protected function establishEnvironment()
    {
        mb_internal_encoding('UTF-8');
        date_default_timezone_set('Europe/Kiev');

        error_reporting(E_ALL);
        set_error_handler(
            function ($errorCode, $errorDescription, $errorFile, $errorLine, array $errorContext = []) {
                throw new PhpException($errorCode, $errorDescription, $errorFile, $errorLine, $errorContext);
            }
        );
    }

    protected function loadMainConfiguration()
    {
        $loader = new YamlFileLoader($this->getServicesContainer(), $this->mainLocator);

        foreach ($this->getServiceConfiguration() as $file) {
            try {
                $loader->load($file);
                //$this->getLogger()->info('Success read config file ' . $file);
            } catch (\InvalidArgumentException $e) {
                $this->getLogger()->debug('Not found config file ' . $file, ['object' => $this]);
            }
        }
    }

    /**
     * Returns list of configuration files, used while application startup
     *
     * @return string[]
     */
    public function getServiceConfiguration() : array
    {
        return [
            'options.yml',
            'container.yml',
            'repositories.yml',
            'components.yml',
            
            'options.' . $this->environment . '.yml',
            'container.' . $this->environment . '.yml',
        ];
    }
}
