<?php

declare(strict_types=1);

namespace Chat\Kernel\Protocol;

use Chat\Exception\Protocol\WrongFormatException;

class JsonFormat implements FormatInterface
{
    /**
     * @inheritDoc
     * @throws \LogicException
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

    /**
     * @inheritDoc
     */
    public function encode(AnswerBundle $bundle) : string
    {
        $json_string = json_encode(
            $bundle->getParams(),
            JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT
        );

        if (!$json_string) {
            switch (json_last_error()) {
                case JSON_ERROR_DEPTH:
                    $message = 'Maximum stack depth exceeded';
                    break;
                case JSON_ERROR_STATE_MISMATCH:
                    $message = 'Invalid or malformed JSON';
                    break;
                case JSON_ERROR_CTRL_CHAR:
                    $message = 'Unexpected control character found';
                    break;
                case JSON_ERROR_SYNTAX:
                    $message = 'Syntax error, malformed JSON';
                    break;
                case JSON_ERROR_UTF8:
                    $message = 'Invalid UTF-8 characters, possibly incorrect encoding';
                    break;
                case JSON_ERROR_NONE:
                default:
                    $message = 'Unknown error';
                    break;
            }
            throw new WrongFormatException($message);
        }

        return $json_string;
    }
}
