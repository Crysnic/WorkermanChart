<?php

declare(strict_types=1);

namespace Chat\Kernel\Protocol;

use Chat\Entity\InternalProtocol\ResponseCode;
use Chat\Util\ConverterClass\ToStringTrait;

/**
 * Class AnswerBundle
 * @package System\Kernel\Protocol
 */
class AnswerBundle
{
    use ToStringTrait;
    
    const RESULT_KEY = 'Result';
    const TIME_KEY = 'Time';

    /**
     * @var array
     */
    private $params;

    /**
     * @var string
     */
    private $key = '';

    /**
     * @var string
     */
    private $signature;

    /**
     * @var string
     */
    private $contentType = 'application/json';

    /**
     * @param array $parameters
     * @return AnswerBundle
     */
    public static function buildSuccess($parameters = []): AnswerBundle
    {
        $parameters[self::RESULT_KEY] = ResponseCode::SUCCESS_ACTION;
        $parameters[self::TIME_KEY] = date('Y-m-d H:i:s');

        return new AnswerBundle($parameters);
    }

    /**
     * AnswerBundle constructor.
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->params = $params;
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
     * @param $value
     */
    public function addParam($key, $value)
    {
        $this->params[$key] = $value;
    }

    /**
     * @return string
     */
    public function getKey() : string
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey(string $key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getSignature() : string
    {
        return $this->signature;
    }

    /**
     * @param string $signature
     */
    public function setSignature(string $signature)
    {
        $this->signature = $signature;
    }

    /**
     * @return string
     */
    public function getContentType() : string
    {
        return $this->contentType;
    }

    /**
     * @param string $contentType
     */
    public function setContentType(string $contentType)
    {
        $this->contentType = $contentType;
    }
}
