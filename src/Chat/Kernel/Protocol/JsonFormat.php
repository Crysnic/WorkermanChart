<?php

declare(strict_types=1);

namespace Chat\Kernel\Protocol;

use Chat\Exception\Protocol\WrongFormatException;

class JsonFormat implements FormatInterface
{
    /**
     * @inheritDoc
     * @throws \LogicException
     * @throws WrongFormatException
     */
    public function decode(string $data) : array
    {
        if (strlen($data) === 0) {
            throw new WrongFormatException('request JSON not found');
        }
        $array = json_decode($data, true);
        if ($array === null && json_last_error() !== JSON_ERROR_NONE) {
            $message = 'JSON decode error';
            if (function_exists('json_last_error_msg')) {
                $message .= ' ' . json_last_error_msg();
            }
            throw new WrongFormatException($message);
        }
        return $array;
    }
}
