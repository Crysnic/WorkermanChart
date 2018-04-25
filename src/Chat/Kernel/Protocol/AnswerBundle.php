<?php

declare(strict_types=1);

namespace Chat\Kernel\Protocol;

use Chat\Util\ConverterClass\ToStringTrait;

/**
 * Class AnswerBundle
 * @package System\Kernel\Protocol
 */
class AnswerBundle
{
    use ToStringTrait;
    
    const RESULT_KEY = 'Result';

    /**
     * @var array
     */
    private $params;

    /**
     * @var array
     */
    private $receiver;

    /**
     * AnswerBundle constructor.
     * @param array $params
     * @param array $receiver
     */
    public function __construct(array $params, array $receiver = [])
    {
        $this->params = $params;
        $this->receiver = $receiver;
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
     * @return array
     */
    public function getReceiver(): array
    {
        return $this->receiver;
    }
}
