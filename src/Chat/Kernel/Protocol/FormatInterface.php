<?php

declare(strict_types=1);

namespace Chat\Kernel\Protocol;

/**
 * Interface FormatInterface
 * @package System\Kernel\Protocol
 */
interface FormatInterface
{
    /**
     * @param string $data
     * @return array
     */
    public function decode(string $data) : array;
}
