<?php

declare(strict_types=1);

namespace Chat\Kernel\Protocol;

use Chat\Exception\Protocol\UnknownCommandException;
use Chat\Util\ConverterClass\ToStringTrait;
use Symfony\Component\DependencyInjection\Exception\OutOfBoundsException;

/**
 * Class RequestBundle
 * @package System\Kernel\Protocol
 */
class RequestBundle
{
    use ToStringTrait;
    
    const COMMAND_KEY = 'Command';

    /**
     * @var string
     */
    private $request;

    /**
     * @var array
     */
    private $params;

    /**
     * RequestBundle constructor.
     * @param string $request
     * @param array $params
     * @throws UnknownCommandException
     */
    public function __construct(
        string $request,
        array $params
    ) {
        if (!isset($params[self::COMMAND_KEY])) {
            throw new UnknownCommandException('Request must contain the "'. self::COMMAND_KEY . '" key');
        }

        $this->request = $request;
        $this->params = $params;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getRequest() : string
    {
        return $this->request;
    }

    /**
     * @return string
     */
    public function getCommand() : string
    {
        return $this->params[self::COMMAND_KEY];
    }

    /**
     * @return array
     */
    public function getParams() : array
    {
        return $this->params;
    }

    /**
     * @param $key
     * @return int
     * @throws OutOfBoundsException
     */
    public function getParamInt($key) : int
    {
        if (!array_key_exists($key, $this->params)) {
            throw new OutOfBoundsException('Array does not have key '. $key);
        }
        return (int)$this->params[$key];
    }

    /**
     * @param $key
     * @return string
     * @throws OutOfBoundsException
     */
    public function getParamString($key) : string
    {
        if (!array_key_exists($key, $this->params)) {
            throw new OutOfBoundsException('Array does not have key '. $key);
        }
        return (string)$this->params[$key];
    }

    /**
     * @param $key
     * @return float
     * @throws OutOfBoundsException
     */
    public function getParamFloat($key) : float
    {
        if (!array_key_exists($key, $this->params)) {
            throw new OutOfBoundsException('Array does not have key '. $key);
        }
        return (float)$this->params[$key];
    }
}
